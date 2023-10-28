<?php
session_start();
require_once "pdo.php";

if(!isset($_GET['profile_id'])){
    $_SESSION['fail']='Bad Profile ID';
    header("Location: index.php");
    return;
}

if(isset($_POST['cancel'])){
    header("Location: index.php");
    return;
}

if (isset($_POST['first_name'])&&isset($_POST['last_name'])&&isset($_POST['email'])&&isset($_POST['headline'])&&isset($_POST['summary'])){
    foreach($_POST as $var){
        if(strlen($var)<1){
            $_SESSION['fail']='All forms are required';
            header("Location: edit.php?profile_id=".$_GET['profile_id']);
            return;
        }elseif(strpos($_POST['email'],'@')<1){
            $_SESSION['fail']='Email must contain an at-sign (@)';
            header("Location: edit.php?profile_id=".$_GET['profile_id']);
            return;
        }
    }

	$sqlupdate = 'UPDATE profile SET first_name=:first_name,last_name=:last_name,email=:email,headline=:headline,summary=:summary WHERE profile_id=:pid';
	$update=$pdo->prepare($sqlupdate);
	$placeholdersub = array(':first_name'=>$_POST['first_name'],':last_name'=>$_POST['last_name'],':email'=>$_POST['email'],':headline'=>$_POST['headline'],':summary'=>$_POST['summary'],':pid'=>$_POST['pid']);
	$update->execute($placeholdersub);
	$_SESSION['success'] = "Record updated.";
	header("Location: index.php");
	return;
}

$queryview = "SELECT * FROM profile WHERE profile_id=:pid";
$stmtview=$pdo->prepare($queryview);
$stmtview->execute(array(':pid'=>$_GET['profile_id']));
$data=$stmtview->fetch(PDO::FETCH_ASSOC);

if($data===false){
    $_SESSION['fail'] = 'No data for selected profile';
    header("Location: index.php");
    return;
}elseif($data['user_id']!==$_SESSION['user_id']){
    $_SESSION['fail'] = 'Access denied; false user';
    header("Location: index.php");
    return;
}
?>

<!-- MVC BORDER ==================================================-->
<!DOCTYPE html>
<html><head><title>Rohan Mars</title></head>
<body>
    <h1> Edit record for <?=htmlentities($data['first_name'])?> <?=htmlentities($data['last_name'])?></h1>
    <?php include "flash.php"; ?>
    <?php
    if($data===false){
    }else{
      $first_name=htmlentities($data['first_name']);
      $last_name=htmlentities($data['last_name']);
      $email=htmlentities($data['email']);
      $headline=htmlentities($data['headline']);
      $summary=htmlentities($data['summary']);
      $pid=htmlentities($_GET['profile_id']);
    }
    ?>
    <form method='POST'>
        <label for='first_name'>First Name:</label>
        <input type='text' name='first_name' id='first_name' value='<?=$first_name?>'>
        <label for='last_name'>Last Name:</label>
        <input type='text' name='last_name' id='last_name' value='<?=$last_name?>'>
        <label for='email'>Email:</label>
        <input type='text' name='email' id='email' value='<?=$email?>'>
        <label for='headline'>Headline:</label>
        <input type='text' name='headline' id='headline' value='<?= $headline ?>'>
        <label for='summary'>Summary:</label>
        <input type='text' name='summary' id='summary' value='<?= $summary ?>'>
        <input type = 'hidden' name='pid' id='pid' value='<?=$pid?>'>
        <input type='submit' value='Save'>
        <input type='submit' name='cancel' value='Cancel'>
    </form>
</body>
</html>
