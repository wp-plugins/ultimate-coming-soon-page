// usage: log('inside coolFunc', this, arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function(){
  log.history = log.history || [];   // store logs to an array for reference
  log.history.push(arguments);
  if(this.console) {
      arguments.callee = arguments.callee.caller;
      console.log( Array.prototype.slice.call(arguments) );
  }
};
// make it safe to use console.log always
(function(b){function c(){}for(var d="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),a;a=d.pop();)b[a]=b[a]||c})(window.console=window.console||{});


// Coming Soon Notify Me
jQuery.ajaxSetup ({
    // Disable caching of AJAX responses */
    cache: false
});
jQuery(document).ready(function($){
    var msgdefault = seedprod_err_msg.msgdefault;
    var msg500 = seedprod_err_msg.msg500;
    var msg400 = seedprod_err_msg.msg400;
    var msg200 = seedprod_err_msg.msg200;
    $('#notify').submit(function() {
        
        url = $("#notify-url").val();
        action = 'seedprod_mailinglist_callback';
        email = $("#notify-email").val();
        noitfy_nonce = $("#noitfy-nonce").val();
        payload = '?action='+action+'&email='+email+'&noitfy_nonce='+noitfy_nonce;
        jQuery.get(url+payload, function(data,textStatus,xhr) {
          switch(data)
          {
          case '500':
            msg = msg500;
            break;
          case '400':
            msg = msg400;
            break;
          case '200':
            msg = msg200;
            $("#notify-incentive").show();
          }
          $("#notify-email").val(msg);
        });
        return false;
    });
    $('#notify-email').focus(function() {  
        if (this.value == msg500 || this.value == msg400 || this.value == msg200 || this.value == msgdefault){
        	this.value = '';
    	}
    });
    $(document).ajaxStart(function(){ 
      $('#ajax-indicator').show(); 
    }).ajaxStop(function(){ 
      $('#ajax-indicator').hide();
    });
    
});