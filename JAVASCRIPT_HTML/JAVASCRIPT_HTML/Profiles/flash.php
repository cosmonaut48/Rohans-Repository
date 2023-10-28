<?php
if(isset($_SESSION['fail'])){
    echo("<p style='color:red'>".$_SESSION['fail']."</p>");
    unset($_SESSION['fail']);
}
if(isset($_SESSION['success'])){
    echo("<p style='color:green'>".$_SESSION['success']."</p>");
    unset($_SESSION['success']);
}
?>
