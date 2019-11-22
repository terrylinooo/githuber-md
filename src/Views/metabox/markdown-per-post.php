<?php 
if ( ! defined('GITHUBER_PLUGIN_NAME') ) die; 
/**
 * View for Controller/HtmlToMarkdown
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.6.0
 * @version 1.6.0
 */
if ( ! isset( $markdown_this_post_choice ) ) {
   return;
}
?>
<div class="submitbox p-r">
	<div class="misc-publishing-actions">
		<?php if ( 'no' !== $markdown_this_post_choice ) : ?>
		<div class="wpmd">
			<?php if ( $is_markdown_this_post ) : ?>
				<input type="checkbox" name="markdown_this_post" id="markdown-switch" value="yes" checked /><label for="markdown-switch">Toggle</label>
			<?php else : ?>
				<input type="checkbox" name="markdown_this_post" id="markdown-switch" value="yes" /><label for="markdown-switch">Toggle</label>
			<?php endif; ?>
		</div>
		<?php else : ?>
		<div class="wpmd">
			<input type="checkbox" name="markdown_this_post" id="markdown-switch" value="yes" /><label for="markdown-switch">Toggle</label>
		</div>
		<?php endif; ?>
	</div>
</div>


