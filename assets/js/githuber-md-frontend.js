(function($) {

    $(function() {
        var setting = window.md_frontend_settings;

        if (setting.link_opening_method == '_blank') {
            var link_href = $(this).attr('href');
            if (link_href.indexOf('#') !== -1) {
                $('.post a').attr('target', '_blank');
            }
        }
    });
        
 })(jQuery);


