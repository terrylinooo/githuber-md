<?php

if ( ! defined( 'GITHUBER_PLUGIN_NAME' ) ) {
	die;
}

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

echo __( 'Display a <strong>HTML to Markdown</strong> helper widget beside the Markdown editor to assist in converting an <strong>old post</strong> into Markdown. <br />This is just a <strong>preview</strong> toshow what the converted content would look like in Markdown.<br /><br />Note: Turning on this option will disable <strong>auto-save</strong> to prevent altering your original content.<br />If you arenot satisfied with the result, do not click the <strong>Update</strong> button.', 'wp-githuber-md' );

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
