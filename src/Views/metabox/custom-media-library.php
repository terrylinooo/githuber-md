<?php 
if ( ! defined('FUTURE_PLUGIN_NAME') ) die;
/**
 * View for Controller/CustomMediaLibrary
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Future
 * @since 1.6.2
 * @version 1.6.2
 */

?>
<select class="future_image_insert" name="future_image_insert">
    <option value="markdown" selected><?php echo __( 'Markdown', 'wp-future-md'  ); ?></option>
    <option value="html"><?php echo __( 'HTML', 'wp-future-md'  ); ?></option>
</select>
