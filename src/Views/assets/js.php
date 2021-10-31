<?php 
if ( ! defined('GITHUBER_PLUGIN_NAME') ) die; 
/**
 * View for CSS
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.6.1
 * @version 1.6.1
 */
?>

<?php if ( '_blank' === githuber_get_option( 'post_link_target_attribute', 'githuber_preferences' ) ) : ?>
    <script id="preference-link-target">
        (function($) {
            $(function() {
                $(".post").find("a").each(function() {
                    var link_href = $(this).attr("href");
                    if (link_href.indexOf("#") == -1) {
                        $(this).attr("target", "_blank");
                    }
                });
            });
        })(jQuery);
    </script>
<?php endif; ?>