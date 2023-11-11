<?php
require_once "pdo.php";
include 'utilities.php';
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

$positions = loadPos($pdo, $_GET['profile_id']);
$educations = loadEdu($pdo, $_GET['profile_id']);
?>











<!-- MVC BORDER ============================================================= -->
<!DOCTYPE html>
<head>
<?php include 'head.php'; ?>
</head>
<body>
    <h1>View data for <?=htmlentities($data['first_name'])?> <?=htmlentities($data['last_name'])?></h1>
    <p>
        <b>Name:</b> <?=htmlentities($data['first_name'])?> <?=htmlentities($data['last_name'])?> <br/><br/>
        <b>Email:</b> <?=htmlentities($data['email'])?><br/><br/>
        <b>Headline:</b> <?=htmlentities($data['headline'])?><br/><br/>
        <b>Summary:</b> <?=htmlentities($data['summary'])?><br/><br/>
        <?php
        if (sizeof($positions)!== 0){
            echo("<b>Positions:</b><br/>");
            echo("<ul>");
            foreach($positions as $var){
                echo("<li>");
                echo(htmlentities($var['year']).", ".htmlentities($var['description']));
                echo("</li>");
            }
            echo("</ul>");
        }
        if (sizeof($educations)!== 0){
            echo("<b>Educations:</b><br/>");
            echo("<ul>");
            foreach($educations as $var){
                echo("<li>");
                echo(htmlentities($var['year']).", ".htmlentities(getSchoolNameFromId($pdo,$var['institution_id'])));
                echo("</li>");
            }
            echo("</ul>");
        }
        ?>
    </p>
    <a href='index.php'>Go back</a>
</body>
</html>