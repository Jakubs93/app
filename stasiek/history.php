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
	try
	{
		$connect = new mysqli($host, $db_user, $db_password, $db_name);
			if ($connect->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());					
			}
			else
			{
				$Name= $_GET ['id'];
				//mysqli_select_db($connect, $db_name);
				$result = mysqli_query($connect, "SELECT * FROM `$Name` ORDER BY id DESC");
			}
	}
	catch(Exception $e)
	{
		echo '<span style="color:red;">Błąd serwera! Spróbuj ponownie</span>';
		echo '<br />Informacja developerska: '.$e;
	}	
?>
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html>
<head>
	<meta charset="utf-8" />
	<meta name="robots" content="NOFOLLOW,NOINDEX" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
	require_once "../includes/header_stasiek.php";
	?>
<div class="row">
<div class="col-md-7">
<div class="panel panel-success">
 <div class="panel-heading">Wyjazdy z dnia <b><?php echo $Name;?></b></div>

 	<div class="panel-body">
		<table class="table table-bordered table-striped">
			<tr>
				<th>#</th>
				<th>Adres</th>
				<th>Kwota</th>
				<th>Typ płatności</th>
				<th>Numer kierowcy</th>
			</tr>
<?php
$ile = mysqli_num_rows( $result);
for ($i = 1; $i <= $ile; $i++) 
{		
		$row = mysqli_fetch_assoc($result);
		$id=$row['id'];
		$adres=$row['adres'];
		$kwota=$row['kwota'];
		$typ_plat=$row['typ_plat'];
		$kierowca=$row['kierowca'];
echo<<<END
	<tr>
	<td >$id</td>
	<td >$adres</td>
	<td >$kwota zł</td>
	<td >$typ_plat</td>
	<td >$kierowca</td>
	</tr>
END;
}
mysqli_free_result($result);
?>
</table>
</div>
</div>
</div>
<div class="col-md-5">
<div class="panel panel-info">
 <div class="panel-heading">Stan kasy</div>
 	<div class="panel-body">
	<dl class="dl-horizontal">
<?php
$query = "SELECT SUM(kwota) AS suma1 FROM `$Name` WHERE typ_plat!='Karta' AND typ_plat!='BON' AND kierowca='kierowca 1'";
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_array($result);
$suma1= $row['suma1'];
    if(empty($suma1))
    {
		$_SESSION['nosum']="0.00";
	}
echo "<dt>$suma1";if (isset($_SESSION['nosum'])){    echo $_SESSION['nosum'];unset($_SESSION['nosum']);} echo " zł</dt><dd>Gotówka kierowcy 1</dd>";
mysqli_free_result($result);
?>

<?php
$query = "SELECT SUM(kwota) AS suma2 FROM `$Name` WHERE typ_plat!='Karta' AND typ_plat!='BON'  AND kierowca='kierowca 2'";
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_array($result);
$suma2= $row['suma2'];
	if(empty($suma2))
	{
		$_SESSION['nosum']="0.00";
	}
echo "<dt>$suma2";if (isset($_SESSION['nosum'])){	echo $_SESSION['nosum'];unset($_SESSION['nosum']);} echo " zł</dt><dd>Gotówka kierowcy 2</dd>";
mysqli_free_result($result);
?>


<?php
$query = "SELECT SUM(kwota) AS suma3 FROM `$Name` WHERE typ_plat!='Karta' AND typ_plat!='BON' AND kierowca='kierowca 3'";
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_array($result);
$suma3= $row['suma3'];
	if(empty($suma3))
	{
		$_SESSION['nosum']="0.00";
	}
echo "<dt>$suma3";if (isset($_SESSION['nosum'])){	echo $_SESSION['nosum'];unset($_SESSION['nosum']);} echo " zł</dt><dd>Gotówka kierowcy 3</dd>";
mysqli_free_result($result);
?>

<?php
$query = "SELECT SUM(kwota) AS suma4 FROM `$Name` WHERE typ_plat!='Karta' AND typ_plat!='BON' AND kierowca='kucharz'";
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_array($result);
$suma4= $row['suma4'];
	if(empty($suma4))
	{
		$_SESSION['nosum']="0.00";
	}
echo "<dt>$suma4";if (isset($_SESSION['nosum'])){	echo $_SESSION['nosum'];unset($_SESSION['nosum']);} echo " zł</dt><dd>Gotówka kucharza</dd>";
mysqli_free_result($result);
?>
</br>
<?php
$query = "SELECT SUM(kwota) AS suma5 FROM `$Name` WHERE typ_plat!='Karta' AND typ_plat!='BON' AND kwota < '0'";
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_array($result);
$suma5= $row['suma5'];
    if(empty($suma5))
	{
		$_SESSION['nosum']="0.00";
	}
echo "<dt><span style='color:red'>$suma5";if (isset($_SESSION['nosum'])){	echo $_SESSION['nosum'];unset($_SESSION['nosum']);} echo " zł</span></dt><dd>Wydatki</dd> ";
mysqli_free_result($result);
?>
<?php
$query = "SELECT SUM(kwota) AS suma6 FROM `$Name` WHERE typ_plat!='Karta' AND typ_plat!='BON'";
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_array($result);
$suma6= $row['suma6'];
	if(empty($suma6))
	{
		$_SESSION['nosum']="0.00";
	}
echo "<dt>$suma6";if (isset($_SESSION['nosum'])){	echo $_SESSION['nosum'];unset($_SESSION['nosum']);} echo " zł</dt><dd>Całkowita ilość gotówki</dd> ";
mysqli_free_result($result);
?>

<?php
$query = "SELECT SUM(kwota) AS suma7 FROM `$Name` WHERE typ_plat='Karta'";
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_array($result);
$suma7= $row['suma7'];
	if(empty($suma7))
	{
		$_SESSION['nosum']="0.00";
	}
echo "<dt>$suma7";if (isset($_SESSION['nosum'])){	echo $_SESSION['nosum'];unset($_SESSION['nosum']);} echo " zł</dt><dd>Płatność kartą</dd> ";
mysqli_free_result($result);
?>

<?php
$query = "SELECT SUM(kwota) AS suma8 FROM `$Name` WHERE typ_plat='BON'";
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_array($result);
$suma8= $row['suma8'];
	if(empty($suma8))
	{
		$_SESSION['nosum']="0.00";
	}
echo "<dt>$suma8";if (isset($_SESSION['nosum'])){	echo $_SESSION['nosum'];unset($_SESSION['nosum']);} echo " zł</dt><dd>Płatność DOTPAY</dd> ";
mysqli_free_result($result);
?>
</br>
<?php
$query = "SELECT SUM(kwota) AS suma9 FROM `$Name` WHERE kwota > '0'";
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_array($result);
$suma9= $row['suma9'];
	if(empty($suma9))
	{
		$_SESSION['nosum']="0.00";
	}
echo "<dt>$suma9";if (isset($_SESSION['nosum'])){	echo $_SESSION['nosum'];unset($_SESSION['nosum']);} echo " zł</dt><dd>Stan kasy</dd> ";
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