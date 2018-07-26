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
        $result = mysqli_query($connect, "SELECT * FROM users")
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
        </div>
        <div id="wrap">
            <div class="row">
                <div class="col-md-3">
                    <div class="panel panel-success">
                        <div class="panel-heading">Lista użytkowników</div>

                        <div class="panel-body">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>#</th>
                                    <th>Nazwa użytkownika</th>
                                </tr>
                                <?php
                                $ile = mysqli_num_rows($result);
                                for ($i = 1; $i <= $ile; $i++) {
                                    $row = mysqli_fetch_assoc($result);
                                    $id = $row['id'];
                                    $login = $row['login'];
                                    echo<<<END
	<tr>
        <td></td>
	<td >$login</td>
	</tr>
END;
                                }
                                mysqli_free_result($result);
                                ?>
                            </table> 
                        </div>
                    </div>
                </div>  
                <div class="col-md-5 col-md-offset-4">
                    <?php
                                    if (isset($_SESSION['e_pass'])) {
                                        echo '<div class="alert alert-danger" role="alert">' . $_SESSION['e_pass'] . '</div>';
                                        unset($_SESSION['e_pass']);
                                    }
                                    if (isset($_SESSION['e_login'])) {
                                        echo '<div class="alert alert-danger" role="alert">' . $_SESSION['e_login'] . '</div>';
                                        unset($_SESSION['e_login']);
                                    }
                                    if (isset($_SESSION['e_uppas'])) {
                                        echo '<div class="alert alert-success" role="alert">' . $_SESSION['e_uppas'] . '</div>';
                                        unset($_SESSION['e_uppas']);
                                    }
                                    ?>
                    <div class="panel panel-info">
                        <div class="panel-heading">Zmiana hasła</div>

                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="post" action="newpass.php">
                                <div class="form-group">
                                    <label for="login" class="col-sm-3 control-label">Podaj nazwę użytkownika</label>
                                    <input type="text" name="login" id="login" placeholder="Login">
                                </div>
                                <div class="form-group">
                                    <label for="pass" class="col-sm-3 control-label">Podaj nowe hasło</label>
                                    <input type="password" name="pass" id="pass" placeholder="***********">
                                </div>
                                <div class="form-group">
                                    <label for="pass2" class="col-sm-3 control-label">Podaj nowe hasło ponownie</label>
                                    <input type="password" name="pass2" id="pass2" placeholder="***********">
                                </div>
                        <div class="form-group ">
                        <input type="submit" value="Kontynuuj" class="btn btn-success col-md-offset-4"/>
                            </div>
                        </div>
                                
                    </div>
                </div>
            </div>


        </div>







    </body>
</html>