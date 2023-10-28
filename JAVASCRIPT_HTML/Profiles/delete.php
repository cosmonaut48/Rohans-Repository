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

if (isset($_POST['profile_id'])){
    $querydel = "DELETE FROM profile WHERE profile_id=:pid";
    $stmtdel=$pdo->prepare($querydel);
    $stmtdel->execute(array(':pid'=>$_POST['profile_id']));
    $_SESSION['success']='Profile deleted';
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

<!-- MVC BORDER ===================================================================== -->
<!DOCTYPE html>
<html>
    <head>
        <title>
            Rohan Mars
        </title>
    </head>
    <body>
        <h1>Confirm Delete for <?=htmlentities($data['first_name']);?> <?= htmlentities($data['last_name']);?></h1>
        <form method='POST'>
            <input type='hidden' name = 'profile_id' id = 'profile_id' value='<?=htmlentities($_GET['profile_id']);?>'>
            <input type = 'submit' value='Delete'>
            <input type = 'submit' name = 'cancel' id = 'cancel' value = 'Cancel'>
        </form>
</body>
</html>