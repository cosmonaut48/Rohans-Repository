<?php
//establishing a session
session_start();

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
// if password is POSTed in, set a hash-variable to check it
if (isset($_POST['pass'])){
	$check = hash('md5',$salt.$_POST['pass']);
}

//check for input errors

if (isset($_POST['email']) && isset($_POST['pass'])){ //if both set: validate
	if (strlen($_POST['email'])<1 || strlen($_POST['pass'])<1){
		$_SESSION['fail'] = "Both fields are required for log in.";
		error_log("Login fail ".$_POST['who']." $check");
		header("Location: login.php");
		return;
	}elseif (strpos($_POST['email'],'@')<1){
		$_SESSION['fail']="An at sign (@) is required in the email";
		error_log("Login fail ".$_POST['email']." $check");
		header("Location: login.php");
		return;
	}elseif($stored_hash!==$check){
		$_SESSION['fail'] = "Incorrect Password.";
		error_log("Login fail ".$_POST['email']." $check");
		header("Location: login.php");
		return;
	}else{
		$_SESSION['success'] = "Log in successful";
		$_SESSION['name'] = $_POST['email'];
		error_log("Login success ".$_POST['email']);
		header("Location: view.php");
		return;
	}
}
//context complete; code now
?>
<!DOCTYPE html>
<html>
<head><title>CRUDLOCK</title></head>
<h1>Please log in:</h1>
<?php
if (isset($_SESSION['fail'])){
	echo ("<p style='color:red'>".$_SESSION['fail']."</p>");
	unset($_SESSION['fail']);
}
?>
<form method='post'>
<p><label for="email">Username:</label>
<input type="text" size=40 name="email" id="email"><br/>
<label for="pass">Password:</label>
<input type="password" size = 40 name="pass" id="pass"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</p></form>
</body></html>
