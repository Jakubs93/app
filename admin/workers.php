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
mysqli_report(MYSQLI_REPORT_STRICT);
try {
    $connect = new mysqli($host, $db_user, $db_password, $db_name);
    if ($connect->connect_errno != 0) {
        throw new Exception(mysqli_connect_errno());
    } else {
        mysqli_select_db($connect, $db_name);
        $result = mysqli_query($connect, "SELECT * FROM workers")
                or die(mysqli_error());
    }
} catch (Exception $e) {
    echo '<span style="color:red;">Błąd serwera! Spróbuj ponownie</span>';
    echo '<br />Informacja developerska: ' . $e;
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

            <div class="col-md-7 col-sm-offset-2">
                            <div class="panel panel-success">
                    <div class="panel-heading">Pracownicy</div>

                    <div class="panel-body">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>Imię i nazwisko</th>
                                <th>Lokal</th>
                                <th>Akcja</th>
                            </tr>
                            <?php
                            $ile = mysqli_num_rows($result);
                            for ($i = 1; $i <= $ile; $i++) {
                                $row = mysqli_fetch_assoc($result);
                                $id = $row['id'];
                                $name = $row['name'];
                                $place = $row['place'];
                                echo<<<END
	<tr>
	<td >$name</td>
	<td >$place</td>

	<td><a href="#" class="btn btn-sm btn-default">Edytuj</a> <a href="delworker.php?id=$id" class="btn btn-sm btn-danger">Usuń</a></td>
	</tr>
END;
                            }
                            mysqli_free_result($result);
                            ?>
                        </table>
                        <button type="button" class="btn btn-danger btn-md" data-toggle="modal" data-target="#myModal">Dodaj Pracownika</button>
                        <div id="myModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Dodawanie nowego pracownika</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>Aby dodać nowego pracownika należy wypełnić wszystkie pola:</p>

                                        <form class="form-horizontal" role="form" method="post" action="addworker.php">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label">Podaj dane</label>
                                                <input type="text" name="name" id="name" placeholder="Imię i nazwisko">
                                            </div>
                                            <div class="form-group">
                                                <label for="place" class="col-sm-2 control-label">Wybierz lokal</label>
                                                <input type="text" name="place" id="place" placeholder="Restauracja">
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="submit" value="Kontynuuj" class="btn btn-success"/>
                                        </form>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij okno</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>