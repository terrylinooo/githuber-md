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
if ( ! isset( $markdown_per_post_choice ) ) {
    return;
}
?>

<div class="submitbox">
	<div class="misc-publishing-actions">
		<table>
			<tr>
				<td>
					&nbsp;&nbsp;
                    <?php if ( '1' === $markdown_per_post_choice ) : ?>
                        <input type="radio" name="markdown_per_post" value="yes" checked> <?php echo __( 'Enable', 'wp-githuber-md'  ); ?>
                        &nbsp;&nbsp;
                        <input type="radio" name="markdown_per_post" value="no"> <?php echo __( 'Disable', 'wp-githuber-md'  ); ?> 
                    <?php else : ?>
                        <input type="radio" name="markdown_per_post" value="yes"> <?php echo __( 'Enable', 'wp-githuber-md'  ); ?>
                        &nbsp;&nbsp;
                        <input type="radio" name="markdown_per_post" value="no" checked> <?php echo __( 'Disable', 'wp-githuber-md'  ); ?> 
                    <?php endif; ?>
				</td>
			</tr>
		</table>
	</div>
	<div class="clear"></div>
	<hr />
	<div class="major-publishing-actions" style="text-align: right; padding-top: 3px;">
		<div class="publishing-action">
			<button id="btn-markdown-per-post" class="button button-primary button-large" type="button"><?php echo __( 'Submit', 'wp-githuber-md'  ); ?></button>
		</div>
	</div>
</div>
