<?php

session_start();
//zapisanie hasła do sesji
$login = $_POST['login'];
$password = $_POST['password'];
//sprawdzanie podanych znakow
$login = htmlentities($login, ENT_QUOTES, "UTF-8");
$password = htmlentities($password, ENT_QUOTES, "UTF-8");
//kodowanie hasla
$shapassword = sha1($password);
require_once 'includes/connect.php';

try {
    $connect = new mysqli($host,$db_user,$db_password,$db_name);
    if ($connect->connect_errno != 0) {
        throw new Exception(mysqli_connect_errno());
    } else {
        if ($result = $connect->query(sprintf("SELECT * FROM users WHERE login='%s' AND password='%s'", mysqli_real_escape_string($connect, $login), mysqli_real_escape_string($connect, $shapassword)))) 
         {
            $users = $result->num_rows;
            if ($users > 0) {
                $_SESSION['loging'] = true;
                unset($_SESSION['error']);
                $result->free_result();
                $result = mysqli_query($connect, "SELECT * FROM users WHERE login='$login'");
                $row = mysqli_fetch_assoc($result);
                $permission = $row['perm'];
                $_SESSION['perm'] = $permission;
                header('Location:home.php');
            } else {

                $_SESSION['error'] = '<div class="alert alert-danger" role="alert">Nieprawidłowy login lub hasło!</div>';
                header('Location: index.php');
                exit();
            }
        }
    }
} catch (Exception $ex) {
    
}

