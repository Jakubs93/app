<?php

session_start();
if (!isset($_SESSION['loging'])) {
    header("Location: ../index.php");
    exit();
}
if ($_SESSION['perm'] !== '1') {
    if ($_SESSION['perm'] !== '3') {
        $_SESSION['e_perm'] = "Nie masz uprawnień żeby zobaczyć tę stronę!";
        header("Location:../home.php");
        exit();
    }
}

$adres = $_POST['adres'];
$kwota = $_POST['kwota'];
$typ_plat = $_POST['typ_plat'];
$kierowca = $_POST['kierowca'];
require_once "../includes/connect_stasiek.php";
mysqli_report(MYSQLI_REPORT_STRICT);
try {
    $connect = new mysqli($host, $db_user, $db_password, $db_name);
    if ($connect->connect_errno != 0) {
        throw new Exception(mysqli_connect_errno());
    } else {
        mysqli_select_db($connect, $db_name);
        if (empty($adres)) {
            $OK = false;
            $_SESSION['e_adres'] = "Podaj poprawny adres";
        } else {
            $OK = true;
        }
        $kwota = str_replace(",", ".", $kwota);
        
        if (empty($typ_plat)) {
            $OK = false;
            $_SESSION['e_typ_plat'] = "Wybierz typ płatności";
        } else {
            $OK = true;
        }
        if (empty($kierowca)) {
            $OK = false;
            $_SESSION['e_kierowca'] = "Wybierz kierowcę";
        } else {
            $OK = true;
        }
        if ($OK == false) {
            header("Location:neworder.php");
            exit();
        } else {
            $connect->query("INSERT INTO zamowienia VALUES (NULL, '$adres', '$kwota', '$typ_plat', '$kierowca')");
            header('Location: orders.php');
        }
    }
} catch (Exception $e) {
    echo '<span style="color:red;">Błąd serwera! Spróbuj ponownie</span>';
    echo '<br />Informacja developerska: ' . $e;
}