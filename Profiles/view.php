<?php
require_once "pdo.php";
session_start();

if(!isset($_GET['profile_id'])){
    $_SESSION['fail']='Bad Profile ID';
    header("Location: index.php");
    return;
}

$query = "SELECT * FROM profile WHERE profile_id=:pid";
$stmt = $pdo->prepare($query);
$stmt->execute(array(':pid'=>$_GET['profile_id']));
$data=$stmt->fetch(PDO::FETCH_ASSOC);
?>











<!-- MVC BORDER ============================================================= -->
<!DOCTYPE html>
<head>
    <title>
        Rohan Mars
    </title>
</head>
<body>
    <h1>View data for <?=htmlentities($data['first_name'])?> <?=htmlentities($data['last_name'])?></h1>
    <table border=2>
        <tr>
            <td>First Name</td>
            <td>Last Name</td>
            <td>Email</td>
            <td>Headline</td>
            <td>Summary</td>
        </tr>
        <tr>
            <td><?=htmlentities($data['first_name'])?></td>
            <td><?=htmlentities($data['last_name'])?></td>
            <td><?=htmlentities($data['email'])?></td>
            <td><?=htmlentities($data['headline'])?></td>
            <td><?=htmlentities($data['summary'])?></td>
        </tr>
    </table>
    <a href='index.php'>Go back</a>
</body>
</html>