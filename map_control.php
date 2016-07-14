<script>            
    var draw; // global so we can remove it later      

    function switchLayerBS()
    {               
        var checkedLayer = $('#layerswitcher input[name=layerBS]:checked').val();
        if (checkedLayer)
        {
            layers[1].setVisible(1);
        }else {
            layers[1].setVisible(0);
        }
    }


    function switchLayerCPE()
    {               
        var checkedLayer = $('#layerswitcher input[name=layerCPE]:checked').val();
        if (checkedLayer)
        {
            layers[2].setVisible(1);
        }else {
            layers[2].setVisible(0);
        }
    }

    $("#layerswitcher input[name=layerBS]").change(function() { switchLayerBS() } );    
    $("#layerswitcher input[name=layerCPE]").change(function() { switchLayerCPE() } );
    /**
     * Elements that make up the popup.
     */
    var container = document.getElementById('popup');
    var content = document.getElementById('popup-content');
    var closer = document.getElementById('popup-closer');


    /**
     * Add a click handler to hide the popup.
     * @return {boolean} Don't follow the href.
     */
    closer.onclick = function() {
        overlay.setPosition(undefined);
        closer.blur();
        return false;
    };

    /**
     * Create an overlay to anchor the popup to the map.
     */
    var overlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
        element: container,
        autoPan: true,
        autoPanAnimation: {
            duration: 250
        }
    }));
            
            

<?php
include("db_connect.php");

//Get all the Base stations 
$query = "SELECT bs.*, count(cpe.objid) AS cpes FROM bs left outer join cpe on  cpe.cpe2bs=bs.objid GROUP BY bs.objid";
$bs_result = $mysqli->query($query);
mysqli_close($mysqli);
$allFeaturesBS = array();
while ($row = mysqli_fetch_array($bs_result, MYSQL_ASSOC)) {

    $id = $row['objid'];
    $bs_name = $row['name'];
    $bs_lat = $row['location_lat'];
    $bs_long = $row['location_long'];
    $ant_direction = ($row['ant_direction'] / 360) * 6.28;
    $ip = $row['ip'];
    $cpes_num = $row['cpes'];

    if (!(empty($bs_long) || empty($bs_lat))) {
        ?>   
                    var BsFeature_<?php echo $id ?> = new ol.Feature({
                        geometry: new ol.geom.Point(ol.proj.transform([<?php echo "$bs_long,$bs_lat" ?>], 'EPSG:4326', 'EPSG:3857')),
                        name: <?php echo "'$bs_name'" ?> ,       
                        id: <?php echo "'$id'" ?>,
                        cpes_num: <?php echo "'$cpes_num'" ?>,   
                        ip: <?php echo "'$ip'" ?>,  
                        type: <?php echo "'bs'" ?> 
                    });

                    var BsStyle_<?php echo $id ?> = new ol.style.Style({
                        image: new ol.style.Icon({
                            anchor: [0.5, 45],
                            anchorXUnits: 'fraction',
                            anchorYUnits: 'pixels',
                            opacity: 0.75,
                            rotation: <?php echo $ant_direction ?>,
                            src: 'v3.10.1/examples/data/geolocation_marker_heading.png'                       
                        })        ,
                        text: new ol.style.Text({
                            text: <?php echo "'$bs_name'" ?> ,
                            scale: 1.3,
                            fill: new ol.style.Fill({
                                color: '#000000'
                            })
                        })
                    });

                    BsFeature_<?php echo $id ?>.setStyle(BsStyle_<?php echo $id ?>);

        <?php
        $allFeaturesBS[] = "BsFeature_$id";
    }
}

//Get all the CPEs
?>

var fillGreen = new ol.style.Fill({
   color: 'rgba(13,236,35,0.7)'
 });
 var stroke = new ol.style.Stroke({
   color: '#3399CC',
   width: 1.25
 });


<?php
include("db_connect.php");
$query = "SELECT * FROM cpe";
$ss_result = $mysqli->query($query);
mysqli_close($mysqli);

while ($row = mysqli_fetch_array($ss_result, MYSQL_ASSOC)) {
    $id = $row['objid'];
    $ss_name = $row['name'];
    $ss_lat = $row['location_lat'];
    $ss_long = $row['location_long'];
    $ant_direction = ($row['ant_direction'] / 360) * 6.28;
    $ip = $row['ip'];

    if (!(empty($ss_long) || empty($ss_lat))) {
        ?>   
                    var SsFeature_<?php echo $id ?> = new ol.Feature({
                        geometry: new ol.geom.Point(ol.proj.transform([<?php echo "$ss_long,$ss_lat" ?>], 'EPSG:4326', 'EPSG:3857')),
                        name: <?php echo "'$ss_name'" ?> ,       
                        id: <?php echo "'$id'" ?>, 
                        ip: <?php echo "'$ip'" ?>,  
                        type: <?php echo "'ss'" ?>
                    });

                     var SsStyle_<?php echo $id ?> = new ol.style.Style({
                       image: new ol.style.Circle({
                               fill: fillGreen,
                               stroke: stroke,
                               radius: 5
                        }),            
                    });

                    SsFeature_<?php echo $id ?>.setStyle(SsStyle_<?php echo $id ?>);

        <?php
        $allFeaturesSS[] = "SsFeature_$id";
    }
}
?>
    
   
    var vectorSourceBS = new ol.source.Vector({
        features: [ <?php echo implode(',', $allFeaturesBS); ?> ]
    });
    var vectorLayerBS = new ol.layer.Vector({
        source: vectorSourceBS
    });
    var vectorSourceSS = new ol.source.Vector({
        features: [ <?php echo implode(',', $allFeaturesSS); ?> ]
    });
    var vectorLayerSS = new ol.layer.Vector({
        source: vectorSourceSS
    });    

    var layers = [];        
    layers[1] = vectorLayerBS;
    layers[2] = vectorLayerSS;
    //layers[1] = new ol.layer.Vector({  source: vectorNamesSource  });
    
    //Gerenet tiles using Maperitive on Windows PC: EXE can be found under backup\Maperitive\Maperitive.exe
    var PrivateLayer = new ol.layer.Tile({
        source: new ol.source.OSM({                
            url: 'tiles/{z}/{x}/{y}.png'
        })
    });



    var map = new ol.Map({                
        layers: [PrivateLayer,layers[1],layers[2]]     ,
        controls: ol.control.defaults().extend([
            new ol.control.ScaleLine({ units:'metric' }),            
            new ol.control.ZoomSlider(),
            new ol.control.FullScreen()
        ]),  
        interactions: ol.interaction.defaults().extend([
            new ol.interaction.DragRotateAndZoom()
        ]),                    
        overlays: [overlay],
        target: 'map', 
        logo : false,
        view: new ol.View({                  
            projection: 'EPSG:3857',
            center: [0,0],
            zoom: 17 
        })
    });
    
    
    //Set the map focus around BS #1
    var feature = vectorSourceBS.getFeatures()[0];
    if (feature) {
        var polygon = feature.getGeometry();
        var size = /** @type {ol.Size} */ (map.getSize());
        map.getView().fit(
        polygon,
        size,
        /*opt_options*/
        {
            //      padding: [170, 50, 30, 150],        
            nearest: true,
            minResolution: 9
        } );
    }
                                       
    /**
     * Add a click handler to the map to render the popup.
     */
    map.on('singleclick', function(evt) {
        var coordinate = evt.coordinate;
        var myItem = map.forEachFeatureAtPixel(evt.pixel,
        function(feature, layer) {
            return feature;
        });
        
        /*click was on empty area of the map*/
        if (! myItem)
        {
            closer.click();  /*close an open content baloon - if opened*/
            return;
        }
       
        //if it does not have type it is a new SS :
        if (! myItem.get('type')) {
            //if the feature is a new SS    
            content.innerHTML = '<h3>New SS</h3>' +
                '<a id="remove_ss" >Remove</a> || '+                
                '<a id="new_ss" >Create</a>';                        
            overlay.setPosition(coordinate);    
        
            //Get out of new SS mode:
            map.removeInteraction(draw);
            var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
            $('#new_ss').on('click',(function(){                     
                $('#overlayId').html(                      
                '<button type="button" class="close">Close</button>'+
                    '<iframe id="newSs" src="new_ss.php?long=' + lonlat[0]+ '&lat=' +lonlat[1]+  '" allowfullscreen="true" sandbox="allow-scripts allow-pointer-lock allow-same-origin allow-popups allow-forms" allowtransparency="true" class="result-iframe"></iframe>'
            );  
                openGenOverlay();
            }));
                

            $('#remove_ss').on('click',(function(){                
                new_ss_source.clear();
                overlay.setPosition(undefined);
                closer.blur();
            }));
            
        }else if (myItem.get('type') == 'bs') {     
            content.innerHTML = '<h3>'+ myItem.get('name')+'</h3><br/><h4>Number of CPEs: ' + myItem.get('cpes_num') + '<h4>' +
                '<br/> <a id="show_nei" >My Neighborhood </a>'+
                '<br/> <a href="'+ 'https://'+ myItem.get('ip')+'" target="_blank">Open WEB UI</a>' +
                '<br/> <a id="edit_bs" >Edit</a>';
                        
            overlay.setPosition(coordinate);                                    
  
            /*Open the edit BS page */
            $('#edit_bs').on('click',(function(){ 
                  
                $('#overlayId').html(
                '<button type="button" class="close">Close</button>'+
                    '<iframe id="editBs" src="edit_bs.php?id=' + myItem.get('id') +  '" allowfullscreen="true" sandbox="allow-scripts allow-pointer-lock allow-same-origin allow-popups allow-forms" allowtransparency="true" class="result-iframe"></iframe>'
            );  
                
             openGenOverlay();

            }));
                        
            $('#show_nei').on('click',(function(){
                                
                $('#overlayId').html(
                '<button type="button" class="close">Close</button>'+
                    '<iframe id="doHo" src="show_neigh.php?id=' + myItem.get('id') +   '" allowfullscreen="true" sandbox="allow-scripts allow-pointer-lock allow-same-origin allow-popups allow-forms" allowtransparency="true" class="result-iframe"></iframe>'
            ); 
                openGenOverlay();
            }));
            
            
            /*Send AJAX commnad to get al the CPEs of this BS*/
            var ajaxinfo = {
                bs_id : myItem.get('id'),                                    
                bs_name: myItem.get('name')                                    
            };
            $('#cpes-info').html("No Info");   
            $.post("getCpeInfo.php", ajaxinfo, function(theResponse){                                   
                $('#bs-info').html('Base Station: ' + myItem.get('name'));     
                $('#cpes-info').html(theResponse);     
            });                             
                    
        }else if (myItem.get('type') == 'ss'){ /*if it is a CPE*/

            content.innerHTML = '<h3>'+ myItem.get('name') +                
                '<br/> <a href="'+ 'https://'+ myItem.get('ip')+'" target="_blank">Open WEB UI</a>' +
                '<br/> <a id="edit_ss" >Edit</a>';                       
            overlay.setPosition(coordinate);  
            
                        /*Open the edit BS page */
            $('#edit_ss').on('click',(function(){ 
                  
                $('#overlayId').html(
                '<button type="button" class="close">Close</button>'+
                    '<iframe id="editSs" src="edit_ss.php?id=' + myItem.get('id') +  '" allowfullscreen="true" sandbox="allow-scripts allow-pointer-lock allow-same-origin allow-popups allow-forms" allowtransparency="true" class="result-iframe"></iframe>'
            );  
                
             openGenOverlay();
            }));  
        }
    });
    // change mouse cursor when over marker
    
    map.on('pointermove', function(e) {
        var pixel = map.getEventPixel(e.originalEvent);
        var hit = map.hasFeatureAtPixel(pixel);
        map.getTargetElement().style.cursor = hit ? 'pointer' : '';
    });
        
    //////////////////////////////////////////////////////
    // drug and drop new SS as a point
    //////////////////////////////////////////////////////
    var features = new ol.Collection();
    var new_ss_source = new ol.source.Vector({features: features});
    var featureOverlay = new ol.layer.Vector({
        source: new_ss_source,
        style: new ol.style.Style({
            fill: new ol.style.Fill({
                color: 'rgba(255, 255, 255, 0.2)'
            }),
            stroke: new ol.style.Stroke({
                color: '#ffcc33',
                width: 2
            }),
            image: new ol.style.Circle({
                radius: 7,
                fill: new ol.style.Fill({
                    color: '#ffcc33'
                })
            })
        })
    });
    featureOverlay.setMap(map);
    
    /*allow the new SS point to be moved on th map*/
    var modify = new ol.interaction.Modify({
        features: features        
    });
    map.addInteraction(modify);  
      
    function addInteraction() {
        draw = new ol.interaction.Draw({
            features: features,
            type: /** @type {ol.geom.GeometryType} */ "Point"
        });
        map.addInteraction(draw);
    }
      


       
</script>