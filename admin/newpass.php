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

$login = $_POST['login'];
$pass = $_POST['pass'];
$pass2 = $_POST['pass2'];

if (empty($login)) {
    $_SESSION['e_login'] = "Nieprawidłowa nazwa użytkownika!";
    header("Location:pass.php");
    exit();
} else {
    if (empty($pass)) {
        $_SESSION['e_pass'] = "Podaj poprawne hasło!";
        header("Location:pass.php");
        exit();
    } else {
        if ($pass !== $pass2) {
            $_SESSION['e_pass'] = "Podane hasła nie są identyczne!";
            header("Location:pass.php");
            exit();
        } else {
            $connect = new mysqli($host, $db_user, $db_password, $db_name);
            $result=$connect->query("SELECT login FROM users WHERE login = '$login'");
            $ileuser=$result->num_rows;
            if ($ileuser < 0) {
                $_SESSION['e_login'] = "Nieprawidłowa nazwa użytkownika!";
                header("Location:pass.php");
                exit();
            } else 
			if ($login =='superadmin'){
			$_SESSION['e_pass'] = "Odmowa dostępu!";
			header("Location:pass.php");
			exit();
			}else{
			
               
                $shapass = sha1($pass);
                $connect->query("UPDATE users SET password = '$shapass' WHERE login = '$login'");
                $_SESSION['e_uppas'] = "Pomyślnie zaktualizowano hasło!";
                header("Location:pass.php");
                exit();
            }
        }
    }
}










//$connect->query("UPDATE `users` SET `password` = $newpassword WHERE `workers`.`login` = $login");
