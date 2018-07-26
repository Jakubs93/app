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
	$query = "SELECT * FROM workers WHERE id='$id'";
	$result = mysqli_query($connect, $query);
	$row = mysqli_fetch_array($result);
	$name= $row['name'];
	$place= $row['place'];
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div id="wrap">
            <header>
                <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                    <div class="container container-fluid">
                        <div class="collapse navbar-collapse navbar-inverse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                               
                                <li><a href='workers.php'>Pracownicy  <span class="glyphicon glyphicon-user"></span></a></li>
                                   <li><a href='#'>Grafik  <span class="glyphicon glyphicon-blackboard"></span></a></li>
                                   <li><a href='../home.php'>Lokale  <span class="glyphicon glyphicon-home"></span></a></li>
                                                                   
                            </ul>
                            <ul class="nav navbar-nav navbar-right">

                                <a class="btn btn-danger" href="../logout.php" role="button"><span class="glyphicon glyphicon-off"></span></a>

                            </ul>
                        </div>
                    </div>
                </div>

            </header>
            