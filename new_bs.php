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

        $bs_id = trim($_POST["bs_id"]);

        //Perform some validation
        //Feel free to edit / change as required

        if (minMaxRange(1, 100, $bs_name)) {
            $errors[] = 'BS name is mandatory. Valid vlaue between 1 to 100 chars';
        }
        if (minMaxRange(5, 15, $bs_ip)) {
            $errors[] = 'IP Address is mandatory';
        }

        if (empty($bs_lat))
            $bs_lat = 'NULL';
        if (empty($bs_long))
            $bs_long = 'NULL';
        if (empty($ant_direction))
            $ant_direction = '0';

        //End data validation
        if (count($errors) == 0) {
            //Update the BS object
            include("db_connect.php");
            $query = "INSERT INTO bs (objid, name, ip, location_lat, location_long, location_height, ant_direction, ant_gain. ant_type,max_tx_power, sas_activated, sas_approved, sas_last_action) " .
                    "VALUES (NULL, '$bs_name', '$bs_ip', '$bs_lat', '$bs_long','$bs_height',$ant_direction,$ant_gain,$ant_type,$max_power,0,0,0)";



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


        $bs_id = trim($_POST["bs_id"]);


        if (minMaxRange(5, 15, $bs_ip)) {
            $errors[] = 'IP Address is required in order to fetch base station information.';
        } else {
            // do the SNMP 
            require_once( 'OSS_SNMP/SNMP.php' );
            $host = new \OSS_SNMP\SNMP($bs_ip, 'public');

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
    $bs_height = "";
    $ant_direction = "";
    $ant_gain = "";
    $ant_type = "";
    $max_power = "";

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
        <link rel="stylesheet" href="assets/css/forms.css"/>
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/font-awesome.min.css"/>

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

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="antd">Antenna Direction:</label>
                            <div class="col-sm-2 controls">
                                <input type="text" class="form-control input-xlarge" name="antd" id="antd" value='<?php echo $ant_direction ?>'/>
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
                            <label class="control-label col-sm-2" for="antg">Antenna Gain:</label>
                            <div class="col-sm-2 controls">
                                <input type="text" class="form-control input-xlarge" name="antg" id="antg" value='<?php echo $ant_gain ?>'/>
                            </div>
                        </div>  

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="max_tx">BS Max TX Power:</label>
                            <div class="col-sm-2 controls">
                                <input type="text" class="form-control input-xlarge" name="max_tx" id="max_tx" value='<?php echo $max_power ?>'/>
                            </div>
                        </div>  

                        <div class="form-actions"> 
                            <div class="col-sm-offset-2 col-sm-2">
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
                                minlength: 10,
                                required: true
                            },                    
                            longit: {
                                minlength: 10,
                                required: true
                            },
                            height: {
                                minlength: 10,
                                required: true
                            },                        
                            antd: {
                                minlength: 1,
                                required: true
                            },
                            antg: {
                                minlength: 1,
                                required: true
                            },
                            antt: {
                                minlength: 1,
                                required: true
                            },
                            max_tx: {
                                minlength: 1,
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
                            lat: "Please provide base station GPS altitude coordinate",
                            longit: "Please provide base station GPS longtitude coordinate",                     
                            height: "Please provide antenna height above sea level",      
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



