<?php
session_start();
require_once 'pdo.php';
include 'utilities.php';



if(!isset($_SESSION['name'])||!isset($_SESSION['user_id'])){
    die("ACCESS DENIED");
}

if(isset($_POST['cancel'])){
    header("Location: index.php");
    return;
}

if(isset($_POST['first_name'])){

    //full post input validation
    if(fullPostValid()!==true){
        $_SESSION['fail']=fullPostValid();
        header("Location: add.php");
        return;
    }

    //positions validation
    $valid_pos = validatePos();
    if (is_string($valid_pos)){
        $_SESSION['fail'] =  $valid_pos;
        header("Location: add.php");
        return;
    }

    //educations validation
    $valid_edu = validateEdu();
    if (is_string($valid_edu)){
        $_SESSION['fail'] =  $valid_edu;
        header("Location: add.php");
        return;
    }

    $sqladd = "INSERT INTO profile(user_id,first_name,last_name,email,headline,summary) VALUES(:uid,:fn,:ln,:em,:hl,:sm);";
    $addstmt = $pdo->prepare($sqladd);
    $addstmt->execute(array(':uid'=>$_SESSION['user_id'],
                            ':fn'=>$_POST['first_name'],
                            ':ln'=>$_POST['last_name'],
                            ':em'=>$_POST['email'],
                            ':hl'=>$_POST['headline'],
                            ':sm'=>$_POST['summary']));

    $profile_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare('INSERT INTO Positions (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');

    $rank = 1;
    for($i=1; $i<=9; $i++) {
      if ( !isset($_POST['year'.$i]) && !isset($_POST['desc'.$i])){
        continue;
      }
    
      $year = $_POST['year'.$i];
      $desc = $_POST['desc'.$i];
      $stmt = $pdo->prepare('INSERT INTO Positions
        (profile_id, rank, year, description)
        VALUES ( :pid, :rank, :year, :desc)');
    
      $stmt->execute(array(
      ':pid' => $profile_id,
      ':rank' => $rank,
      ':year' => $year,
      ':desc' => $desc)
      );
    
      $rank++;
    
    }

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
        $stmt2->execute(array(':pid'=>$profile_id, ':iid'=>$school_id, ':rank'=>$rank, ':year'=>$year_s));

        $rank++;
    } 


    $_SESSION['success'] = 'Record added';
    unset($_SESSION['add_fname']);unset($_SESSION['add_lname']);unset($_SESSION['add_em']);unset($_SESSION['add_hl']);unset($_SESSION['add_sum']);
    header("Location: index.php");
    return;

}

function preserveFormData($var){
    if(isset($_SESSION["$var"])){
        return htmlentities($_SESSION["$var"]);
        unset($_SESSION["$var"]);
    }else{
        return null;
    }
}
?>



<!-- MVC BORDER. NO INPUT ABOVE ================================================================================================================================================ -->
<!DOCTYPE html>
<html>
    <head>
    <?php include "head.php" ?>
    </head>
    <body>
        <h1 style='color:#aa66ff'>Add a profile</h1>
        <?php flashMessage(); ?>
        <form method="POST">
            <label for="first_name">First Name</label>
            <input type='text' name='first_name' id='first_name' value='<?= preserveFormData('add_fname'); ?>'><br/>
            <label for="last_name">Last Name</label>
            <input type='text' name='last_name' id='last_name' value='<?= preserveFormData('add_lname'); ?>'><br/>
            <label for="email ">Email</label>
            <input type='text' name='email' id='email' value='<?= preserveFormData('add_em'); ?>'><br/>
            <label for="headline">Headline</label>
            <input type='text' name='headline' id='headline' value='<?= preserveFormData('add_hl'); ?>'><br/>
            <label for="summary">Summary</label>
            <textarea rows='10' cols='40' name='summary' id='summary'><?= preserveFormData('add_sum'); ?></textarea><br/>
            <label>Positions</label>
            <input type='button' id='addpos' value='+'><br/>
            <div id="positions_holder"></div>
            <label>Education</label>
            <input type='button' id='addedu' value='+'><br/>
            <div id="educations_holder"></div> 
            <input type='submit' value='Add'>
            <input type='submit' name='cancel' id='cancel' value='Cancel'>
        </form>
                

        <script>
            countPos = 0;
            countEdu = 0;
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