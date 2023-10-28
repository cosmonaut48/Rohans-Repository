<?php
session_start();
require_once "pdo.php";

$sqlprofile = 'SELECT * FROM Profile';
$profile=$pdo->query($sqlprofile);

function canEdit($rowuser,$rowprofile){
    if (isset($_SESSION['user_id'])){
        if ($rowuser==$_SESSION['user_id']){
            echo("<a href='edit.php?profile_id=".$rowprofile."'>Edit</a>");
        }else{
            echo("<span style:'text-decoration: line-through'>Edit</span>");
        }
    }else{
        echo("<span style:'text-decoration: line-through'>Edit</span>");
    }
}
function canDelete($rowuser,$rowprofile){
    if (isset($_SESSION['user_id'])){
        if ($rowuser==$_SESSION['user_id']){
            echo("<a href='delete.php?profile_id=".$rowprofile."'>Delete</a>");
        }else{
            echo("<span style:'text-decoration: line-through'>Delete</span>");
        }
    }else{
        echo("<span style:'text-decoration: line-through'>Delete</span>");
    }
}

function toView($user){
    echo("<a href='view.php?profile_id=".$user."'>View</a>");
}
?>
<!-- MODEL VIEW BOUNDARY -->
<!DOCTYPE html>
<html>
    <head>
        <title>Rohan Mars</title>
    </head>
    <body style="font-family:arial">
        <?php
        include "flash.php";
        ?>
        <h1 style="color:seagreen">Rohan's profile database viewer</h1>
        <?php
        if (isset($_SESSION['name'])){
            echo("<p style='color:green'>Current user:".$_SESSION['name']."</p>");
        }else{
            echo("<p style='color:red'>No current user</p>");
        }
        ?>
        <table border=1>
            <tr>
                <td>First Name</td>
                <td>Last Name</td>
                <td>Headline</td>
                <td>Action</td>
            </tr>
            <?php
            while ($row=$profile->fetch(PDO::FETCH_ASSOC)){
                echo("<tr><td>");
                echo($row['first_name']);
                echo("</td><td>");
                echo($row['last_name']);
                echo("</td><td>");
                echo($row['headline']);
                echo("</td><td>");
                toView($row['profile_id']);
                echo(" / ");
                canEdit($row['user_id'],$row['profile_id']);
                echo(" / ");
                canDelete($row['user_id'],$row['profile_id']);
                echo("</td></tr>");
            }
            ?>
        </table>
        <?php
        if(isset($_SESSION['name'])&&isset($_SESSION['user_id'])){
            echo("<a href='add.php'>Add New Entry</a>");
            echo('||');
            echo("<a href=logout.php>Log out</a>");
        }else{
            echo("<a href='login.php'>Please log in</a>");
        }

        ?>

    </body>
</html>
