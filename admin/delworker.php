<?php

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
$id = $_GET['id'];
$connect = new mysqli($host, $db_user, $db_password, $db_name);
$result = mysqli_query($connect, "SELECT name FROM workers WHERE workers.id = '$id'");
$row = mysqli_fetch_array($result);
$name = $row['name'];
$connect->query("DELETE FROM `workers` WHERE `workers`.`id` = '$id'");
$connect->query("DROP TABLE `$name`");
$_SESSION['deldtrue'] = '<div class="alert alert-success" role="alert">Pomyślnie usunięto pracownika!</div>';
header("Location:workers.php");
