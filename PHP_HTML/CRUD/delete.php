<?php
session_start();
require_once "pdo.php";

if (!isset($_SESSION['name'])){
  die("ACCESS DENIED");
}

if(!isset($_GET['autos_id'])){
  $_SESSION['fail']="Bad data for autos_id";
  header("Location: view.php");
  return;
}

if(isset($_POST['cancel'])){
  header("Location: view.php");
  return;
}

$sqlselect = 'SELECT * FROM autos WHERE autos_id=?';
$data=$pdo->prepare($sqlselect);
$data->execute(array($_GET['autos_id']));
$data=$data->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])){
  $sqldelete="DELETE FROM autos WHERE autos_id=:autos_id";
  $stmt=$pdo->prepare($sqldelete);
  $stmt->execute(array(':autos_id'=>$_GET['autos_id']));
  $_SESSION['success']='Record deleted.';
  header("Location: view.php");
  return;
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>CRUD - DELETE</title>
</head>
<body>
  <h1>Deleting the <?= htmlentities($data['make']); ?> <?= htmlentities($data['model']);?> </h1>
  <form method="POST">
    <input type='hidden' name='autos_id' value='<?=$_GET['autos_id']?>'>
    <input type='submit' name='submit' value='Delete'>
    <input type='submit' name='cancel' value='Cancel'>
  </form>
</body>
</html>
