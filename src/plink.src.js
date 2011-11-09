var c = console;
if( !c ) {
	c = {};
	c.log = function( msg ){};
}
if( !window.jQuery ) {
	jq=document.createElement('SCRIPT');
  jq.type='text/javascript';
  jq.src='https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js';
  document.getElementsByTagName('head')[0].appendChild(jq);
}
var interval;
var intervalFn = function() {
	if( window.jQuery ) {
		if( jQuery && window.$ && jQuery != $ ) {
			jQuery.noConflict();
		}
		jQuery(document).ready(function($){
			alert('bookmarklet loaded');	
		});
		clearInterval( interval );
	}
}
interval = setInterval( intervalFn, 100 ); 
