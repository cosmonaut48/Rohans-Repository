<?php
//redirect to homepage when cancelled
if (isset($_POST['cancel'])){
    header("Location: index.php");
    return;
}
//setting password parameters
$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';
//default fail cannot show up therefore:
$failmessage = false;
//check for errors
if (isset($_POST['who']) && isset($_POST['pass'])){
	if (strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1){ //un/pw given but 1/2 are empty
		$failmessage = 'Username and password are required.';
	} else{
		$check = hash('md5',$salt.$_POST['pass']);
		if ($check === $stored_hash){
			//redirect due to entry granted
			header("Location: game.php?name=".urlencode($_POST['who']));
			return;
		} else{
			$failmessage = 'Incorrect password.';
		}
	}
}

//context complete; code now
?>
<!DOCTYPE html>
<html>
<head>
<title>R MARS bc70f970</title>
</head>
<h1>Please log in:</h1>
<?php
if ($failmessage !== false){
    echo('<p style="color: red;">'.htmlentities($failmessage)."</p>\n");
}
?>
<form method='post'>
<p><label for="who">Username:</label>
<input type="text" size=40 name="who" id="who"><br/>
<label for="pass">Password:</label>
<input type="password" size = 40 name="pass" id="pass"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</p></form>
</body></html>

