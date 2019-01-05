<?php 
if ( ! defined('GITHUBER_PLUGIN_NAME') ) die; 
/**
 * View for Controller/Setting
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.3.0
 * @version 1.3.0
 */
?>

<?php 

echo __( 'Dsiplaying A <strong>HTML to Markdown</strong> helper widget beside Markdown editor that helps you convert an <strong>old post</strong> into Markdown. <br />It is just a <strong>preview</strong>, to let you know what the converted content looks like after converting to Markdown.<br /><br />Notice: Turning on this option will force to disable <strong>auto-save</strong>, prevents breaking your original content.<br />If you are not satisfied with the result, do not click <strong>Update</strong> button.', 'wp-githuber-md');

?>

<script>
	(function($) {
		$(function() {
			var is_html_to_markdown = $('#wpuf-githuber_markdown-html_to_markdown-yes').is(':checked');

			if (is_html_to_markdown) {
				$('#wpuf-githuber_markdown-disable_autosave-yes').prop('checked', true);
				$('#wpuf-githuber_markdown-disable_autosave-no').prop('checked', false);
			}
		});
	})(jQuery);
</script>