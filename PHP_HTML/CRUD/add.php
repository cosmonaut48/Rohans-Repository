<?php
session_start();
require_once "pdo.php";

if (!isset($_SESSION['name'])){
	die("ACCESS DENIED");
}

if (isset($_POST['goback'])){
	header("Location: view.php");
	return;
}

//DB add functionality
if (isset($_POST['make'])||isset($_POST['year'])||isset($_POST['mileage'])){
	if (strlen($_POST['make']<1)||strlen($_POST['model']<1)||strlen($_POST['year']<1)||strlen($_POST['mileage']<1)){ //securitycheck1
		$_SESSION['fail']='All fields are required';
		header("Location: add.php");
		return;
	}
	if(is_numeric($_POST['year'])===false || is_numeric($_POST['mileage']===false)){ //securitycheck2
		$_SESSION['fail'] = 'Mileage and year must be numeric';
		header("Location: add.php");
		return;
	}
	$sql = 'INSERT INTO autos(make,model,year,mileage) VALUES (?,?,?,?)';
	$stmt=$pdo->prepare($sql);
	$placeholdersub = array($_POST['make'],$_POST['model'],$_POST['year'],$_POST['mileage']);
	$stmt->execute($placeholdersub);
	$_SESSION['success'] = "Vehicle added.";
	header("Location: view.php");
	return;
}

?>


<!DOCTYPE html>
<html>
<head><title>CRUD - ADD</title></head>
<body>
<h1>ADD A CAR HERE</h1>
<?php
if (isset($_SESSION['fail'])){
	echo("<p style='color:red'>".$_SESSION['fail']."</p>");
	unset($_SESSION['fail']);
}
?>
<p>
<form method='POST'>
<label for='make'>Make:</label>
<input type='text' id='make' name='make' size=40><br/>
<label for='make'>Model:</label>
<input type='text' id='model' name='model' size=40><br/>
<label for='year'>Year:</label>
<input type='text' name='year' id='year' size=40><br/>
<label for='mileage'>Mileage</label>
<input type='text' name='mileage' id = 'mileage'><br/>
<input type="submit" value="Add Car">
<input type="submit" name="goback" value="Go Back">
</form>
</p>
</body>
</html>
