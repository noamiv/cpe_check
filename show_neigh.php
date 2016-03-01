<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
require_once("models/config.php");
if (!isset($loggedInUser)) {
    header('Location: login.php');
    exit();
}

/* NOAM: what if bs_id does not exists?  */
if (isset($_GET["id"])) {
    $in_bsid = $_GET['id'];
}
?>


<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>WIN RCM Fixed CPES HO Demo</title>
        <link href="boxTypes.css" rel="stylesheet" type="text/css" />  
        <link rel="stylesheet" href="css/jquery-ui.css"/>
        <script src="js/jquery-1.10.2.js"></script>
        <script src="js/jquery-ui.js"></script>
        <style>            
            body { 
                top:0px;
                padding-top:0;
                margin:auto; position:relative;
                width: 80%;
                height:100%;
                background-color: #133783; text-align: center;
            }
            table { table-layout:fixed; text-align: center; }
            article {display: block; text-align: center;}
            #response {
                padding:10px;
                background-color:#9F9;
                border:2px solid #396;
                margin-bottom:20px;
                font-size: 200%;
                font-weight: bolder;
            }

            h1 {color: white; font-size: 4.5em;text-decoration: underline;
                font-weight: 300; margin-bottom: 20px; text-align: center; }

            .myButton {
                margin-top: 10%;
                -moz-box-shadow: 0px 1px 0px 0px #f0f7fa;
                -webkit-box-shadow: 0px 1px 0px 0px #f0f7fa;
                box-shadow: 0px 1px 0px 0px #f0f7fa;
                background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #33bdef), color-stop(1, #019ad2));
                background:-moz-linear-gradient(top, #33bdef 5%, #019ad2 100%);
                background:-webkit-linear-gradient(top, #33bdef 5%, #019ad2 100%);
                background:-o-linear-gradient(top, #33bdef 5%, #019ad2 100%);
                background:-ms-linear-gradient(top, #33bdef 5%, #019ad2 100%);
                background:linear-gradient(to bottom, #33bdef 5%, #019ad2 100%);
                filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#33bdef', endColorstr='#019ad2',GradientType=0);
                background-color:#33bdef;
                -moz-border-radius:6px;
                -webkit-border-radius:6px;
                border-radius:6px;
                border:1px solid #057fd0;
                display:inline-block;
                cursor:pointer;
                color:#ffffff;
                font-family:Arial;
                font-size:16px;
                font-weight:bold;
                padding:9px 39px;
                text-decoration:none;
                text-shadow:0px -1px 0px #5b6178;
            }
            .myButton:hover {
                background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #019ad2), color-stop(1, #33bdef));
                background:-moz-linear-gradient(top, #019ad2 5%, #33bdef 100%);
                background:-webkit-linear-gradient(top, #019ad2 5%, #33bdef 100%);
                background:-o-linear-gradient(top, #019ad2 5%, #33bdef 100%);
                background:-ms-linear-gradient(top, #019ad2 5%, #33bdef 100%);
                background:linear-gradient(to bottom, #019ad2 5%, #33bdef 100%);
                filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#019ad2', endColorstr='#33bdef',GradientType=0);
                background-color:#019ad2;
            }
            .myButton:active {
                position:relative;
                top:1px;
            }


            .onDrag {
                color: red;
                font-weight: bold;
            }
            .afterdrop {
                background-color:whitesmoke;
            }
        </style>
        <script type="text/javascript">           
            
            $(document).ready(function(){ 	
                function slideout(){
                    setTimeout(function(){
                        $("#response").slideUp("slow", function () {
                        });
    
                    }, 4000);
                    /*
                    setTimeout(function(){
                        window.location.reload();
                    }, 4100);
                     */
                }
  
                $("#response").hide();   
                
                $("#mainTable").width($(window).width() * 0.8);
  
                $(function() {
                
                
                    $( "#list ol li" ).draggable( {axis: "x", 
                        start: function( event, ui ) {
                            $(this).addClass( "onDrag" );
                        }
                
                    });
                    $( "#list td" ).droppable({                                        
                
                                        
                        drop: function( event, ui ) {
                            if ($(this).attr('id') != ui.draggable.attr('parent'))
                            {
                                $( this )
                                .addClass( "afterdrop" )
                                
                                ui.draggable.draggable('option','revert',false);                            
                                ui.draggable.removeClass( "box14 onDrag" );
                                ui.draggable.addClass( "box10" );
                                                        
                                var order = {
                                    bs : $(this).attr('id'),
                                    ss : ui.draggable.attr('id'),  
                                    bs_name: $(this).attr('name'),
                                    ss_name: ui.draggable.attr('name')
                                };
                            
                                $.post("updateList.php", order, function(theResponse){
                                    $("#response").html(theResponse);
                                    $("#response").slideDown('slow');
                                    slideout();                                                              
                                });                             
                            }else {
                                ui.draggable.removeClass( "onDrag" );
                                ui.draggable.draggable('option','revert',true);  
                            } ;                                                     
                        }	
                    });
                                       
                });
                
             

            });	
        </script>
    </head>
    <body>

        <div id="list">
            <div id="response"> </div>   
            <article  itemscope="itemscope" >

                <div class="">
                    <h1 id="title"> My Neighborhood  Watch  </h1>      
                    <!--.row-->

                    <table id="mainTable" class="boxTable">
                        <tr>
                            <?php
                            include("db_connect.php");
                            $query = "SELECT bs.objid, bs.name FROM bs where bs.objid=$in_bsid OR bs.objid in (SELECT neigh.neigh_bs FROM neigh_bs_m2m neigh WHERE neigh.bs_objid=$in_bsid)";                                                         
                            $result = $mysqli->query($query);
                            while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {

                                $bs_id[] = $row['objid'];
                                $bs_name[] = $row['name'];
                                ?>                  
                                <td><?php echo $row['name'] ?></td>
                            <?php } ?>   
                        </tr> 
                        <tr>                         
                            <?php
                            //Get all the CPEs                                        
                            foreach ($bs_id as $id => $bs_objid) {
                                ?>
                                <td name="<?php echo $bs_name[$id] ?>" id="<?php echo $bs_objid ?>"><ol  class="column">
                                        <?php
                                        $query = "SELECT objid, name FROM cpe WHERE cpe2bs=$bs_objid";
                                        $result = $mysqli->query($query);
                                        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                                            $name = stripslashes($row['name']);
                                            $ss_id = stripslashes($row['objid'])
                                            ?>

                                            <li id="<?php echo $ss_id ?>" name="<?php echo $name ?>" parent="<?php echo $bs_objid ?>" class="box14"><span>  <?php echo $name; ?> </span></li>          
                                        <?php } ?>     
                                    </ol></td>                                        
                            <?php } ?>     
                        </tr> 
                    </table>            
                </div>          
                <!--/.container -->

                <form><input type="button" class="myButton" value="Validate" onClick="window.location.reload()"></form> 
            </article>
        </div>
        <?php mysqli_close($mysqli); ?>
    </body>
</html>
