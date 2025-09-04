<?php
if (!defined('GITHUBER_PLUGIN_NAME')) die;
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

<?php if ('_blank' === githuber_get_option('post_link_target_attribute', 'githuber_preferences')) : ?>
    <script id="preference-link-target">
        document.addEventListener('DOMContentLoaded', function() {
            let links = document.querySelectorAll('.post a');
            links.forEach(function(link) {
                let linkHref = link.getAttribute('href');
                if (linkHref && linkHref.indexOf('#') === -1) {
                    link.setAttribute('target', '_blank');
                }
            });
        });
    </script>
<?php endif; ?>