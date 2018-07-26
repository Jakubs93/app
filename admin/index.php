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
            <header>
                <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                    <div class="container container-fluid">
                        <div class="collapse navbar-collapse navbar-inverse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                        <li><a href='../home.php'>Lokale  <span class="glyphicon glyphicon-home"></span></a></li>
                                   <li><a href='pass.php'>Hasła  <span class="glyphicon glyphicon-cog"></span></a></li>
                                                                   
                            </ul>
                            <ul class="nav navbar-nav navbar-right">

                                <a class="btn btn-danger" href="../logout.php" role="button"><span class="glyphicon glyphicon-off"></span></a>

                            </ul>
                        </div>
                    </div>
                </div>

            </header>
