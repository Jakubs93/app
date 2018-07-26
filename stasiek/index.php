<?php
session_start();
if (!isset($_SESSION['loging'])) {
    header('location: ../index.php');
    exit();
}
if ($_SESSION['perm'] !== '1') {
    if ($_SESSION['perm'] !== '3') {
        $_SESSION['e_perm'] = "Nie masz uprawnień żeby zobaczyć tę stronę!";
        header('Location: ../home.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="../style.css" rel="stylesheet" type="text/css"/>
        <meta charset="utf-8" />
        <meta name="robots" content="NOFOLLOW,NOINDEX"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>Panel pracowniczy</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div id="wrap">
<?php
            require_once '../includes/header_stasiek.php';
?>
        </body>
        </html>