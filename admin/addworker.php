<?php
session_start();
if (!isset($_SESSION['loging'])) {
    header('location: ../index.php');
    exit();
}
if ($_SESSION['perm'] !== '1') {


    $_SESSION['e_perm'] = "Nie masz uprawnień żeby zobaczyć tę stronę!";
    header('Location: ../home.php');
    exit();
}
require_once "../connect.php";

$name = $_POST['name'];
$place = $_POST['place'];

mysqli_report(MYSQLI_REPORT_STRICT);
try {
    $connect = new mysqli($host, $db_user, $db_password, $db_name);
    if ($connect->connect_errno != 0) {
        throw new Exception(mysqli_connect_errno());
    } else {
        mysqli_select_db($connect, $db_name);
        if (empty($name)) {
            $OK = false;
            $_SESSION['e_name'] = "Podaj imię i nazwisko pracownika";
        } else {
            
            $OK = true;
        }        
        if (empty($place)) {
            $OK = false;
            $_SESSION['e_place'] = "Wybierz lokal pracownika";
        } else {
            $lplace=  strtolower($place);
            $OK = true;
        }
        
        if ($OK == false) {
            header("Location:workers.php");
            exit();
        } else {
            $connect->query("INSERT INTO workers VALUES (NULL, '$name', '$lplace')");
            $connect->query("CREATE TABLE `power`.`$name` ( `id` INT NOT NULL AUTO_INCREMENT, `data` TEXT NOT NULL , `godz` TEXT NOT NULL , `ilosc` INT NOT NULL, `utarg` DECIMAL(9,2) NOT NULL, PRIMARY KEY  (`id`)) ENGINE = InnoDB");
            $_SESSION['addtrue'] = '<div class="alert alert-success" role="alert">Pomyślnie dodano nowego pracownika!</div>';
            header('Location: workers.php');
        }
    }
} catch (Exception $e) {
    echo '<span style="color:red;">Błąd serwera! Spróbuj ponownie</span>';
    echo '<br />Informacja developerska: ' . $e;
}
