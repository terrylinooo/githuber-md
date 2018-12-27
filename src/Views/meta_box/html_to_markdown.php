<?php 
if ( ! defined('GITHUBER_PLUGIN_NAME') ) die; 
/**
 * View for Controller/HtmlToMarkdown
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.3.0
 * @version 1.3.0
 */
?>

<div class="submitbox">
    <div class="misc-publishing-actions">
        <p>
            <?php echo __( 'This is a tool that helps you easily convert an old post (not current content in your editor area) into Markdown. If you are not satisfied with the result, do not click <strong>Update</strong> button.', GITHUBER_PLUGIN_TEXT_DOMAIN ); ?>
        </p>
        <table>
            <tr>
                <td>
                    <?php echo __( 'Strip tags', GITHUBER_PLUGIN_TEXT_DOMAIN ); ?>:
                </td>
                <td>
                    &nbsp;&nbsp;
                    <input type="radio" name="h2m_strip_tags" value="yes" checked> <?php echo __( 'Yes', GITHUBER_PLUGIN_TEXT_DOMAIN ); ?>
                    &nbsp;&nbsp;
                    <input type="radio" name="h2m_strip_tags" value="no"> <?php echo __( 'No', GITHUBER_PLUGIN_TEXT_DOMAIN ); ?> 
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo __( 'Line break', GITHUBER_PLUGIN_TEXT_DOMAIN ); ?>:
                </td>
                <td>
                    &nbsp;&nbsp;
                    <input type="radio" name="h2m_line_break" value="yes" checked> <?php echo __( 'Yes', GITHUBER_PLUGIN_TEXT_DOMAIN ); ?>
                    &nbsp;&nbsp;
                    <input type="radio" name="h2m_line_break" value="no"> <?php echo __( 'No', GITHUBER_PLUGIN_TEXT_DOMAIN ); ?><br />
                </td>
            </tr>
        </table>
    </div>
    <div class="clear"></div>
    <hr />
    <div class="major-publishing-actions" style="text-align: right; padding-top: 3px;">
        <div class="publishing-action">
            <button type="button" class="button button-large" onclick="location.reload();"><?php echo __( 'Reload', GITHUBER_PLUGIN_TEXT_DOMAIN ); ?></button>&nbsp;
            <button id="btn-html2markdown" class="button button-primary button-large" type="button"><?php echo __( 'Convert', GITHUBER_PLUGIN_TEXT_DOMAIN ); ?></button>
        </div>
    </div>
</div>
