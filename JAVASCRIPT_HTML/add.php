<?php
require_once 'pdo.php';
session_start();


if(!isset($_SESSION['name'])||!isset($_SESSION['user_id'])){
    die("ACCESS DENIED");
}

if(isset($_POST['cancel'])){
    header("Location: index.php");
    return;
}

if(isset($_POST['first_name'])&&isset($_POST['last_name'])&&isset($_POST['email'])&&isset($_POST['headline'])&&isset($_POST['summary'])){
    //for form preservation
    $_SESSION['add_fname'] =  $_POST['first_name'];
    $_SESSION['add_lname'] = $_POST['last_name'];
    $_SESSION['add_em'] = $_POST['email'];
    $_SESSION['add_hl'] = $_POST['headline'];
    $_SESSION['add_sum'] = $_POST['summary'];
    //for post validation (submit does not send as post thankfully)
    foreach($_POST as $item){
        if(strlen($item)<1){
            $_SESSION['fail'] = 'All fields are required';
            header("Location: add.php");
            return;
        }
    }
    //email validation
    if (strpos($_POST['email'],'@')<1){
        $_SESSION['fail']='Email must contain the at-sign (@)';
        header("Location: add.php");
        return;
    }
    $sqladd = "INSERT INTO profile(user_id,first_name,last_name,email,headline,summary) VALUES(:uid,:fn,:ln,:em,:hl,:sm);";
    $addstmt = $pdo->prepare($sqladd);
    $addstmt->execute(array(':uid'=>$_SESSION['user_id'],
                            ':fn'=>$_POST['first_name'],
                            ':ln'=>$_POST['last_name'],
                            ':em'=>$_POST['email'],
                            ':hl'=>$_POST['headline'],
                            ':sm'=>$_POST['summary']));
    $_SESSION['success'] = 'Record added';
    unset($_SESSION['add_fname']);unset($_SESSION['add_lname']);unset($_SESSION['add_em']);unset($_SESSION['add_hl']);unset($_SESSION['add_sum']);
    header("Location: index.php");
    return;

}

function preserveFormData($var){
    if(isset($_SESSION["$var"])){
        return htmlentities($_SESSION["$var"]);
    }else{
        return null;
    }
}
?>



<!-- MVC BORDER. NO INPUT ABOVE ================================================================================================================================================ -->
<!DOCTYPE html>
<html>
    <head><title>Rohan Mars</title></head>
    <body>
        <h1 style='color:#aa66ff'>Add a profile</h1>
        <?php include "flash.php"; ?>
        <pre>
            <form method="POST">
                <label for="first_name">First Name</label>
                <input type='text' name='first_name' id='first_name' value='<?= preserveFormData('add_fname'); ?>'>
                <label for="last_name">Last Name</label>
                <input type='text' name='last_name' id='last_name' value='<?= preserveFormData('add_lname'); ?>'>
                <label for="em">Email</label>
                <input type='text' name='email' id='email' value='<?= preserveFormData('add_em'); ?>'>
                <label for="headline">Headline</label>
                <input type='text' name='headline' id='headline' value='<?= preserveFormData('add_hl'); ?>'>
                <label for="summary">Summary</label>
                <input type='textarea' rows='10' cols='40' name='summary' id='summary' value='<?= preserveFormData('add_sum'); ?>'>
                <input type='submit' value='Add'>
                <input type='submit' name='cancel' id='cancel' value='Cancel'>
            </form>
        </pre>
                