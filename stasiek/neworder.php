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
?>
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
            <div class="row">
                <div class="panel panel-success">
                    <div class="panel-heading">Dodaj miejsce, w które się udajesz!</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="post" action="addorder.php">
                            <div class="form-group">
                                <label for="adres" class="col-sm-2 control-label">Adres dostawy</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control " id="adres" name="adres" placeholder="Adres dostawy" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="kwota" class="col-sm-2 control-label">Kwota zamowienia</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="kwota" name="kwota" placeholder="00.00" required>
                                </div>
                            </div>

                            <div class="form-group">		
                                <label for="typ_plat" class="col-sm-2 control-label">Typ płatności</label>
                                <div class="col-sm-2">
                                    <select name="typ_plat" id="typ_plat" class="form-control" required>
                                        <option disabled selected>Wybierz..
                                        <option>Gotówka
                                        <option>Karta
                                        <option>BON
                                    </select>
                                </div>
                            </div>	

                            <div class="form-group">		
                                <label for="kierowca" class="col-sm-2 control-label">Dostawca</label>
                                <div class="col-sm-2 ">
                                    <select name="kierowca" id="kierowca" class="form-control" required>
                                        <option disabled selected>Wybierz..
                                        <option>Kierowca 1
                                        <option>Kierowca 2
                                        <option>Kierowca 3
                                        <option>Kierowca 4
                                        <option>Kucharz
                                    </select>
                                </div>
                            </div>

                            

                            <div class="col-sm-6 col-sm-offset-3">
                                <input type="submit" value="Dodaj zamówienie" class="btn btn-primary"/>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </body>
</html>