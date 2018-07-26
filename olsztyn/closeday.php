<?php
ob_start();
session_start();
if (!isset($_SESSION['loging'])) {
    header("Location: ../index.php");
    exit();
}
if ($_SESSION['perm'] !== '1') {
    if ($_SESSION['perm'] !== '2') {
        $_SESSION['e_perm'] = "Nie masz uprawnień żeby zobaczyć tę stronę!";
        header("Location:../home.php");
        exit();
    }
}
require_once "../connect.php";

$connect = new mysqli($host, $db_user, $db_password, $db_name);
$adate = $_POST['date'];
$query = ("CREATE TABLE `ufung_power`.`$adate` ( `id` INT NOT NULL, `adres` TEXT NOT NULL , `kwota` DECIMAL(9,2) NOT NULL , `typ_plat`TEXT NOT NULL, `kierowca`TEXT NOT NULL, `pojazd`TEXT NOT NULL ) ENGINE = InnoDB");
$query2 = ("INSERT INTO `ufung_power`.`$adate` SELECT * FROM `ufung_power`.`zamowienia`");
$query3 = ("TRUNCATE TABLE `zamowienia`");
$query4 = ("INSERT INTO `closed` (`id`, `name`) VALUES (NULL, '$adate')");
if ($connect->connect_errno != 0) {
    echo "Error: " . $connect->connect_errno;
} else {
    if (empty($adate)) {
        $_SESSION['e_date'] = "Wybierz poprawną datę!";
        header('Location: olsztyn/orders.php');
    } else {
        if (mysqli_query($connect, $query)) {
            echo " Baza danych utworzona ";
            if (mysqli_query($connect, $query2)) {
                echo " Baza danych skopiowana ";
                if (mysqli_query($connect, $query3)) {
                    echo " Baza danych wyczyszczona ";
                    if (mysqli_query($connect, $query4)) {
                        header('Location: orders.php');
                    } else {
                        echo "Wystąpił błąd przy dodawaniu rekordu" . mysqli_error($connect);
                    }
                } else {
                    echo " czyszczenie bazy danych wystąpił błąd: " . mysqli_error($connect);
                }
            } else {
                echo " przenoszenie bazy danych wystąpił błąd: " . mysqli_error($connect);
            }
        } else {
            echo " Tworzenie bazy danych wystąpił błąd: " . mysqli_error($connect);
        }
    }
}
ob_end_flush();
?>