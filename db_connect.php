<?php
$dbhost							= "localhost";
$dbuser							= "root";
$dbpass							= "root";
$dbname							= "dd_test";

$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

?>