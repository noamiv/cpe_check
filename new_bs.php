<?php
require_once("models/config.php");

/*
 * Check for login
 */
//if (!isset($loggedInUser)) {
//    header('Location: login.php');
//    exit();
//}
//Forms posted
if (isSet($_POST['funcName'])) {
    if ($_POST['funcName'] == 'add') {
        $errors = array();
        $bs_name = trim($_POST["bsname"]);
        $bs_ip = trim($_POST["bsip"]);
        $bs_long = trim($_POST["longit"]);
        $bs_lat = trim($_POST["lat"]);
        $bs_height = trim($_POST["height"]);
        $ant_direction = trim($_POST["antd"]);
        $ant_gain = trim($_POST["antg"]);
        $ant_type = trim($_POST["antt"]);
        $max_power = trim($_POST["max_tx"]);        
        $ant_downtilt_m = trim($_POST["dtm"]);
        $ant_downtilt_e = trim($_POST["dte"]);        
        $ant_beamw      = trim($_POST["antbw"]);
        $ant_model      = trim($_POST["model"]);
        $sas_active     = trim($_POST["sas"]);
        $snmp_read    = trim($_POST["snmpr"]);
        $snmp_write    = trim($_POST["snmpw"]);        

        $bs_id = trim($_POST["bs_id"]);

        //Perform some validation
        //Note the validation is done in the client with JS, this is just for extra protection!
        //Feel free to edit / change as required

        if (minMaxRange(1, 100, $bs_name)) {
            $errors[] = 'BS name is mandatory. Valid vlaue between 1 to 32 chars';
        }
        if (minMaxRange(5, 15, $bs_ip)) {
            $errors[] = 'IP Address is mandatory';
        }

        if (empty($bs_lat)){
            $bs_lat = 'NULL';
        } else {
          //  if (! preg_match('/^(?[-]?[0-8]?[0-9]\.\d+|[-]?90\.0+?)/', $bs_lat))
           //     $errors[] = 'Latitude format is invalid.';    
        }
        if (empty($bs_long))
            $bs_long = 'NULL';
        if (empty($ant_direction))
            $ant_direction = '0';

        //End data validation
        if (count($errors) == 0) {
            //Update the BS object
            include("db_connect.php");
            $query = "INSERT INTO bs (`objid`, `name`, `ip`, `location_lat`, `location_long`, `ant_direction`, `ant_height`, ".
                                   "`ant_gain`, `ant_downtilt_mech`, `ant_downtilt_elec`, `ant_beamwidth`, `ant_model`, `ant_type`, `max_tx_power`,".
                                   " `sas_active`, `bs2status`, bs2cbsd_status) ".                    
                    "VALUES (NULL, '$bs_name', '$bs_ip', '$bs_lat', '$bs_long',$ant_direction,'$bs_height',".
                                    "$ant_gain,'$ant_downtilt_m', '$ant_downtilt_e', '$ant_beamw','$ant_model', $ant_type,$max_power,".
                                    "$sas_active,3,6)";



            if (!$mysqli->query($query)) {
                mysqli_close($mysqli);
                $errors[] = 'SQL Error, insert query failed';
                $errors[] = $query;
            }
        }
        if (count($errors) == 0) {
            $message = '<span style="color: green;"> Created Succeffuly </span>';
        } else {
            $message = '<span style="color: red;">' . implode(", ", $errors) . '</span>';
        }
    } else if ($_POST['funcName'] == 'fetch') {

        $errors = array();
        $bs_name = trim($_POST["bsname"]);
        $bs_ip = trim($_POST["bsip"]);
        $bs_long = trim($_POST["longit"]);
        $bs_lat = trim($_POST["lat"]);
        $bs_height = trim($_POST["height"]);
        $ant_direction = trim($_POST["antd"]);
        $ant_gain = trim($_POST["antg"]);
        $ant_type = trim($_POST["antt"]);
        $max_power = trim($_POST["max_tx"]);
        $ant_downtilt_m = trim($_POST["dtm"]);
        $ant_downtilt_e = trim($_POST["dte"]);        
        $ant_beamw      = trim($_POST["antbw"]);
        $ant_model      = trim($_POST["model"]);
        $sas_active     = trim($_POST["sas"]);
        $snmp_read    = trim($_POST["snmpr"]);
        $snmp_write    = trim($_POST["snmpw"]);

        $bs_id = trim($_POST["bs_id"]);


        if (minMaxRange(5, 15, $bs_ip)) {
            $errors[] = 'IP Address is required in order to fetch base station information.';
        } else {
            // do the SNMP 
            require_once( 'OSS_SNMP/SNMP.php' );
            require_once( 'config_mgr.php' );

            $snmp_read = CONFIG_MGR::GET(CONFIG_SNMP2_READ_COMM);

            $host = new \OSS_SNMP\SNMP($bs_ip, $snmp_read);

            try {
                $bs_name = $host->useWinBs()->site_id();
                $ant_direction = $host->useWinBs()->antenna_azimuth();

                $gps_mode = $host->useWinBs()->gps_mode();
                if ($gps_mode == 1) {
                    $bs_long = $host->useWinBs()->location_long();
                    $bs_lat = $host->useWinBs()->location_lat();
                } else {
                    $errors[] = "GPS mode is Off. Location should be entred manaully.";
                }
            } catch (Exception $e) {
                // If we get here, it timed out. Now check to see if the peer server is up.
                $errors[] = "SNMP timed out";
            }
        }

        if (count($errors) == 0) {
            $message = '<span style="color: green;"> Fetch Succeffuly </span>';
        } else {
            $message = '<span style="color: red;">' . implode(", ", $errors) . '</span>';
        }
    }
} else {
    $bs_name = "";
    $bs_ip = "";
    $bs_long = "";
    $bs_lat = "";
    $bs_height = "0";
    $ant_direction = "";
    $ant_gain = "";
    $ant_type = "";
    $max_power = "";
    $ant_downtilt_m = "0";
    $ant_downtilt_e = "0"; 
    $ant_beamw ="";     
    $ant_model ="";     
    $sas_active =1;
    $snmp_read ="";
    $snmp_write ="";
    
    $bs_id = "";
    $message = "";
}
// <script src="js/jquery-2.1.4.min.js"></script>
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>New BS </title>

        <script src="js/jquery-2.1.4.min.js"></script>
        <script src="assets/js/jquery.validate.js"></script>
        <script src="js/jquery-ui.js"></script>        
        <link rel="stylesheet" href="assets/css/forms.css"/>
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/font-awesome.min.css"/>
        <link rel="stylesheet" href="css/jquery-ui.css"/>        
        <script>
            $( function() {
                $("#tabs").tabs();
            } );
        </script>
    </head>
    <body>


        <div >
            <div class="modal-header">
                <h2>Create New Base Station</h2>                
            </div>
            <div class="">

                <div id="success">
                    <p><?php if (isset($message))
    echo $message; ?></p>
                </div>

                <div id="regbox">
                    <form id="newBS" name="newBs" class="form-horizontal" role="form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" novalidate="novalidate">                                               
                        <input type="hidden" name="bs_id" value='<?php echo $bs_id ?>'/>
                        <div id="tabs">
                            <ul>
                                <li><a href="#tabs-1">General</a></li>
                                <li><a href="#tabs-2">Location</a></li>
                                <li><a href="#tabs-3">Antenna</a></li>
                                <li><a href="#tabs-4">SNMP</a></li>                                
                            </ul>                            

                            <div id="tabs-1">
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="bsname">BS Name:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" name='bsname' id='bsname' value='<?php echo $bs_name ?>'/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="bsip" >IP Address:</label>
                                    <div class="col-sm-2">                                
                                        <input type="text" class="form-control" name="bsip" id="bsip" value='<?php echo $bs_ip ?>'/>                               
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="max_tx">BS Max TX Power:</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" class="form-control input-xlarge" name="max_tx" id="max_tx" value='<?php echo $max_power ?>'/>
                                    </div>
                                </div>   
                               <?php
                                $selected[0] = '';
                                $selected[1] = '';                                
                                $selected[$sas_active] = 'selected';
                                ?>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="sas">SAS:</label>
                                    <div class="col-sm-2 controls">
                                        <select class="form-control" name="sas" id="sas">
                                            <option value="1" <?php echo $selected[1] ?> >Enabled</option>
                                            <option value="0" <?php echo $selected[0] ?> >Disabled</option>                                            
                                        </select>                                
                                    </div>
                                </div>                                  

                            </div>
                            <div id="tabs-2">
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="alt">Location Altitude:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" name="lat" id="lat" value='<?php echo $bs_lat ?>'/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="longit">Location Longitude:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" name="longit" id="longbit" value='<?php echo $bs_long ?>'/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="height">Location Height:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" name="height" id="height" value='<?php echo $bs_height ?>'/>
                                    </div>
                                </div>
                            </div>

                            <div id="tabs-3">
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="antd">Antenna Azimuth:</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" class="form-control input-xlarge" name="antd" id="antd" value='<?php echo $ant_direction ?>'/>
                                    </div>
                                </div>                        
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="dtm">Down tilt Mech:</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" class="form-control input-xlarge" name="dtm" id="dtme" value='<?php echo $ant_downtilt_m ?>'/>
                                    </div>
                                </div>                                
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="dte">Down tilt Elec:</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" class="form-control input-xlarge" name="dte" id="dtme" value='<?php echo $ant_downtilt_e ?>'/>
                                    </div>
                                </div>                                                                
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="antg">Gain:</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" class="form-control input-xlarge" name="antg" id="antg" value='<?php echo $ant_gain ?>'/>
                                    </div>
                                </div>                                  
                                <?php
                                $selected[0] = '';
                                $selected[1] = '';
                                $selected[2] = '';
                                $selected[$ant_type] = 'selected';
                                ?>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="antt">Antenna Type:</label>
                                    <div class="col-sm-2 controls">
                                        <select class="form-control" name="antt" id="antt">
                                            <option value="" <?php echo $selected[0] ?> >Please Select</option>
                                            <option value="1" <?php echo $selected[1] ?> >Omni</option>
                                            <option value="2" <?php echo $selected[2] ?> >Directional</option>                                    
                                        </select>                                
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="antbw">Beam Width:</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" class="form-control input-xlarge" name="antbw" id="antbw" value='<?php echo $ant_beamw ?>'/>
                                    </div>
                                </div>                                  
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="model">Model:</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" class="form-control input-xlarge" name="model" id="model" value='<?php echo $ant_model ?>'/>
                                    </div>
                                </div>                                                                  

                            </div>
                            <div id="tabs-4">

                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="snmpv">SNMP version:</label>
                                    <div class="col-sm-2 controls">
                                        <select class="form-control" name="snmpv" id="snmpv">
                                            <option value="2">SNMPv2c</option>
                                            <option value="3">SNMPv3 - N/A</option>                                            
                                        </select>                                
                                    </div>
                                    <label class="control-label col-sm-2" for="snmpr">Leave empty to use the global configuration values</label>
                                </div>                                  
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="snmpr">V2c Read Community:</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" class="form-control input-xlarge" name="snmpr" id="snmpr" value='<?php echo $snmp_read ?>'/>
                                    </div>                                    
                                </div>                                                                  
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="snmpw">V2c Write Community:</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" class="form-control input-xlarge" name="snmpw" id="snmpw" value='<?php echo $snmp_write ?>'/>
                                    </div>
                                </div>                                                                                                  
                            </div>                                                     
                        </div>

                        <div style="position:fixed;bottom:30px;left:10px;"> 
                            <div class=" col-sm-offset-2 col-sm-2">
                                <button type="submit" name="funcName" value="add" class="btn btn-primary">Apply</button>
                            </div>

                            <div class="col-sm-offset-2 col-sm-2">
                                <button type="submit" name="funcName" value="fetch" id="fetch" class="btn btn-primary">Fetch Data</button>
                            </div>
                        </div>

                    </form>            
                </div>
            </div> 
        </div>  
        <script>
            $.validator.addMethod('IP4Checker', function(value) {
                var ip = "^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$";                                                         
                return  value.match(ip);
            }, 'Invalid IP address');

            
            $(document).ready(function() {                          
                $("form[name='newBs']").validate(               
                {
                    rules: {
                        bsname: {
                            minlength: 1,
                            required: true
                        },
                        bsip: {
                            required: true,
                            IP4Checker: true
                        },
                        lat: {                            
                            minlength: 4,
                            maxlength: 15,
                            required: true
                        },                    
                        longit: {
                            minlength: 4,
                            maxlength: 15,
                            required: true
                        },
                        height: {
                            minlength: 1,                            
                            maxlength: 15,
                            required: true
                        },                        
                        antd: {
                            minlength: 1,
                            maxlength: 15,                            
                            required: true
                        },
                        antg: {
                            minlength: 1,                            
                            maxlength: 15,                            
                            required: true
                        },
                        antt: {
                            minlength: 1,
                            maxlength: 15,
                            required: true
                        },
                        max_tx: {
                            minlength: 1,
                            maxlength: 15,
                            required: true
                        }
                        
                    },
                
                
                    // Specify the validation error messages
                    messages: {
                        bsname: "Please provide a base station name",             
                        bsip: {
                            required: "Please provide the base station IP address",
                            IP4Checker: "IP address format is wrong"
                        },                        
                        longit: "GPS longtitude coordinate length {0} to {1} is requried",                     
                        height: "Please provide antenna height above ground level",      
                        antd: "Please provide antenna direction 0..359",
                        antg: "Please provide antenna gain in dBm"                        
                    },
             
        
                    submitHandler: function(form) {
                        form.submit();
                    }
                });
            }); // end document.ready
        </script>             
    </body>
</html>



