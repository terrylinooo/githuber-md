<?php 
if ( ! defined('GITHUBER_PLUGIN_NAME') ) die; 
/**
 * View for Controller/CustomMediaLibrary
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.6.2
 * @version 1.6.2
 */

?>
<select class="githuber_image_insert" name="githuber_image_insert">
    <option value="markdown" selected><?php echo __( 'Markdown', 'wp-githuber-md'  ); ?></option>
    <option value="html"><?php echo __( 'HTML', 'wp-githuber-md'  ); ?></option>
</select>
