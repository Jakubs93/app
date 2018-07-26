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
require_once "../includes/connect_stasiek.php";
mysqli_report(MYSQLI_REPORT_STRICT);
try {
    $connect = new mysqli($host, $db_user, $db_password, $db_name);
    if ($connect->connect_errno != 0) {
        throw new Exception(mysqli_connect_errno());
    } else {
        mysqli_select_db($connect, $db_name);
        $result = mysqli_query($connect, "SELECT * FROM zamowienia ORDER BY id DESC")
                or die(mysqli_error());
    }
} catch (Exception $e) {
    echo '<span style="color:red;">Błąd serwera! Spróbuj ponownie</span>';
    echo '<br />Informacja developerska: ' . $e;
}
?>
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
            <?php
            require_once '../includes/header_stasiek.php';
            ?>
<div><font color="red" size="30"><b>Usługa wygaśnie za: <span id="time"></span></b></font><p>Aby przedłużyć możliwość korzystania z panelu skontaktuj się z autorem</p></div>
<script>

var countDownDate = new Date("Jul 22, 2018 23:59:59").getTime();
var x = setInterval(function() {
  var now = new Date().getTime();
  var distance = countDownDate - now;
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
  document.getElementById("time").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("time").innerHTML = "Licencja wygasła";
  }
}, 1000);
</script>
            <div class="row">
                <div class="col-md-7">
                    <?php
                    if (isset($_SESSION['edtrue']))
                        echo $_SESSION['edtrue'];
                    unset($_SESSION['edtrue']);


                    if (isset($_SESSION['e_date'])) {
                        echo '<div class="alert alert-danger" role="alert">' . $_SESSION['e_date'] . '</div>';
                        unset($_SESSION['e_date']);
                    }
                    ?>
                    <div class="panel panel-success">
                        <div class="panel-heading">Aktualne wyjazdy</div>

                        <div class="panel-body">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>#</th>
                                    <th>Adres</th>
                                    <th>Kwota</th>
                                    <th>Typ płatności</th>
                                    <th>Numer kierowcy</th>
                                    <th>Akcja</th>
                                </tr>
                                <?php
                                $ile = mysqli_num_rows($result);
                                for ($i = 1; $i <= $ile; $i++) {
                                    $row = mysqli_fetch_assoc($result);
                                    $id = $row['id'];
                                    $adres = $row['adres'];
                                    $kwota = $row['kwota'];
                                    $typ_plat = $row['typ_plat'];
                                    $kierowca = $row['kierowca'];
                                    echo<<<END
	<tr>
	<td >$id</td>
	<td >$adres</td>
	<td >$kwota zł</td>
	<td >$typ_plat</td>
	<td >$kierowca</td>
	<td><a href="edit.php?id=$id" class="btn btn-sm btn-default">Edytuj</a>
	</tr>
END;
                                }
                                mysqli_free_result($result);
                                ?>
                            </table>
                            <button type="button" class="btn btn-danger btn-md" data-toggle="modal" data-target="#myModal">Zakończ dzień</button>
                            <div id="myModal" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Uwaga!</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Napewno zakończyć dzień? Operacja jest nieodwracalna!</p>
                                            <p> Aby zakończyć dzień, wybierz odpowiednią datę:</p>
                                            <form class="form-horizontal" role="form" method="post" action="closeday.php">
                                                <input type="date" name="date" id="date">
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="submit" id="date" value="Kontynuuj" class="btn btn-danger"/>
                                            </form>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="panel panel-info">
                        <div class="panel-heading">Aktualny stan kasy</div>
                        <div class="panel-body">
                            <dl class="dl-horizontal">
                                <?php
                                $query = "SELECT SUM(kwota) AS suma1 FROM zamowienia WHERE typ_plat!='Karta' AND typ_plat!='BON' AND kierowca='kierowca 1'";
                                $result = mysqli_query($connect, $query);
                                $row = mysqli_fetch_array($result);
                                $suma1 = $row['suma1'];
                                if (empty($suma1)) {
                                    $_SESSION['nosum'] = "0.00";
                                }
                                echo "<dt>$suma1";
                                if (isset($_SESSION['nosum'])) {
                                    echo $_SESSION['nosum'];
                                    unset($_SESSION['nosum']);
                                } echo" zł</dt><dd>Gotówka kierowcy 1</dd>";
                                mysqli_free_result($result);
                                ?>

                                <?php
                                $query = "SELECT SUM(kwota) AS suma2 FROM zamowienia WHERE typ_plat!='Karta' AND typ_plat!='BON'  AND kierowca='kierowca 2'";
                                $result = mysqli_query($connect, $query);
                                $row = mysqli_fetch_array($result);
                                $suma2 = $row['suma2'];
                                if (empty($suma2)) {
                                    $_SESSION['nosum'] = "0.00";
                                }
                                echo "<dt>$suma2";
                                if (isset($_SESSION['nosum'])) {
                                    echo $_SESSION['nosum'];
                                    unset($_SESSION['nosum']);
                                } echo " zł</dt><dd>Gotówka kierowcy 2</dd>";
                                mysqli_free_result($result);
                                ?>


                                <?php
                                $query = "SELECT SUM(kwota) AS suma3 FROM zamowienia WHERE typ_plat!='Karta' AND typ_plat!='BON' AND kierowca='kierowca 3'";
                                $result = mysqli_query($connect, $query);
                                $row = mysqli_fetch_array($result);
                                $suma3 = $row['suma3'];
                                if (empty($suma3)) {
                                    $_SESSION['nosum'] = "0.00";
                                }
                                echo "<dt>$suma3";
                                if (isset($_SESSION['nosum'])) {
                                    echo $_SESSION['nosum'];
                                    unset($_SESSION['nosum']);
                                } echo " zł</dt><dd>Gotówka kierowcy 3</dd>";
                                mysqli_free_result($result);
                                ?>
                                
                                <?php
                                $query = "SELECT SUM(kwota) AS suma10 FROM zamowienia WHERE typ_plat!='Karta' AND typ_plat!='BON' AND kierowca='kierowca 4'";
                                $result = mysqli_query($connect, $query);
                                $row = mysqli_fetch_array($result);
                                $suma10 = $row['suma10'];
                                if (empty($suma10)) {
                                    $_SESSION['nosum'] = "0.00";
                                }
                                echo "<dt>$suma10";
                                if (isset($_SESSION['nosum'])) {
                                    echo $_SESSION['nosum'];
                                    unset($_SESSION['nosum']);
                                } echo " zł</dt><dd>Gotówka kierowcy 4</dd>";
                                mysqli_free_result($result);
                                ?>

                                <?php
                                $query = "SELECT SUM(kwota) AS suma4 FROM zamowienia WHERE typ_plat!='Karta' AND typ_plat!='BON' AND kierowca='kucharz'";
                                $result = mysqli_query($connect, $query);
                                $row = mysqli_fetch_array($result);
                                $suma4 = $row['suma4'];
                                if (empty($suma4)) {
                                    $_SESSION['nosum'] = "0.00";
                                }
                                echo "<dt>$suma4";
                                if (isset($_SESSION['nosum'])) {
                                    echo $_SESSION['nosum'];
                                    unset($_SESSION['nosum']);
                                } echo " zł</dt><dd>Gotówka kucharza</dd>";
                                mysqli_free_result($result);
                                ?>
                                </br>
                                <?php
                                $query = "SELECT SUM(kwota) AS suma5 FROM zamowienia WHERE typ_plat!='Karta' AND typ_plat!='BON' AND kwota < '0'";
                                $result = mysqli_query($connect, $query);
                                $row = mysqli_fetch_array($result);
                                $suma5 = $row['suma5'];
                                if (empty($suma5)) {
                                    $_SESSION['nosum'] = "0.00";
                                }
                                echo "<dt><span style='color:red'>$suma5";
                                if (isset($_SESSION['nosum'])) {
                                    echo $_SESSION['nosum'];
                                    unset($_SESSION['nosum']);
                                } echo " zł</span></dt><dd>Wydatki</dd> ";
                                mysqli_free_result($result);
                                ?>
                                <?php
                                $query = "SELECT SUM(kwota) AS suma6 FROM zamowienia WHERE typ_plat!='Karta' AND typ_plat!='BON'";
                                $result = mysqli_query($connect, $query);
                                $row = mysqli_fetch_array($result);
                                $suma6 = $row['suma6'];
                                if (empty($suma6)) {
                                    $_SESSION['nosum'] = "0.00";
                                }
                                echo "<dt>$suma6";
                                if (isset($_SESSION['nosum'])) {
                                    echo $_SESSION['nosum'];
                                    unset($_SESSION['nosum']);
                                } echo " zł</dt><dd>Całkowita ilość gotówki</dd> ";
                                mysqli_free_result($result);
                                ?>

                                <?php
                                $query = "SELECT SUM(kwota) AS suma7 FROM zamowienia WHERE typ_plat='Karta'";
                                $result = mysqli_query($connect, $query);
                                $row = mysqli_fetch_array($result);
                                $suma7 = $row['suma7'];
                                if (empty($suma7)) {
                                    $_SESSION['nosum'] = "0.00";
                                }
                                echo "<dt>$suma7";
                                if (isset($_SESSION['nosum'])) {
                                    echo $_SESSION['nosum'];
                                    unset($_SESSION['nosum']);
                                } echo " zł</dt><dd>Płatność kartą</dd> ";
                                mysqli_free_result($result);
                                ?>

                                <?php
                                $query = "SELECT SUM(kwota) AS suma8 FROM zamowienia WHERE typ_plat='BON'";
                                $result = mysqli_query($connect, $query);
                                $row = mysqli_fetch_array($result);
                                $suma8 = $row['suma8'];
                                if (empty($suma8)) {
                                    $_SESSION['nosum'] = "0.00";
                                }
                                echo "<dt>$suma8";
                                if (isset($_SESSION['nosum'])) {
                                    echo $_SESSION['nosum'];
                                    unset($_SESSION['nosum']);
                                } echo " zł</dt><dd>Płatność DOTPAY</dd> ";
                                mysqli_free_result($result);
                                ?>
                                </br>
                                <?php
                                $query = "SELECT SUM(kwota) AS suma9 FROM zamowienia WHERE kwota > '0'";
                                $result = mysqli_query($connect, $query);
                                $row = mysqli_fetch_array($result);
                                $suma9 = $row['suma9'];
                                if (empty($suma9)) {
                                    $_SESSION['nosum'] = "0.00";
                                }
                                echo "<dt>$suma9";
                                if (isset($_SESSION['nosum'])) {
                                    echo $_SESSION['nosum'];
                                    unset($_SESSION['nosum']);
                                } echo " zł</dt><dd>Stan kasy</dd> ";
                                mysqli_free_result($result);
                                ?>
                                
                            </dl>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </body>
</html>