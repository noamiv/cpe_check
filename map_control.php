<script>            
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


$query = "SELECT bs.*, count(cpe.objid) AS cpes FROM bs left outer join cpe on  cpe.cpe2bs=bs.objid GROUP BY bs.objid";
$result = $mysqli->query($query);
mysqli_close($mysqli);
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {

    $id = $row['objid'];
    $bs_name = $row['name'];
    $bs_alt = $row['location_alt'];
    $bs_long = $row['location_long'];
    $ant_direction = ($row['ant_direction'] / 360) * 6.28;
    $ip =  $row['ip'];
    $cpes_num = $row['cpes'];

    if (!(empty($bs_long) || empty($bs_alt))) {
        ?>   
                    var BsFeature_<?php echo $id ?> = new ol.Feature({
                        geometry: new ol.geom.Point(ol.proj.transform([<?php echo "$bs_alt,$bs_long" ?>], 'EPSG:4326', 'EPSG:3857')),
                        name: <?php echo "'$bs_name'" ?> ,       
                        id: <?php echo "'$id'" ?>,
                        cpes_num: <?php echo "'$cpes_num'" ?>,   
                        ip: <?php echo "'$ip'" ?>   
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
        $allFeatures[] = "BsFeature_$id";
    }
}
?>
    
   
    

    var vectorSource = new ol.source.Vector({
        features: [ <?php echo implode(',', $allFeatures); ?> ]
    });
    

    var vectorLayer = new ol.layer.Vector({
        source: vectorSource
    });

    var layers = [];        
    layers[1] = vectorLayer;
    //layers[1] = new ol.layer.Vector({  source: vectorNamesSource  });
    
    //Gerenet tiles using Maperitive on Windows PC: EXE can be foudn under backup\Maperitive\Maperitive.exe
    var PrivateLayer = new ol.layer.Tile({
        source: new ol.source.OSM({                
            url: 'tiles/{z}/{x}/{y}.png'
        })
    });



    var map = new ol.Map({                
        layers: [PrivateLayer,layers[1]]     ,
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
    
    
       
    var feature = vectorSource.getFeatures()[0];
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
    }
    
);
                                       
    /**
     * Add a click handler to the map to render the popup.
     */
    map.on('singleclick', function(evt) {
        var coordinate = evt.coordinate;
        var myBs = map.forEachFeatureAtPixel(evt.pixel,
        function(feature, layer) {
            return feature;
        });              
        if (myBs) {     
            content.innerHTML = '<h3>'+ myBs.get('name')+'</h3><br/><h4>Number of CPEs: ' + myBs.get('cpes_num') + '<h4>' +
                '<br/> <a id="show_nei" >My Neighborhood </a>'+
                '<br/> <a href="'+ 'https://'+ myBs.get('ip')+'" target="_blank">Open WEB UI</a>' +
                '<br/> <a id="edit_bs" >Edit</a>';
                        
            overlay.setPosition(coordinate);                                    
  
            /*Open the edit BS page */
            $('#edit_bs').on('click',(function(){ 
                  
                $('#overlayId').html(
                '<button type="button" class="close">Close</button>'+
                    '<iframe id="editBs" src="edit_bs.php?id=' + myBs.get('id') +  '" allowfullscreen="true" sandbox="allow-scripts allow-pointer-lock allow-same-origin allow-popups allow-forms" allowtransparency="true" class="result-iframe"></iframe>'
            );  
                
                openGenOverlay();

            }));
                        
            $('#show_nei').on('click',(function(){
                                
                $('#overlayId').html(
                '<button type="button" class="close">Close</button>'+
                    '<iframe id="doHo" src="show_neigh.php?id=' + myBs.get('id') +   '" allowfullscreen="true" sandbox="allow-scripts allow-pointer-lock allow-same-origin allow-popups allow-forms" allowtransparency="true" class="result-iframe"></iframe>'
            ); 
                openGenOverlay();
            }));
            
            
            /*Send AJAX commnad to get al the CPEs of this BS*/
            var ajaxinfo = {
                bs_id : myBs.get('id'),                                    
                bs_name: myBs.get('name')                                    
            };
            $('#cpes-info').html("No Info");   
            $.post("getCpeInfo.php", ajaxinfo, function(theResponse){                                   
                $('#bs-info').html('Base Station: ' + myBs.get('name'));     
                $('#cpes-info').html(theResponse);     
            });                             
                    
        }
    });
    
    // change mouse cursor when over marker
    
    map.on('pointermove', function(e) {
        var pixel = map.getEventPixel(e.originalEvent);
        var hit = map.hasFeatureAtPixel(pixel);
        map.getTargetElement().style.cursor = hit ? 'pointer' : '';
    });
        

       
</script>