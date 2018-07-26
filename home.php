<?php
ob_start();
session_start();
if (!isset($_SESSION['loging'])) {
    header('location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="style.css" rel="stylesheet" type="text/css"/>
        <meta charset="utf-8" />
        <meta name="robots" content="NOFOLLOW,NOINDEX"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>Panel pracowniczy</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div id="wrap">
            
            
            <div id="wrap">
                <div class="row">
                    <form  method="post" accept-charset="utf-8"> 
                        <div class="form-group">
                            <!--<label class="col-sm-1 control-label" for="place">Wybierz lokal</label> -->
                            <div class="col-sm-3 col-sm-offset-4">
                                <select class="form-control" id="place" name="place">
                                    <option disabled selected>Wybierz...</option>
                                    <option>olsztyn</option>
									<option>stasiek</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3 col-sm-offset-4">
                            <button class="btn btn-lg btn-primary btn-block" type="submit">Wybierz lokal</button>
<?php
                            if (!empty($_POST['place'])) {
                                $place = $_POST['place'];
                                header("Location: $place/index.php");
                            } else {
                                //$_SESSION['e_place'] = "Wybierz lokal!";
                            }
ob_end_flush();
?>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>