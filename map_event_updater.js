var lastEventObjid =0;   
var intrevalTimer = 5000; // 5 sec by default
 
function updateObjets(data)
{
    var dataObj = JSON.parse(data);
 
    //keep the last objid which was read, in order not to read these event again
    if (dataObj[0]){
        lastEventObjid = dataObj[0];
    } 
 
    for (var i = 1, l = Object.keys(dataObj).length; i < l; i++) 
    {
       // console.log(dataObj[i]); 
        eval(dataObj[i]);    
    }
}

 
var fetchData = function () {
    $.ajax("getEvents.php",
    {
        type: "POST",
        data:  {
            id : lastEventObjid
        },
        dataType: 'html',
        success: function (data, textStatus, jqXHR) {
            updateObjets(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);          
        }
    });

    //call this again in 5 seconds time
    updateTimer = setTimeout(function () {
        fetchData();
    }, intrevalTimer);
};




function updateConfig(data)
{
    var configObj = JSON.parse(data);
 
    //keep the last objid which was read, in order not to read these event again
    if (configObj.maxEventIndex) {
        lastEventObjid = configObj.maxEventIndex ; 
    }
    
    if (configObj.map_refresh_interval){
        intrevalTimer = configObj.map_refresh_interval;
    }
}



var onLoadFunction = function() {
 
     $.ajax("getEvents.php",
    {
        type: "POST",
        data:  {
            funcName : 'load'
        },
        dataType: 'html',
        async:false,  //very imporatnt! to ensure the answer is returned before the first call to 'fetchData'
        success: function (data, textStatus, jqXHR) {
            updateConfig(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);          
        }
    });
   
   fetchData(); //must actually call the function!
}
onLoadFunction();       
