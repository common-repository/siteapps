jQuery(document).ready(function($) {
    $('.wpbtb_widget_mode').live('change', function(e) {
        var select_val = $(this).is(':checked');
        
        var start_hidden    = $(this).nextAll('.start-hidden');
        
        if (select_val) {
            start_hidden.removeClass('wpbtb-hide').addClass('wpbtb-show');
        } else {
            start_hidden.removeClass('wpbtb-show').addClass('wpbtb-hide');
        }
    });
   
   $('.siteapps-segment').live('change', function(e) {
       var select_val = $(this).is(':checked');
       if (select_val) {
           $(this).parent().parent().addClass("siteapps-orange");
       } else {
           $(this).parent().parent().removeClass("siteapps-orange");
       }
   });
});
