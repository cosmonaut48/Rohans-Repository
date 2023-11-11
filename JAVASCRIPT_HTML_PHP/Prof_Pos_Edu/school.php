<?php
session_start();
require_once 'pdo.php';
include 'utilities.php';

header('Content-Type: application/json; charset=utf-8');

$stmt = $pdo->prepare('SELECT name FROM Institution
    WHERE name LIKE :prefix');
$stmt->execute(array( ':prefix' => $_REQUEST['term']."%"));

$retval = $stmt->fetchAll(PDO::FETCH_ASSOC);


echo(json_encode($retval, JSON_PRETTY_PRINT));

?>