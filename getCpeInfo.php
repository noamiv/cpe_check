<?php


include("db_connect.php");
$bs_id = $_POST['bs_id'];

if (isset($bs_id)) {
    $query = "SELECT * FROM cpe WHERE cpe2bs= $bs_id";
    $result = $mysqli->query($query);

    // start table
        $html = '<table>';
        // header row
        $html .= '<tr>';
        $html .= '<th>CPE Name</th>';
        $html .= '<th>DL CINR</th>';
        $html .= '<th>UL CINR</th>';
        $html .= '<th>DL RSSI</th>';
        $html .= '<th>UL RSSI</th>';
        $html .= '<th>Link Uptime (HH:MM:SS)</th>';
        $html .= '</tr>';
    
    while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {        
        $html .= '<tr>';
        $html .= '<td>' . trim($row['name']) . '</td>';
        $html .= '<td>' . trim($row['dl_cinr']) . '</td>';
        $html .= '<td>' . trim($row['ul_cinr']) . '</td>';
        $html .= '<td>' . trim($row['dl_rssi']) . '</td>';
        $html .= '<td>' . trim($row['ul_rssi']) . '</td>';
        $html .= '<td>' . gmdate("H:i:s", $row['uptime']) . '</td>';  
        $html .= '</tr>';
    }
     
    // finish table and return it
     $html .= '</table>';
            
     echo $html;
    
}else {
    die("error - no BS ID");
}
mysqli_close($mysqli);
?>

