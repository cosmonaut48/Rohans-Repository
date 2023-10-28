<?php
session_start();
require_once "pdo.php";

if (!isset($_SESSION['name'])){
	die("ACCESS DENIED");
}


$stmt=$pdo->query("SELECT * FROM autos");
?>

<html>
<head><title>CRUD - VIEW</title></head>
<body>
<p style="text-strength:bold color:blue"> THE CARS:</p>
<?php
if (isset($_SESSION['success'])){
	echo("<p style='color:green'>".$_SESSION['success']."</p>");
	unset($_SESSION['success']);
}
if (isset($_SESSION['fail'])){
	echo("<p style='color:red'>".$_SESSION['fail']."</p>");
	unset($_SESSION['fail']);
}
$row=$stmt->fetch(PDO::FETCH_ASSOC);
if($row===false){
  echo("<pre>");
  echo("<p style='color:red'>No data could be located<p>");
}else{
	echo("<table border=1>"."\n");
	echo("<tr><td>YEAR</td><td>MAKE</td><td>MODEL</td><td>MILEAGE</td><td>ACTION</td></tr>");
	$auto = $row['autos_id'];
	echo("<tr><td>");
	echo(htmlentities($row['year']));
	echo("</td><td>");
	echo(htmlentities($row['make']));
	echo("</td><td>");
	echo(htmlentities($row['model']));
	echo("</td><td>");
	echo(htmlentities($row['mileage']));
	echo("</td><td>");
	echo("<a href='edit.php?autos_id=".$row['autos_id']."''>EDIT</a> / ");
	echo("<a href='delete.php?autos_id=".$row['autos_id']."''>DELETE</a>");
	echo("</td></tr>");
	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		$auto = $row['autos_id'];
		echo("<tr><td>");
		echo(htmlentities($row['year']));
		echo("</td><td>");
		echo(htmlentities($row['make']));
		echo("</td><td>");
		echo(htmlentities($row['model']));
		echo("</td><td>");
		echo(htmlentities($row['mileage']));
		echo("</td><td>");
		echo("<a href='edit.php?autos_id=".$row['autos_id']."''>EDIT</a> / ");
		echo("<a href='delete.php?autos_id=".$row['autos_id']."''>DELETE</a>");
		echo("</td></tr>");
	}
}
echo("</table>");
?>
<p><a href="add.php">Add New Entry</a>||<a href="logout.php">Logout</a>
</body>
</html>
