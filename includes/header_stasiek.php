<header><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container container-fluid">
            <div class="collapse navbar-collapse navbar-inverse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">

                    <li><a href="orders.php">Lista Zamówień  <span class="glyphicon glyphicon-list-alt"></span></a></li>
                    <li><a href="neworder.php">Nowe zamówienie  <span class="glyphicon glyphicon-plus"></span></a></li>
                    
                    <li><a href='closed.php'>Archiwum  <span class='glyphicon glyphicon-lock'></span></a></li>
                    <?php
                    if ($_SESSION['perm'] == '1') {

                        echo "<li><a href='../admin/index.php'>Panel Administracyjny  <span class='glyphicon glyphicon-adjust'></span></a></li>";
                    }
                    ?>

                </ul>
                <ul class="nav navbar-nav navbar-right">

                    <a class="btn btn-danger" href="../logout.php" role="button"><span class="glyphicon glyphicon-off"></span></a>

                </ul>
            </div>
        </div>
    </div>

</header>