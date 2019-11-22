<?php 
if ( ! defined('GITHUBER_PLUGIN_NAME') ) die; 
/**
 * View for Controller/FetchRemoteImage
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.3.0
 * @version 1.3.0
 */
?>

<div class="submitbox p-r">
	<div class="misc-publishing-actions">
		<table>
			<tr>
				<td style="vertical-align: top">
					<input type="hidden" name="fetch_remote_image" value="no">
					<input type="checkbox" name="fetch_remote_image" value="yes">
				</td>
				<td>
                    <?php echo __( 'Fetch remote images and save them into local folder.', 'wp-githuber-md'  ); ?>
				</td>
			</tr>
		</table>
	</div>
</div>
