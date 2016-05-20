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
    $alt = trim($_POST["alt"]);
    $antdir = trim($_POST["ant"]);
    $bs_id = trim($_POST["bs_id"]);

    //Perform some validation
    //Feel free to edit / change as required

    if (minMaxRange(12, 21, $long)) {
        $errors[] = 'Longitude should be a number with 12-20 digits';
    }
    if (minMaxRange(12, 21, $alt)) {
        $errors[] = 'altitude should be a number with 12-20 digits';
    } else if (empty($bsname)) {
        $errors[] = 'Base station name cannot be empty';
    }

    //End data validation
    if (count($errors) == 0) {
        //Update the BS object
        include("db_connect.php");
        $query = "UPDATE bs SET name='$bsname',ip='$bsip', location_long=$long, location_alt=$alt, ant_direction=$antdir WHERE  objid=$bs_id";
        if (!$mysqli->query($query)) {
            mysqli_close($mysqli);
            $errors[] = 'SQL Error, update query failed';
            $errors[] = $query;
        }
    }
    if (count($errors) == 0) {
        $message = '<span style="color: green;"> Updated Succeffuly </span>';
    } else {
        $message = '<span style="color: red;">' . implode(", ", $errors) . '</span>';
    }
}


if (isset($_GET["id"])) {
    $bs_id = $_GET['id'];
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

        <?php
        include("db_connect.php");


        $query = "SELECT * FROM bs where objid=$bs_id";
        $result = $mysqli->query($query);
        mysqli_close($mysqli);
        $bs_name = "";
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {

            $bs_name = $row['name'];
            $bs_ip = $row['ip'];
            $bs_alt = $row['location_alt'];
            $bs_long = $row['location_long'];
            $ant_direction = $row['ant_direction'];
        }
        if (empty($bs_name)) {
            $message = "BS with id '$bs_id' could not be found ";
        }
        ?>


        <div >
            <div class="modal-header" >
                <h2>Update Base Station <?php echo $bs_name ?></h2>
            </div>
            <div class="">

                <div id="success">
                    <p><?php if (isset($message))
            echo $message; ?></p>
                </div>

                <div id="regbox">
                    <form id="editBS"  class="form-horizontal" role="form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
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
                                <input type="text" class="form-control" name="alt" id="alt" value='<?php echo $bs_alt ?>'/>
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
                    alt: {
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



