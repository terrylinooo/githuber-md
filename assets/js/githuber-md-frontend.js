(function($) {

    $(function() {
        var setting = window.md_frontend_settings;

        if (setting.link_opening_method == '_blank') {
            $('.post a').attr('target', '_blank');
        }
    });
        
 })(jQuery);


