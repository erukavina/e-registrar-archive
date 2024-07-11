<?php
session_start();
if (!isset($_SESSION["localhost"])) {
    header("Location: /");
    exit();
}
?>
<a target="_blank" href="logs">Logs</a>
-
<a target="_blank" href="s">Session info</a>