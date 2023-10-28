<?php
//requiring a get token 
if(!isset($_GET['name']) || strlen($_GET['name'])<1){
	die("Name parameter not set.");
}

//logout functionality
if(isset($_POST['logout'])){
	header("Location: index.php");
	return;
}

$rps=array('Rock','Paper','Scissors');
$computer = rand(0,2);
$human = isset($_POST["human"]) ? $_POST['human']+0 : -1;

function check($computer,$human){
	if ($computer==0){
		if ($human==0){
			return 'Tie.';
		}elseif ($human==1){
			return 'You win!';
		}elseif ($human==2){
			return 'You lose.';
		}
	}elseif ($computer==1){
		if ($human==0){
			return 'You lose.';
		}elseif ($human==1){
			return 'Tie.';
		}elseif ($human==2){
			return 'You win!';
		}
	}elseif ($computer==2){
		if ($human==0){
			return 'You win!';
		}elseif ($human==1){
			return 'You lose.';
		}elseif ($human==2){
			return 'Tie.';
		}
	}else{
		return false;
	}
}

$result=check($computer,$human);
?>

<!DOCTYPE html>
<html>
<head>
<title>R MARS bc70f970</title>
</head>
<body>
<h1>Rock Paper Scissors 3: 'Best 5 out of 7!'</h1>
<?php
if (isset($_GET['name'])){
	echo ("<p>Welcome: ".htmlentities($_GET['name']));
}
?>
<form method="post">
<select name="human">
<option value="-1">Select</option>
<option value="0">Rock</option>
<option value="1">Paper</option>
<option value="2">Scissors</option>
<option value="3">Test</option>
</select>
<input type="submit" value="Play">
<input type="submit" name="logout" value="Logout">
</form>

<pre>
<?php
if ($human == -1){
    echo "Please select a throw and press 'Play'";
}elseif ($human == 3){
    for($c=0;$c<3;$c++) {
        for($h=0;$h<3;$h++) {
            $r = check($c, $h);
            print "human:$rps[$h] computer:$rps[$c] WIN??:$r\n";
        }
    }
}else{
    print "Your Play=$rps[$human] Computer Play=$rps[$computer] Result=$result\n";
}
?>
</pre>