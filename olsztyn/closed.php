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
require_once "../includes/connect.php";

mysqli_report(MYSQLI_REPORT_STRICT);
try {
    $connect = new mysqli($host, $db_user, $db_password, $db_name);
    if ($connect->connect_errno != 0) {
        throw new Exception(mysqli_connect_errno());
    } else {
        $query = "SELECT * FROM closed";
        $result = mysqli_query($connect, $query);
    }
} catch (Exception $e) {
    echo '<span style="color:red;">Błąd serwera! Spróbuj ponownie</span>';
    echo '<br />Informacja developerska: ' . $e;
}
?>
<html>
    <head>
        <meta charset="utf-8" />
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
    </head>
    <body>
        <div id="wrap">
            <?php
            require_once '../includes/header.php';
            ?>
            <?php
            //połączenie
            //$conn = mysqli_connect($host, $db_user, $db_password) or die("Nie udało sie połączyć z bazą danych MySQL " . mysql_error());
            //mysql_select_db($db_name, $conn);
            //musimy wyciągnąć z bazy informacje o ilości postów ogólnie do wyliczenia ilości stron
            //celowo nie kożystamy z SQL_CALC_FOUND_ROWS, bo zależy nam na zabezpieczeniu się przed wś****skimi 
            //użytkownikami, którzy zmodyfikują url i będą chcieli wejść na stronę jaka nie istnieje
            $query = "SELECT COUNT(*) AS all_posts FROM closed";
            $result = mysqli_query($connect, $query) or die(mysqli_error());
            $row = mysqli_fetch_array($result);
            extract($row);

            $onpage = 18; //ilość newsów na stronę
            $navnum = 5; //ilość wyświetlanych numerów stron, ze względów estetycznych niech będzie to liczba nieparzysta
            $allpages = ceil($all_posts / $onpage); //wszysttkie strony to zaokrąglony w górę iloraz wszystkich postów i ilości postów na stronę
            //sprawdzamy poprawnośc przekazanej zmiennej $_GET['page'] zwróć uwage na $_GET['page'] > $allpages
            if (!isset($_GET['page']) or $_GET['page'] > $allpages or ! is_numeric($_GET['page']) or $_GET['page'] <= 0) {
                $page = 1;
            } else {
                $page = $_GET['page'];
            }
            $limit = ($page - 1) * $onpage; //określamy od jakiego newsa będziemy pobierać informacje z bazy danych

            $query = "SELECT * FROM closed ORDER BY id DESC LIMIT $limit, $onpage";
            $result = mysqli_query($connect, $query) or die(mysqli_error());
            ?>
            <div class="row">
                <div class="col-md-4 col-md-push-4">
                    <div class="panel panel-warning">
                        <div class="panel-heading">Wcześniejsze zamówienia</div>
                        <div class="panel-body">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>Data</th>
                                    <th>Akcja</th>
                                </tr>  
<?php
while ($row = mysqli_fetch_array($result)) {
    $name = $row['name'];
    echo<<<END
	<tr>
	<td >$name</td>
	<td><a href="history.php?id=$name" class="btn btn-sm btn-default">Zobacz</a>
	</tr>
END;
}
?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
<?php
//zabezpieczenie na wypadek gdyby ilość stron okazała sie większa niż ilośc wyświetlanych numerów stron
if ($navnum > $allpages) {
    $navnum = $allpages;
}

//ten fragment może być trudny do zrozumienia
//wyliczane są tu niezbędne dane do prawidłowego zbudowania pętli
//zmienne są bardzo opisowę więc nie będę ich tłumaczyć
$forstart = $page - floor($navnum / 2);
$forend = $forstart + $navnum;

if ($forstart <= 0) {
    $forstart = 1;
}

$overend = $allpages - $forend;

if ($overend < 0) {
    $forstart = $forstart + $overend + 1;
}

//ta linijka jest ponawiana ze względu na to, że $forstart mogła ulec zmianie
$forend = $forstart + $navnum;
//w tych zmiennych przechowujemy numery poprzedniej i następnej strony
$prev = $page - 1;
$next = $page + 1;

//nie wpisujemy "sztywno" nazwy skryptu, pobieramy ja od serwera
$script_name = $_SERVER['SCRIPT_NAME'];
?>
            <div class="row">
                <div class="col-md-4 col-md-push-5">

                    <?php
                    //ten fragment z kolei odpowiada za wyślwietenie naszej nawigacji
                    echo "<nav aria-label='Page navigation'><ul class='pagination'>";
                    if ($page > 1) {
                        echo "<li><a href=\"" . $script_name . "?page=" . $prev . "\">&laquo;</a></li>";
                    }
                    if ($forstart > 1) {
                        echo "<li><a href=\"" . $script_name . "?page=1\">1</a></li>";
                    }
                    if ($forstart > 2) {
                        echo "<li>...</li>";
                    }
                    for ($forstart; $forstart < $forend; $forstart++) {
                        if ($forstart == $page) {
                            echo "<li class=\"current\">";
                        } else {
                            echo "<li>";
                        }
                        echo "<a href=\"" . $script_name . "?page=" . $forstart . "\">" . $forstart . "</a></li>";
                    }
                    if ($forstart < $allpages) {
                        echo "<li>...</li>";
                    }
                    if ($forstart - 1 < $allpages) {
                        echo "<li><a href=\"" . $script_name . "?page=" . $allpages . "\">[" . $allpages . "]</a></li>";
                    }
                    if ($page < $allpages) {
                        echo "<li><a href=\"" . $script_name . "?page=" . $next . "\">&raquo;</a></li>";
                    }
                    echo "</ul></nav><div class=\"clear\">";
                    ?>
                </div>
            </div>
    </body>
</html>