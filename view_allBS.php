<?php
require_once("models/config.php");

/*
 * Check for login
 */
if (!isset($loggedInUser)) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />        
        <script src="js/jquery-2.1.4.min.js"></script>
        <script src="assets/js/jquery.validate.min.js"></script>
        <link rel="stylesheet" href="assets/css/forms.css"/>
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/font-awesome.min.css"/>
        <link rel="stylesheet" href="./css/mainPage.css"/>
    </head>
    <body>

        <?php
        include("db_connect.php");


        $query = "SELECT bs.*, count(cpe.objid) AS cpes FROM bs left outer join cpe on  cpe.cpe2bs=bs.objid GROUP BY bs.objid ";
        $result = $mysqli->query($query);
        mysqli_close($mysqli);
        $bs = array();
        $i = 0;
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {

            $bs[$i]['id'] = $row['objid'];
            $bs[$i]['name'] = $row['name'];
            $bs[$i]['ip'] = $row['ip'];
            $bs[$i]['status'] = 'Connected';
            $bs[$i]['count'] = $row['cpes'];
            $bs[$i]['name'] = $row['name'];            
            //$bs[]['lat'] = $row['location_lat'];
            //$bs[]['long'] = $row['location_long'];
            //$bs[]['ant_direction'] = $row['ant_direction'];
            $i++;
        }
        if ($i === 0) {
            $message = "No Base Station could not be found ";
        }
        ?>

        <div >
            <div class="modal-header">
                <h2 id="head2">View All Base Stations</h2>
            </div>
            <div id="regbox">
                <form id="viewBS"  class="form-horizontal" role="form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">

                    <?php
                    $table = '<table>';
                    $table .= '<tr><th> Name </th><th> Ip Address </th><th> Connected CPEs </th><th> Status </th></tr>';
                    foreach ($bs as $row) {
                        $table .= '<tr><td> <a id="' . $row['id'] . '" name="' . $row['name'] .'" href="">' .
                                $row['name'] .
                                '</a></td><td>' . $row['ip'] . '</td><td>' . $row['count'] . '</td><td>' . $row['status'] . '</td>    </tr>';
                    }
                    $table.='</table>';
                    ?>
                    <div id="all-bs-info" class="bs_table cpe_table">
                        <?php echo $table; ?>  
                    </div>

                    <section id="cpes" class="section text-center">                       
                        <div id="cpesTable" class="container">                              
                            <a id="tableHide" href=""> <img src="img/arrow_down_double.png" alt="arrow_down_double"/></a>
                            <div id="in-cpes-info" class="cpe_table">No CPEs</div>
                        </div>            
                        <!--/.container -->
                    </section>

                </form>
            </div>
        </div>         
    </body>

    <script>
       $('#cpesTable').hide();             
       
       /*Send AJAX commnad to get al the CPEs of the selected BS*/            
        function updateCpes(bsId)
        {
            var ajaxinfo = {
                bs_id : bsId                    
            };
            $('#in-cpes-info').html(bsId);   
            $.post("getCpeInfo.php", ajaxinfo, function(theResponse){                                                   
                $('#in-cpes-info').html(theResponse);     
            });  
        }
            
        jQuery( 'table a' )
        .click(function() {                   
            updateCpes( this.id ); 
            $('#head2').html("CPEs of BS name " + this.name);
            $('#all-bs-info').slideUp( "slow", function(){});                            
            $('#cpesTable').slideDown( "slow", function(){});                    
            return false;
        });
                        
        jQuery( '#tableHide' )
        .click(function() {                               
                    
            $('#cpesTable').slideUp( "slow", function(){});
            $('#all-bs-info').slideDown( "slow", function(){});
            $('#head2').html("View All Base Stations");
            return false;
        });
    </script>

</html>



