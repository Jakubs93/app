<?php
session_start();
if ((isset($_SESSION['loging'])) && ($_SESSION['loging'] == true)) 
{
    header('location: home.php');
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
            <div class="row">
                    <div class="col-md-3 col-md-offset-4">
                <form action="sign.php" method="post" accept-charset="utf-8">                     
                    <label class="sr-only" for="login">Login</label>
                    <input id="login" name="login" type="text" class="form-control" placeholder="Login" autofocus>
                    <label class="sr-only" for="password">Hasło</label>
                    <input id="password" name="password" type="password" class="form-control" placeholder="Hasło">
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Zaloguj</button>

                    <?php
                    if (isset($_SESSION['error'])) {
                        echo $_SESSION['error'];
                    }
                    ?>
                </form>
                    </div>
            </div>
        </div>
    </body>
</html>
