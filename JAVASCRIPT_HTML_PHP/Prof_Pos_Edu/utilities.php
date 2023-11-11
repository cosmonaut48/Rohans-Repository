<?php

//function to show a success/fail message as passed into _SESSION and clear it afterwards
function flashMessage(){
    if(isset($_SESSION['fail'])){
        echo("<p style='color:red'>".$_SESSION['fail']."</p>");
        unset($_SESSION['fail']);
    }
    if(isset($_SESSION['success'])){
        echo("<p style='color:green'>".$_SESSION['success']."</p>");
        unset($_SESSION['success']);
    }
}

function validatePos(){
    for($i=1;$i<=9;$i++){
        if(!isset($_POST['year'.$i])) continue;
        if(!isset($_POST['desc'.$i])) continue;
        //skip data gaps
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];
        if (strlen($year) == 0 || strlen($desc) == 0){
            return 'All fields are required';
        }

        if (!is_numeric($year)){
            return 'Postion year must be numeric';
        }
    }
    return true;
}

function validateEdu(){
    for($i=1;$i<=9;$i++){
        if(!isset($_POST['year_s'.$i])) continue;
        if(!isset($_POST['school'.$i])) continue;
        //skip data gaps
        $year = $_POST['year_s'.$i];
        $school = $_POST['school'.$i];
        if (strlen($year) == 0 || strlen($school) == 0){
            return 'All fields are required';
        }

        if (!is_numeric($year)){
            return 'Education year must be numeric';
        }
    }
    return true;
}

function loadPos($pdo, $profile_id){
    $stmt = $pdo->prepare('SELECT * FROM Positions where profile_id = :prof order by rank');
    $stmt-> execute(array(":prof"=> $profile_id));
    $positions=array();
    while ($row= $stmt->fetch(PDO::FETCH_ASSOC)){
        $positions[]=$row;
    }
return $positions;
}

function loadEdu($pdo, $profile_id){
    $stmt = $pdo->prepare('SELECT * FROM education where profile_id = :prof order by rank');
    $stmt-> execute(array(":prof"=> $profile_id));
    $educations=array();
    while ($row= $stmt->fetch(PDO::FETCH_ASSOC)){
        $educations[]=$row;
    }
return $educations;
}

function getSchoolNameFromId($pdo, $iid){
    $stmt = $pdo->prepare('SELECT name FROM institution WHERE institution_id = :iid');
    $stmt->execute(array(':iid'=>$iid));
    $name = $stmt->fetchColumn();
    return $name;
}

function fullPostValid(){
    //check all data has passed in non-empty
    foreach($_POST as $item){
        if(strlen($item)<1){
            return 'All fields are required';
        }
    }
    //check a set email is passed in valid
    if(isset($_POST['email'])){
        if(strpos($_POST['email'],'@')<1){
            return 'Email must contain an at-sign (@)';
        }
    }
    return true;
}
?>
