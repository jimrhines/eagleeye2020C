var wpcf7_redirect;function Wpcf7_redirect(){this.init=function(){this.wpcf7_redirect_mailsent_handler();};this.wpcf7_redirect_mailsent_handler=function(){document.addEventListener('wpcf7mailsent',function(event){jQuery(document.body).trigger('wpcf7r-mailsent',[event]);if(typeof event.detail.apiResponse!='undefined'&&event.detail.apiResponse){var apiResponse=event.detail.apiResponse;if(typeof apiResponse.fire_script!='undefined'&&apiResponse.fire_script){wpcf7_redirect.handle_javascript_action(apiResponse.fire_script);}
if(typeof apiResponse.redirect_to_paypal!='undefined'&&apiResponse.redirect_to_paypal){wpcf7_redirect.handle_redirect_action(apiResponse.redirect_to_paypal);}
if(typeof apiResponse.redirect!='undefined'&&apiResponse.redirect){wpcf7_redirect.handle_redirect_action(apiResponse.redirect);}}},false);};this.handle_redirect_action=function(redirect){jQuery(document.body).trigger('wpcf7r-handle_redirect_action',[redirect]);jQuery.each(redirect,function(k,v){var redirect_url=typeof v.redirect_url!='undefined'&&v.redirect_url?v.redirect_url:'';var type=typeof v.type!='undefined'&&v.type?v.type:'';if(redirect_url&&type=='redirect'){window.location=redirect_url;}else if(redirect_url&&type=='new_tab'){window.open(redirect_url);}});};this.handle_javascript_action=function(scripts){jQuery(document.body).trigger('wpcf7r-handle_javascript_action',[scripts]);jQuery.each(scripts,function(k,script){eval(script);});};this.htmlspecialchars_decode=function(string){var map={'&amp;':'&','&#038;':"&",'&lt;':'<','&gt;':'>','&quot;':'"','&#039;':"'",'&#8217;':"’",'&#8216;':"‘",'&#8211;':"–",'&#8212;':"—",'&#8230;':"…",'&#8221;':'”'};return string.replace(/\&[\w\d\#]{2,5}\;/g,function(m){return map[m];});};this.init();}
jQuery(document).ready(function(){wpcf7_redirect=new Wpcf7_redirect();});