<?php
/**
 * Prevents users from manually indexing the JavaScript folder.
 */
header('Content-Type: application/json');
header('HTTP/1.1 500 Internal Server Error');
exit();
