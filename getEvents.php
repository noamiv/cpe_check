<?php
require_once ('config_mgr.php');

include("db_connect.php");

if (isSet($_POST['funcName'])) {
    if ($_POST['funcName'] == 'load') {
    /*this will happen once on the load*/    
        $rows = array();
        
        $query = "SELECT MAX(objid)AS max FROM event_notifier";
        $result = $mysqli->query($query);
        $row = mysqli_fetch_array($result, MYSQL_ASSOC);
        $rows['maxEventIndex'] = $row['max'];
        mysqli_close($mysqli);
        
        $map_refresh = CONFIG_MGR::GET(CONFIG_MAP_REFRESH_INTERVAL);
        $rows['map_refresh_interval'] = $map_refresh;
                
        echo json_encode($rows);
        return;
    }
} else {

    $lastIndex = $_POST['id'];
//$lastIndex =0;

    $respone = array();
    $respone[0] = $lastIndex;
    if (isset($lastIndex)) {

        $query = "SELECT * FROM event_notifier WHERE objid > $lastIndex";
        $result = $mysqli->query($query);

        //prepare the JavaCode commands which will be exceuted by the client:  
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
            switch ($row['event2desc']) {
                case '1':
                    $respone[] = "SsFeature_" . $row['event2ss'] . ".setStyle(SsStyleRed)";
                    break;
                case '2':                             
                    $respone[] = "BsFeature_" . $row['event2bs'] . ".setStyle(BsStyle_".$row['event2bs']."_red)";
                    break;
                case '3':
                    $respone[] = "SsFeature_" . $row['event2ss'] . ".setStyle(SsStyleGreen)";
                    break;
                case '4':
                    $respone[] = "BsFeature_" . $row['event2bs'] . ".setStyle(BsStyle_".$row['event2bs'].")";
                    break;
                default:
                    error_log("unknow event type: " . $row['event2desc']);
                    break;
            }

            $respone[0] = $row['objid'];
        }

        echo json_encode($respone);
        ;
    } else {
        die("error - no last index");
    }
    mysqli_close($mysqli);
}
?>

