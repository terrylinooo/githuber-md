<?php 
if ( ! defined('GITHUBER_PLUGIN_NAME') ) die; 
/**
 * View for Controller/KeywordSuggestion
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.5.0
 * @version 1.5.0
 */
?>

<div class="submitbox p-r">
	<div class="misc-publishing-actions">
		<p>
			<?php echo __( 'Enter a keyword that you want to fetch its related long-tail terms.', 'wp-githuber-md'  ); ?>
		</p>
		<table>
			<tr>
				<td>
					<input type="text" name="ks_keyword">
					<input type="hidden" name="ks_nonce" value="<?php echo wp_create_nonce( 'keyword_suggession_action' ); ?>">
				</td>
            </tr>
            <tr>
                <td id="display-keyword-suggestion"></td>
            </tr>
		</table>
	</div>
	<div class="clear"></div>
	<hr />
	<div class="major-publishing-actions" style="text-align: right; padding-top: 3px;">
		<div class="publishing-action">
			<button id="btn-keyword-suggestion-reset" type="button" class="button button-large"><?php echo __( 'Clear', 'wp-githuber-md'  ); ?></button>&nbsp;
			<button id="btn-keyword-suggestion-query" class="button button-primary button-large" type="button"><?php echo __( 'Query', 'wp-githuber-md'  ); ?></button>
		</div>
	</div>
</div>
