<?php

include("db_connect.php");
$bs = $_POST['bs'];
$ss = $_POST['ss'];

if (isset($bs) AND isset($ss)) {

    $query = "UPDATE cpe SET cpe2bs = " . $bs . " WHERE objid = " . $ss;
    if (! $mysqli->query($query)) {
        mysqli_close($mysqli);
        die('Error, insert query failed');
    }

    if ($mysqli->affected_rows == 0) {        
        echo "No update for BS id $bs and CPE id $ss";    
    }else {
        $bs_name = $_POST['bs_name'];
        $ss_name = $_POST['ss_name'];
        echo "request to move $ss_name to base station $bs_name was sent. Click Validate to see results";
    }
    
    
} else {
    if (!isset($bs))
        echo "BS  not set ";
    if (!isset($ss))
        echo "SS  not set ";
}

mysqli_close($mysqli);
?>