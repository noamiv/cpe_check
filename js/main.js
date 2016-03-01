
    function openGenOverlay() {
        var triggerBttn = document.getElementById( 'js-trigger-overlay' ),
        overlay = document.querySelector( 'div.overlay' ),
        closeBttn = overlay.querySelector( 'button.close' );
        transEndEventNames = {
            'WebkitTransition': 'webkitTransitionEnd',
            'MozTransition': 'transitionend',
            'OTransition': 'oTransitionEnd',
            'msTransition': 'MSTransitionEnd',
            'transition': 'transitionend'
        },
        transEndEventName = transEndEventNames[ Modernizr.prefixed( 'transition' ) ],
        support = {
            transitions : Modernizr.csstransitions
        };                                 

        function toggleOverlay() {
            if( classie.has( overlay, 'open' ) ) {
                classie.remove( overlay, 'open' );
                classie.add( overlay, 'close' );
                var onEndTransitionFn = function( ev ) {
                    if( support.transitions ) {
                        if( ev.propertyName !== 'visibility' ) return;
                        this.removeEventListener( transEndEventName, onEndTransitionFn );
                    }
                    classie.remove( overlay, 'close' );
                };
                if( support.transitions ) {
                    overlay.addEventListener( transEndEventName, onEndTransitionFn );
                }
                else {
                    onEndTransitionFn();
                }
                /*location.reload(); NOAM- this will reload the page on close. I prefer to do it nicer and just update the BS ballon*/
                
                 var content = document.getElementById('popup-content');
                  content.innerHTML = "NOAM";
                                               
            }
            else if( !classie.has( overlay, 'close' ) ) {
                classie.add( overlay, 'open' );                
            }            
        }

        toggleOverlay() ;
        closeBttn.addEventListener( 'click', toggleOverlay );
    } 
    
    
    

$(document).ready(function() {
    "use-strict";
    
      
    

    $('#nav-new-bs').click(function(e) 
    { 
        $('#overlayId').html(
            '<button type="button" class="close">Close</button>'+
            '<iframe id="editBs" src="new_bs.php" allowfullscreen="true" sandbox="allow-scripts allow-pointer-lock allow-same-origin allow-popups allow-forms" allowtransparency="true" class="result-iframe"></iframe>'
            );           
        openGenOverlay();
    });            


    $('#nav-new-user').click(function(e) 
    { 
        $('#overlayId').html(
            '<button type="button" class="close">Close</button>'+
            '<iframe id="editBs" src="register.php" allowfullscreen="true" sandbox="allow-scripts allow-pointer-lock allow-same-origin allow-popups allow-forms" allowtransparency="true" class="result-iframe"></iframe>'
                        
            );  
        openGenOverlay();
    });
                     
        
    $('a[href*=#]:not([href=#])').click(function() {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top
                }, 1000);
                return false;
            }
        }
    });    
    
     $("#nav-sys").on("click", "a", null, function () {
         $("#nav-sys").collapse('hide');
     });

});






