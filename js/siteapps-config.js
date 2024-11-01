jQuery(document).ready(function($) {
    
    jQuery(document).tooltip();

    jQuery('.advOptionsBtn').click(function(e){
        e.preventDefault();
        
        jQuery('#advOptions, #siteapps_reset').slideToggle();
    });
    
    jQuery('#siteapps_configure').click(function(){
        jQuery('#siteapps_site_signup').hide();
        jQuery('#siteapps_site_config').show();    
    });
    
     jQuery('#siteapps_signup').click(function(){
        jQuery('#siteapps_site_config').hide();    
        jQuery('#siteapps_site_signup').show();
    });
});