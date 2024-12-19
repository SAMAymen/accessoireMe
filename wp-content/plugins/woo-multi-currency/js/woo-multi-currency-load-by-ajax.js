'use strict';
(function(history){
    var pushState = history.pushState;
    history.pushState = function(state) {
        if (typeof history.onpushstate == "function") {
            history.onpushstate({state: state});
        }


        var apply_address = pushState.apply(history, arguments);
        //Custom code here
        wmc_change_url();
        return apply_address;

    }
})(window.history);
function wmc_change_url(){
    var site_url =window.location.href;
    jQuery('.wmc-currency a').each(function(){
        var wmc_url  = jQuery(this).attr('href');
        var wmc_currency = wmc_url.split(/wmc-currency=/gi);
        wmc_currency = wmc_currency[1];
        if(site_url.match(/\?/gi)){
            wmc_url = site_url + '&wmc-currency='+wmc_currency;
        }else{
            wmc_url = site_url + '?wmc-currency='+wmc_currency;
        }
        jQuery(this).attr('href',wmc_url);
    });

}