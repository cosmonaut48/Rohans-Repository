<?php
session_start();
require_once "pdo.php";
include 'utilities.php';

if(!isset($_GET['profile_id'])){
    $_SESSION['fail']='Bad Profile ID';
    header("Location: index.php");
    return;
}

if(isset($_POST['cancel'])){
    header("Location: index.php");
    return;
}

if (isset($_POST['first_name'])&&isset($_POST['last_name'])&&isset($_POST['email'])&&isset($_POST['headline'])&&isset($_POST['summary'])){
    foreach($_POST as $var){
        if(strlen($var)<1){
            $_SESSION['fail']='All forms are required';
            header("Location: edit.php?profile_id=".$_GET['profile_id']);
            return;
        }elseif(strpos($_POST['email'],'@')<1){
            $_SESSION['fail']='Email must contain an at-sign (@)';
            header("Location: edit.php?profile_id=".$_GET['profile_id']);
            return;
        }
    }
    if (!is_bool(validatePos())){
        $_SESSION['fail']=validatePos();
        header("Location: edit.php?profile_id=".$_GET['profile_id']);
    }

    if (!is_bool(validateEdu())){
        $_SESSION['fail']=validateEdu();
        header("Location: edit.php?profile_id=".$_GET['profile_id']);
    }

    //update the name, headline, summary etc.
    $sqlupdate = 'UPDATE profile SET first_name=:first_name,last_name=:last_name,email=:email,headline=:headline,summary=:summary WHERE profile_id=:pid';
	$update=$pdo->prepare($sqlupdate);
	$placeholdersub = array(':first_name'=>$_POST['first_name'],':last_name'=>$_POST['last_name'],':email'=>$_POST['email'],':headline'=>$_POST['headline'],':summary'=>$_POST['summary'],':pid'=>$_POST['pid']);
	$update->execute($placeholdersub);

    // Clear out the old positions entries
    $stmt = $pdo->prepare('DELETE FROM Positions
        WHERE profile_id=:pid');
    $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
    
    //Clear out the old educations entries
    $statement = $pdo->prepare('DELETE FROM education WHERE profile_id=:pid');
    $statement->execute(array(':pid' => $_REQUEST['profile_id']));

    // Insert the positions entries
    $rank = 1;
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

        $stmt = $pdo->prepare('INSERT INTO Positions
            (profile_id, rank, year, description)
        VALUES ( :pid, :rank, :year, :desc)');
        $stmt->execute(array(
            ':pid' => $_REQUEST['profile_id'],
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc)
        );
        $rank++;
    }
    // Insert the education entries
    $rank=1;
    for($i=1; $i<=9; $i++){
        if ( !isset($_POST['year_s'.$i]) && !isset($_POST['school'.$i])){
            continue;
        }
        $year_s = $_POST['year_s'.$i];
        $school = $_POST['school'.$i];

        $stmt_preserve = $pdo->prepare('SELECT institution_id FROM institution where name=:name');
        $stmt_preserve->execute(array(':name'=>$school));
        $iid = $stmt_preserve->fetch(PDO::FETCH_ASSOC);
        if($iid===false){
            $stmt1= $pdo->prepare('INSERT INTO institution (name) VALUES (:school)');
            $stmt1->execute(array(':school'=>$school));
            $school_id = $pdo->lastInsertId();
        }else{
            $school_id=$iid['institution_id'];
        }

        $stmt2= $pdo->prepare('INSERT INTO education (profile_id, institution_id, rank, year) VALUES (:pid, :iid, :rank, :year)');
        $stmt2->execute(array(':pid'=>$_REQUEST['profile_id'], ':iid'=>$school_id, ':rank'=>$rank, ':year'=>$year_s));

        $rank++;
    } 
    //success case
    $_SESSION['success'] = 'Record updated';
    header("Location: index.php");
    return;
}
//retrieval case
$queryview = "SELECT * FROM profile WHERE profile_id=:pid";
$stmtview=$pdo->prepare($queryview);
$stmtview->execute(array(':pid'=>$_REQUEST['profile_id']));
$data=$stmtview->fetch(PDO::FETCH_ASSOC);

if($data===false){
    $_SESSION['fail'] = 'No data for selected profile';
    header("Location: index.php");
    return;
}elseif($data['user_id']!==$_SESSION['user_id']){
    $_SESSION['fail'] = 'Access denied; false user';
    header("Location: index.php");
    return;
}

$positions = loadPos($pdo, $_GET['profile_id']);
$educations = loadEdu($pdo, $_GET['profile_id']);


?>

<!-- MVC BORDER ==================================================-->
<!DOCTYPE html>
<html>
<head>
<?php include 'head.php'; ?>
</head>
<body>
    <h1> Edit record for <?=htmlentities($data['first_name'])?> <?=htmlentities($data['last_name'])?></h1>
    <?php 
    flashMessage();
    if($data===false){
    }else{
      $first_name=htmlentities($data['first_name']);
      $last_name=htmlentities($data['last_name']);
      $email=htmlentities($data['email']);
      $headline=htmlentities($data['headline']);
      $summary=htmlentities($data['summary']);
      $pid=htmlentities($_GET['profile_id']);
    }
    ?>
    <form method='POST'>
        <label for='first_name'>First Name:</label>
        <input type='text' name='first_name' id='first_name' value='<?=$first_name?>'><br/>
        <label for='last_name'>Last Name:</label>
        <input type='text' name='last_name' id='last_name' value='<?=$last_name?>'><br/>
        <label for='email'>Email:</label>
        <input type='text' name='email' id='email' value='<?=$email?>'><br/>
        <label for='headline'>Headline:</label>
        <input type='text' name='headline' id='headline' value='<?= $headline ?>'><br/>
        <label for='summary'>Summary:</label>
        <textarea name='summary' id='summary' rows='10' cols='40'><?= $summary ?></textarea><br/>
        <?php
        if($positions!==false){
            echo("<label for='addpos'>Positions</label>");
            echo("<input type='button' id='addpos' value='+'><br/>");
            echo("<div id='positions_holder'>");
            foreach($positions as $var){
                echo("<div id='position".$var['rank']."'>"); 
                    echo("<p>Year: <input type='text' name='year".$var['rank']."' id='year".$var['rank']."' value='".htmlentities($var['year'])."' /> ");
                    echo("<input type = 'button' value='-' onclick='$(\"#position".$var['rank']."\").remove();return false;'></p>");
                    echo("<textarea name='desc".$var['rank']."' id='desc".$var['rank']."' rows ='8' cols = '80'>".htmlentities($var['description'])."</textarea>");
                echo("</div>");
            }
            echo("</div>");
        }
        if($educations!==false){
            echo("<label for='addedu'>Educations</label>");
            echo("<input type = 'button' id='addedu' value ='+'/><br/>");
            echo("<div id='educations_holder'>");
            foreach($educations as $var){
                echo("<div id='education".$var['rank']."'>"); 
                    echo("<p>Year: <input type='text' name='year_s".$var['rank']."' id='year_s".$var['rank']."' value='".htmlentities($var['year'])."' /> ");
                    echo("<input type = 'button' value='-' onclick='$(\"#education".$var['rank']."\").remove();return false;'></p>");
                    echo("<input type='text' name='school".$var['rank']."' id='school".$var['rank']."' class='school' value='".htmlentities(getSchoolNameFromId($pdo, $var['institution_id']))."' />");
                echo("</div>");  
            }
            echo("</div>");
        }
        ?>
        <input type = 'hidden' name='pid' id='pid' value='<?=$pid?>'>
        <input type='submit' value='Save'>
        <input type='submit' name='cancel' value='Cancel'>
    </form>

    

    <!-- This is the doc ready script, within is a call function to a clickable button to add a div field to the page-->
    <script>
            countPos = $("#positions_holder").children().length;
            countEdu = $("#educations_holder").children().length;
            $(document).ready(function(){
                window.console && console.log('Document ready called..');
                $("#addpos").click(function(event){
                    event.preventDefault();
                    if (countPos>=9){
                        alert("Maximum of nine postion entry attempts permitted.\
                        Refresh to re-set.");
                        return;
                    }
                    countPos++;
                    window.console && console.log("Adding position"+countPos);
                    $('#positions_holder').append(
                        '<div id="position'+countPos+'"> \
                        <p>Year: <input type="text" name="year'+countPos+'" id="year'+countPos+'" value="" /> \
                        <input type = "button" value="-" \
                            onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
                        <textarea name="desc'+countPos+'" id="desc'+countPos+'" rows ="8" cols = "80"></textarea>\
                        </div>');
                })
                $("#addedu").click(function(event){
                    event.preventDefault();
                    if (countEdu>=9){
                        alert("Maximum of nine postion entry attempts permitted.\
                        Refresh to re-set.");
                        return;
                    }
                    countEdu++;
                    window.console && console.log("Adding education"+countEdu);
                    $('#educations_holder').append(
                        '<div id="education'+countEdu+'"> \
                        <p>Year: <input type="text" name="year_s'+countEdu+'" id="year_s'+countEdu+'" value="" /> \
                        <input type = "button" value="-" \
                            onclick="$(\'#education'+countEdu+'\').remove();return false;"></p> \
                        <p>School: \
                        <input type="text" name="school'+countEdu+'" class="school" id="school'+countEdu+'"/>\
                        </div>');
                })
                return false;
            })
            $(document).ready(function(){
                window.console && console.log('Should autofill');
                $('.school').autocomplete({source: "school.php"});
            });
    </script>
</body>

</html>
