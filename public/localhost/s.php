<?php
session_start();
if(!isset($_SESSION["localhost"])){header("Location: /");exit();}
header('Content-Type: application/json');
echo json_encode($_SESSION, JSON_PRETTY_PRINT);
?>
