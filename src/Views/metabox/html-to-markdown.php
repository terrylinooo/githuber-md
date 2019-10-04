<?php 
if ( ! defined('FUTURE_PLUGIN_NAME') ) die;
/**
 * View for Controller/HtmlToMarkdown
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Future
 * @since 1.3.0
 * @version 1.3.0
 */
?>

<div class="submitbox">
	<div class="misc-publishing-actions">
		<p>
			<?php echo __( 'This is a tool that helps you easily convert an old post into Markdown. If you are not satisfied with the result, do not click <strong>Update</strong> button.', 'wp-future-md'  ); ?>
		</p>
		<table>
			<tr>
				<td>
					<?php echo __( 'Strip tags', 'wp-future-md'  ); ?>:
				</td>
				<td>
					&nbsp;&nbsp;
					<input type="radio" name="h2m_strip_tags" value="yes" checked> <?php echo __( 'Yes', 'wp-future-md'  ); ?>
					&nbsp;&nbsp;
					<input type="radio" name="h2m_strip_tags" value="no"> <?php echo __( 'No', 'wp-future-md'  ); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo __( 'Line break', 'wp-future-md'  ); ?>:
				</td>
				<td>
					&nbsp;&nbsp;
					<input type="radio" name="h2m_line_break" value="yes" checked> <?php echo __( 'Yes', 'wp-future-md'  ); ?>
					&nbsp;&nbsp;
					<input type="radio" name="h2m_line_break" value="no"> <?php echo __( 'No', 'wp-future-md'  ); ?><br />
				</td>
			</tr>
		</table>
	</div>
	<div class="clear"></div>
	<hr />
	<div class="major-publishing-actions" style="text-align: right; padding-top: 3px;">
		<div class="publishing-action">
			<button type="button" class="button button-large" onclick="location.reload();"><?php echo __( 'Reload', 'wp-future-md'  ); ?></button>&nbsp;
			<button id="btn-html2markdown" class="button button-primary button-large" type="button"><?php echo __( 'Convert', 'wp-future-md'  ); ?></button>
		</div>
	</div>
</div>
