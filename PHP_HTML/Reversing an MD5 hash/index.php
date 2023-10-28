<!DOCTYPE html>
<html>
<head>
<title>Reversing an MD5 Hash for HACKING</title>
</head>
<body>
<h1>Hackerman</h1>
<p>Here we will take an input and check the hash of all possible other combinations until we crack it</p>
<form>
<p><label for="md5">Input Hash</label>
<input type="text" name="md5" size="40"></p>
<input type ='submit'/>
</form>


<?php
$result = 'Not found';
$prints = 0;
$correct="Not found";
if (isset($_GET['md5'])){
	$time_pre = microtime(true);
	echo nl2br("Checking combinations for hash key ".$_GET['md5']."\n");
	for($d1=0;$d1<=9;$d1++){
		for($d2=0;$d2<=9;$d2++){
			for($d3=0;$d3<=9;$d3++){
				for($d4=0;$d4<=9;$d4++){
					$code=$d1.$d2.$d3.$d4;
					$hash=hash('md5',$code);
					if ($prints<=15) {
						$prints++;
						echo nl2br("$code ||| "."$hash"."\r\n");
					}
					if ($hash==$_GET["md5"]){
						$correct=$hash;
						$result=$code;
						$d1=10;
						$d2=10;
						$d3=10;
						$d4=10;
					}
				}
			}
		}
	}
$time_post = microtime(true);
$elapse = $time_post-$time_pre;
echo nl2br("PIN FOUND AFTER $elapse SECONDS\n");
echo("PIN: $result from hash $correct");
}