<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'sql102.epizy.com');
define('DB_USERNAME', 'epiz_23124467');
define('DB_PASSWORD', 'Nhcz8s1jL');
define('DB_NAME', 'epiz_23124467_kernelizationusers');

/* Attempt to connect to MySQL database */
$link = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($link->connect_error){
    die("ERROR: Could not connect. " . $conn->connect_error);
}
?>
