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

if (isset($_POST['make'])||isset($_POST['year'])||isset($_POST['mileage'])){
	if (strlen($_POST['make']<1)||strlen($_POST['model']<1)){ //securitycheck1
		$_SESSION['fail']='Make and model are required';
		header("Location: edit.php?autos_id=".$_GET['autos_id']);
		return;
	}
	if(is_numeric($_POST['year'])===false || is_numeric($_POST['mileage']===false)){ //securitycheck2
		$_SESSION['fail'] = 'Mileage and year must be numeric';
		header("Location: edit.php?autos_id=".$_GET['autos_id']);
		return;
	}
	$sqlupdate = 'UPDATE autos SET make=:make,model=:model,year=:year,mileage=:mileage WHERE autos_id=:autos_id';
	$update=$pdo->prepare($sqlupdate);
	$placeholdersub = array(':make'=>$_POST['make'],':model'=>$_POST['model'],':year'=>$_POST['year'],':mileage'=>$_POST['mileage'],':autos_id'=>$_POST['autos_id']);
	$update->execute($placeholdersub);
	$_SESSION['success'] = "Record updated.";
	header("Location: view.php");
	return;
}


$sqlselect = 'SELECT * FROM autos WHERE autos_id=?';
$data=$pdo->prepare($sqlselect);
$data->execute(array($_GET['autos_id']));
?>

<!DOCTYPE html>
<html>
<head><title>CRUD - EDIT</title></head>
<body>
<?php
if (isset($_SESSION['success'])){
	echo("<p style='color:green'>".$_SESSION['success']."</p>");
	unset($_SESSION['success']);
}
if (isset($_SESSION['fail'])){
	echo("<p style='color:red'>".$_SESSION['fail']."</p>");
	unset($_SESSION['fail']);
}
$row=$data->fetch(PDO::FETCH_ASSOC);
if($row===false){
}else{
  $make=htmlentities($row['make']);
  $model=htmlentities($row['model']);
  $year=htmlentities($row['year']);
  $mileage=htmlentities($row['mileage']);
  $autos_id = $row['autos_id'];
}
?>
  <form method='POST'>
    <label for='make'>Make:</label>
    <input type='text' name='make' id='make' value='<?=$make?>'>
    <label for='model'>Model:</label>
    <input type='text' name='model' id='model' value='<?=$model?>'>
    <label for='year'>Year:</label>
    <input type='text' name='year' id='year' value='<?=$year?>'>
    <label for='mileage'>Mileage:</label>
    <input type='text' name='mileage' id='mileage' value='<?= $mileage ?>'>
    <input type = 'hidden' name='autos_id' id='autos_id' value='<?=$autos_id?>'>
    <input type='submit' value='Save'>
    <input type='submit' name='cancel' value='Cancel'>
  </form>
</body>
</html>
