if( !window.jQuery ) {
  jq=document.createElement('SCRIPT');
  jq.type='text/javascript';
  jq.src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js';
  document.getElementsByTagName('head')[0].appendChild(jq);
}
var interval;
var intervalFn = function() {
        if( window.jQuery ) {
                if( jQuery && window.$ && jQuery != $ ) {
                    jQuery.noConflict();
                }
                jQuery(document).ready(function($){
                    if( plink.url ) {
                    	if( plink.success ) {
                    		window.location.href = plink.url;
                    	} else {
                    		alert("Error");
                    	}
                    } else {
                    	plink.url = window.location.href;
                    	$.ajax({
                    		url:plink.home+"stash.php",
                    		data:plink,
                    		dataType:"jsonp",
                    		success:function(data) {
                    			if( data.success ) {
                        			$('body').append('<div id="plinkStatus" style="z-index:1000;display:none;position:fixed;top:0;left:0;text-align:center;background-color:rgba(0,0,0,.75);color:white;font-weight:bold;width:100%;"><p>Successfully Stashed URL with <a style="color:yellow;" href="'+plink.home+'">Plink</a>!</p></div>');
                        			var timeOut, status = $('#plinkStatus').click(function(e){
                        				clearTimeout(timeOut);
                        				$(this).slideUp(function(){$(this).remove();});
                        			}).slideDown();
                        			timeOut = setTimeout(function(){
                        				status.slideUp(function(){$(this).remove();});
                        			},2000);
                    			} else {
                    				alert("Error");
                    			}
                    		}
                    	});
                    }
                });
                clearInterval( interval );
        }
}
interval = setInterval( intervalFn, 100 ); 