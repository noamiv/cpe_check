<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

const CONFIG_TYPE_TEXT = 0;
const CONFIG_TYPE_SELECT = 1;

const CONFIG_MAP_REFRESH_INTERVAL = 1;
const CONFIG_SNMP2_READ_COMM = 2;
const CONFIG_SNMP2_WRITE_COMM = 3;
const CONFIG_MAP_SOURCE = 4;

const CONFIG_MAP_SOURCE_ONLINE = 0;
const CONFIG_MAP_SOURCE_LOCAL = 1;

class CONFIG_MGR {

    private static $initialized = false;
    
    private static $cache ;

    public static function GET($config_id) {
        self::initialize();        
        return self::$cache[$config_id];
    }
    
    public static function SET($config_id, $value) {
        self::initialize();        
        self::$cache[$config_id] = $value;
    }

    private static function initialize() {
        if (self::$initialized)
            return;

        /*Init cache*/
        include("db_connect.php");
        self::$cache = array();
        $result = $mysqli->query("SELECT objid,p_value  FROM meta_config");
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC) ) {
            self::$cache[$row['objid']] = $row['p_value'];
        }            
         mysqli_close($mysqli);
         
        self::$initialized = true;
        //place holder for constructor activities
    }

}

?>
