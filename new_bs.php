<?php
require_once("models/config.php");

/*
 * Check for login
 */
if (!isset($loggedInUser)) {
    header('Location: login.php');
    exit();
}

//Forms posted
if (!empty($_POST)) {
    $errors = array();
    $bsname = trim($_POST["bsname"]);
    $bsip = trim($_POST["bsip"]);
    $long = trim($_POST["longit"]);
    $lat = trim($_POST["lat"]);
    $antdir = trim($_POST["ant"]);
    $bs_id = trim($_POST["bs_id"]);

    //Perform some validation
    //Feel free to edit / change as required

    if (minMaxRange(1, 100, $bsname)) {
        $errors[] = 'BS name is mandatory. Valid vlaue between 1 to 100 chars';
    }
    if (minMaxRange(5, 15, $bsip)) {
        $errors[] = 'IP Address is mandatory';
    } 
    
    if (empty($lat))    $lat='NULL';
    if (empty($long))   $long='NULL';
    if (empty($antdir)) $antdir='0';

    //End data validation
    if (count($errors) == 0) {
        //Update the BS object
        include("db_connect.php");
        $query = "INSERT INTO bs (objid, name, ip, location_lat, location_long, ant_direction) VALUES (NULL, '$bsname', '$bsip', $lat, $long,$antdir)";
        
        
        
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
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Edit BS | <?php echo $bs_id; ?> </title>
        <script src="js/jquery-2.1.4.min.js"></script>
        <script src="assets/js/jquery.validate.min.js"></script>
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
                    <form id="newBS"  class="form-horizontal" role="form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                        <input type="hidden" name="bs_id" value='<?php echo $bs_id ?>'/>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="bsname">BS Name:</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="bsname" id="bsname" value='<?php echo $bs_name ?>'/>
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
                            <label class="control-label col-sm-2" for="long">Location Longitude:</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="longit" id="longbit" value='<?php echo $bs_long ?>'/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="ant">Antenna Direction:</label>
                            <div class="col-sm-2 controls">
                                <input type="text" class="form-control input-xlarge" name="ant" id="ant" value='<?php echo $ant_direction ?>'/>
                            </div>
                        </div>                        

                        <div class="form-actions"> 
                            <div class="col-sm-offset-2 col-sm-2">
                                <button type="submit" class="btn btn-primary">Apply</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div> 
        </div>  
    </body>

    <script>
        
        $.validator.addMethod('IP4Checker', function(value) {
            var ip = "^(?:(?:25[0-5]2[0-4][0-9][01]?[0-9][0-9]?)\.){3}" +
                "(?:25[0-5]2[0-4][0-9][01]?[0-9][0-9]?)$";
            return value.match(ip);
        }, 'Invalid IP address');
        
        $(document).ready(function(){
 
            $('#editBs').validate(
            {
                rules: {
                    bsname: {
                        minlength: 2,
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
                    ant: {
                        minlength: 1,
                        required: true
                    }
                },
                highlight: function(element) {
                    $(element).closest('.form-group').removeClass('success').addClass('error');
                },
                success: function(element) {
                    element
                    .text('OK!').addClass('valid')
                    .closest('.form-group').removeClass('error').addClass('success');
                }
            });
        }); // end document.ready
    </script>



</html>



