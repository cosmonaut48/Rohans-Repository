<?php
require_once "pdo.php";
session_start();
$salt = "XyZzy12*_";
$sqllogin = "SELECT * FROM users WHERE email=:em AND password=:password";
if (isset($_POST['cancel'])){
    header("Location: index.php");
    return;
}

if(isset($_POST['email'])&&isset($_POST['pass'])){
    if(strlen($_POST['pass'])<1||strlen($_POST['email'])<1){
        $_SESSION['fail'] = 'All fields are required';
        header("Location: login.php");
        return;
    }
    
    
    //success case
    $check=hash('md5',$salt.$_POST['pass']);
    $data=$pdo->prepare($sqllogin);
    $data->execute(array('em'=>$_POST['email'],':password'=>$check));
    $data=$data->fetch(PDO::FETCH_ASSOC);
    if ($data!==false){
        $_SESSION['name']=$data['name'];
        $_SESSION['user_id']=$data['user_id'];
        $_SESSION['success']="Login Success";
        header("Location: index.php");
        return;
    }else{
        $_SESSION['fail']='Email and password do not match';
        header("Location: login.php");
        return;
    }
}
?>


<!-- MVC BOUNDARY -->
<!DOCTYPE html>
<html>
    <head><title>Rohan Mars</title></head>
    <body>
        <h1>LOG IN</h1>
        <pre>
        <?php include "flash.php"; ?>
        <form method='POST'>
            <label for='email'>Email</label>
            <input type='text' name='email' id='email'>
            <label for='pass'>Password</label>
            <input type='text' name='pass' id='pass'>
            <input type='submit' onclick="return doValidate();" value='Log In'>
            <input type='submit' name='cancel' value='Cancel'>
        </form>
        </pre>

        <script type='text/javascript' src='functions.js'></script>



