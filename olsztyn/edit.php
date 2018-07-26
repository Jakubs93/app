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
$id = $_GET ['id'];
$connect = new mysqli($host, $db_user, $db_password, $db_name);
$query = "SELECT * FROM zamowienia WHERE id='$id'";
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_array($result);
$adres = $row['adres'];
if (empty($adres)) {
    $_SESSION['n_adres'] = "(brak)";
}
$kwota = $row['kwota'];
$typ_plat = $row['typ_plat'];
$kierowca = $row['kierowca'];
$pojazd = $row['pojazd'];

if (isset($_POST['adres'])) {
    $OK = true;
    $newadres = $_POST['adres'];
    if ((strlen($newadres) < 3) || (strlen($newadres) > 30)) {
        $OK = false;
        $_SESSION['e_adres'] = "Adres musi posiadać od 3 do 30 znaków!";
    }
    $newkwota = $_POST['kwota'];

    $newkwota = str_replace(",", ".", $newkwota);
    $newtyp_plat = $_POST['typ_plat'];
    if (empty($newtyp_plat)) {
        $OK = false;
        $_SESSION['e_typ_plat'] = "Wybierz typ płatności";
    }
    $newkierowca = $_POST['kierowca'];
    if (empty($newkierowca)) {
        $OK = false;
        $_SESSION['e_kierowca'] = "Wybierz numer kierowcy";
    }
    $newpojazd = $_POST['pojazd'];
    if (empty($newpojazd)) {
        $OK = false;
        $_SESSION['e_pojazd'] = "Wybierz środek transportu";
    }
    $_SESSION['fr_adres'] = $newadres;
    $_SESSION['fr_kwota'] = $kwota;
    $_SESSION['fr_typ_plat'] = $newtyp_plat;
    $_SESSION['fr_kierowca'] = $newkierowca;
    $_SESSION['fr_pojazd'] = $newpojazd;
    require_once "../connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    try {
        $connect = new mysqli($host, $db_user, $db_password, $db_name);
        if ($connect->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            if ($OK == true) {
                if ($connect->query("UPDATE zamowienia SET adres='$newadres', kwota='$newkwota', typ_plat='$newtyp_plat', kierowca='$newkierowca', pojazd='$newpojazd' WHERE id='$id'")) {
                    $_SESSION['edtrue'] = '<div class="alert alert-success" role="alert">Pomyślnie zaktualizowano!</div>';
                    header('Location: ../olsztyn/orders.php');
                } else {
                    throw new Exception($connect->error);
                }
            }
            $connect->close();
        }
    } catch (Exception $e) {
        echo '<span style="color:red;">Błąd serwera! Spróbuj ponownie</span>';
        echo '<br />Informacja developerska: ' . $e;
    }
}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="NOFOLLOW,NOINDEX" />
        <title>Panel pracowniczy</title>
        <link rel="stylesheet" type="text/css" href="../style.css">
        <!-- Bootstrap -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style>
            .ed
            {
                font-weight: normal;
            }
        </style>
    </head>
    <body>
        <div id="wrap">
            <?php
            require_once '../includes/header.php';
            ?>
            <div class="row">
                <div class="col-md-7">
                    <div class="panel panel-default">
                        <div class="panel-heading">Edycja zamówienia numer <b><?php echo $id; ?></b></div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="post">
                                <div class="form-group">
                                    <label for="adres" class="col-sm-3 control-label"><?php echo $adres;
                                if (isset($_SESSION['n_adres'])) {
                                    echo $_SESSION['n_adres'];
                                    unset($_SESSION['n_adres']);
                                } ?> <span class="ed">zamień na:</span></label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="adres" name="adres" value="<?php echo $adres; ?>">
<?php
if (isset($_SESSION['e_adres'])) {
    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['e_adres'] . '</div>';
    unset($_SESSION['e_adres']);
}
?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="kwota" class="col-sm-3 control-label"><?php echo $kwota; ?>zł <span class="ed">zamień na:</span></label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="kwota" name="kwota" value="<?php echo $kwota; ?>">
                                        <?php
                                        if (isset($_SESSION['e_kwota'])) {
                                            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['e_kwota'] . '</div>';
                                            unset($_SESSION['e_kwota']);
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="form-group">		
                                    <label for="typ_plat" class="col-sm-3 control-label"><?php echo $typ_plat; ?> <span class="ed">zamień na:</span></label>
                                    <div class="col-sm-3">
                                        <select name="typ_plat" id="typ_plat" class="form-control">
                                            <option><?php echo $typ_plat; ?>
                                            <option>Gotówka
                                            <option>Karta
                                            <option>BON
                                        </select>
                                        <?php
                                        if (isset($_SESSION['e_typ_plat'])) {
                                            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['e_typ_plat'] . '</div>';
                                            unset($_SESSION['e_typ_plat']);
                                        }
                                        ?>
                                    </div>
                                </div>	

                                <div class="form-group">		
                                    <label for="kierowca" class="col-sm-3 control-label"><?php echo $kierowca; ?> <span class="ed">zamień na:</span></label>
                                    <div class="col-sm-3 ">
                                        <select name="kierowca" id="kierowca" class="form-control">
                                            <option><?php echo $kierowca; ?>
                                            <option>Kierowca 1
                                            <option>Kierowca 2
                                            <option>Kierowca 3
                                            <option>Kierowca 4
                                            <option>Kucharz
                                        </select>
                                        <?php
                                        if (isset($_SESSION['e_kierowca'])) {
                                            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['e_kierowca'] . '</div>';
                                            unset($_SESSION['e_kierowca']);
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">		
                                    <label for="pojazd" class="col-sm-3 control-label"><?php echo $pojazd; ?> <span class="ed">zamień na:</span></label>
                                    <div class="col-sm-3">
                                        <select name="pojazd" id="pojazd" class="form-control">
                                            <option><?php echo $pojazd; ?>
                                            <option>Panda
                                            <option>Własny
                                            <option>W lokalu
                                        </select>
                                        <?php
                                        if (isset($_SESSION['e_pojazd'])) {
                                            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['e_pojazd'] . '</div>';
                                            unset($_SESSION['e_pojazd']);
                                        }
                                        ?>

                                    </div>
                                </div>	
                                <div class="col-sm-6 col-sm-offset-3">
                                    <input type="submit" value="Zapisz zmiany" class="btn btn-primary"/>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="panel panel-danger ">
                        <div class="panel-heading">Uwaga!</div>
                        <div class="panel-body">

                            <p>Nalezy wypełnić wszystkie pola.</p>
                        </div>
                    </div>
                    </body>
                    </html>