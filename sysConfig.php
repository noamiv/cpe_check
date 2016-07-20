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

    //Perform some validation
    //Feel free to edit / change as required
    //End data validation
    if (count($errors) == 0) {
        //Update the BS object
        include("db_connect.php");
        $sql_str = "";
        foreach ($_POST AS $key => $value) {
            $sql_str .= "UPDATE meta_config SET p_value='" . trim($value) . "' WHERE objid=" . trim($key) . ";";
        }

        if (!$mysqli->multi_query($sql_str)) {
            mysqli_close($mysqli);
            $errors[] = 'SQL Error, update query failed';
            $errors[] = $sql_str;
        }
    }
    if (count($errors) == 0) {
        $message = '<span style="color: green;"> Updated Succeffuly</span>';
    } else {
        $message = '<span style="color: red;">' . implode(", ", $errors) . '</span>';
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Update Sys Config</title>
        <script src="js/jquery-2.1.4.min.js"></script>
        <script src="assets/js/jquery.validate.min.js"></script>
        <link rel="stylesheet" href="assets/css/forms.css"/>
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/font-awesome.min.css"/>
        <script>
            /*this function is requreied to ensure only the changed fields are sent with POST*/
            $(function() {
                $(':input').change(function() {
                    $(this).addClass('changed');
                });
                $('form').submit(function () {
                    $('form').find(':input:not(.changed)').remove();
                    return true;
                });
            });    
        </script>
    </head>
    <body>

        <?php
        include("db_connect.php");


        $query = "SELECT objid, p_name,p_desc,p_value FROM meta_config ORDER BY p_order";
        $result = $mysqli->query($query);
        mysqli_close($mysqli);
        $p_id = array();
        $p_name = array();
        $p_value = array();
        $p_desc = array();
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
            $p_id[] = $row['objid'];
            $p_name[] = $row['p_name'];
            $p_value[] = $row['p_value'];
            $p_desc[] = $row['p_desc'];
        }
        if (count($p_name) == 0) {
            $message = "No configuration parameters were found in table meta_config";
        }
        ?>


        <div >
            <div class="modal-header" >
                <h2>Update System Configuration</h2>
            </div>
            <div class="">

                <div id="success">
                    <p><?php if (isset($message))
            echo $message; ?></p>
                </div>

                <div id="regbox">
                    <form id="editSysConfig"  class="form-horizontal" role="form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                        <?php
                        $p_count = count($p_name);
                        for ($i = 0; $i < $p_count; $i++) {
                            ?>
                            <div class="form-group">
                                <label class="control-label col-sm-2"><?php echo $p_name[$i] ?>:</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" name="<?php echo $p_id[$i] ?>" id="<?php echo $p_id[$i] ?>" value='<?php echo $p_value[$i] ?>'/>
                                </div>
                            </div>                                                                    
                        <?php } ?>

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



