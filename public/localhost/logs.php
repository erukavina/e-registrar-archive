<?php
session_start();
if(!isset($_SESSION["localhost"])){header("Location: /");exit();}
if(isset($_GET["clearlogs"])){
    unset($_SESSION["logs"]);
    header("Location: /localhost/logs");
}
echo "<a href='?clearlogs'>CLEAR LOGS</a>";
echo "<br><hr><pre>";
print_r($_SESSION["logs"]);
echo "</pre>";