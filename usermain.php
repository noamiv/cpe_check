<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<!--<![endif]-->


 <!-- Make sure the user is login. If not redirect to login page -->
<?php
require_once("models/config.php");
if (!isset($loggedInUser)) {
    header('Location: login.php');
    exit();
}
?>

<html>    
    <head>
        <meta charset="utf-8">
        <title>CPE MNG</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- This JS are required for the MAP -->
        <script src="js/jquery-2.1.4.min.js"></script>
        <script src="js/jquery-1.10.2.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="./v3.10.1/build/ol.js"></script>

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

         <!-- NOAM TODO: too many CSS.. needs to be reduced -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/animate.css">        
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/responsive.css">
        <link rel="stylesheet" href="./v3.10.1/examples/overviewmap-custom.css">
        <link rel="stylesheet" href="./v3.10.1/examples/popup.css">
        <link rel="stylesheet" href="./css/mainPage.css">

        <link rel="stylesheet" href="v3.10.1/css/ol.css" type="text/css">        
        <link rel="stylesheet" href="./css/framewarp.css" >

        <link rel="stylesheet" href="css/ol2/normalize.css">
        <link rel="stylesheet" href="css/ol2/style.css">        

        <style>
            #map {
                position: relative;
            }
            #toolbox       { position:absolute; top:80px; right:8px; padding:3px; border-radius:4px; color:#fff; background: rgba(255, 255, 255, 0.4); z-index:100; }
            #layerswitcher { margin:0; padding:10px; border-radius:4px; background:rgba(0, 60, 136, 0.5); list-style-type:none; } 
        </style>
    </head>

    <body id="top">
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade ybrowser</a> to improve your experience.</p>
        <![endif]-->

        <header>
            <!-- NAVIGATION -->
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header page-scroll">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                        <!-- LOGO -->
                        <a class="navbar-brand page-scroll" href="http://w3.siemens.com/mcms/industrial-communication/en/rugged-communication/Documents/webfeature_rx1400/index.html#!/de/" target="_blank">
                            <img src="img/logo.png" alt="">
                        </a>
                        <!-- END LOGO -->

                    </div>

                    <!-- TOGGLE NAV -->
                    <div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
                        <ul class="menu nav navbar-nav navbar-right">
                            <li class="hidden">
                                <a href="#page-top"></a>
                            </li>
                            <li><a class="page-scroll" href="#top">Map</a>
                            </li>
                            <li><a class="page-scroll" href="#cpes">CPEs</a> </li>
                            <li class="dropdown nav_menu_sub" id="nav-sys"> <a class="page-scroll">System Setup</a> 
                                <ul class="dropdown-menu nav">
                                    <li><a class="page-scroll" id="nav-new-bs"   href="#">Add BS</a></li>
                                    <li><a class="page-scroll" id="nav-new-user" href="#">Add User</a></li>
                                    <li><a class="page-scroll" id="nav-all-users" href="#">All Users</a></li>
                                    <li><a class="page-scroll" id="nav-all-bs"   href="#">All BS</a></li>
                                    <li><a class="page-scroll" id="nav-config"   href="#">Configuration</a></li>
                                </ul>
                            </li> 
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                    <!-- TOGGLE NAV                                        
                    -->

                    <div id="overlayId" class="overlay overlay-effect"> </div>                    

                </div>
                <!-- /.container -->
            </nav>
            <!-- END NAVIGATION -->
        </header>

        <!-- HOME -->
        <div id="home-area">            

            <div class="row-fluid">
                <div class="span12">
                    <div id="map" class="map"" ></div>
                    <div id="toolbox">
                        <ul id="layerswitcher">
                            <li><label><input type="checkbox" name="layerBS" value="1" checked> Base Stations</label></li>                            
                            <li><label><input type="checkbox" name="layerCPE" value="0"> CPEs</label></li>                            
                        </ul>
                    </div>
                    <div id="popup" class="ol-popup">
                        <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                        <div id="popup-content"></div>
                    </div>
                </div>
            </div> 

        </div>
        <!-- END HOME -->

        <!-- SERVICES -->
        <section id="cpes" class="section text-center">
            <div class="container">
                <h2 id="bs-info" class="section-title "> </h2>      
                <!--.row-->
                <div id="cpes-info" class="cpe_table">No CPEs</div>
            </div>            
            <!--/.container -->
        </section>
        <!-- END SERVICES -->

        <!-- FOOTER -->
        <footer>


        </footer>
        <!-- END FOOTER -->
        <script src="js/main.js"></script>   
         <!-- Map control holds the JS and PHP for the map -->
        <?php require_once("map_control.php"); ?>
          <!-- END FOOTER -->
        <script src="./js/framewarp.js"></script>  
        <script src="js/plugins.js"></script>

        <script src='js/classie.js'></script>
        <script src='js/modernizr.custom.js'></script>       
    </body>
</html>