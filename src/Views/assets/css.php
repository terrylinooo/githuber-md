<?php 
if ( ! defined('GITHUBER_PLUGIN_NAME') ) die; 
/**
 * View for CSS
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.6.1
 * @version 1.6.1
 */
?>

<?php if ( 'yes' === githuber_get_option( 'support_task_list', 'githuber_extensions' ) ) : ?>
    .gfm-task-list {
        border: 1px solid transparent;
        list-style-type: none;
    }
    .gfm-task-list input {
        margin-right: 10px !important;
    }
<?php endif; ?>

<?php if ( 'yes' === githuber_get_option( 'support_katex', 'githuber_modules' ) ) : ?>
    .katex-container {
        margin: 25px !important;
        text-align: center;
    }
    .katex-container.katex-inline {
        display: inline-block !important;
        background: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    pre .katex-container {
        font-size: 1.4em !important;
    }
    .katex-inline {
        background: none !important;
        margin: 0 3px;
    }
<?php endif; ?>

<?php if ( '_blank' === githuber_get_option( 'post_link_target_attribute', 'githuber_preferences' ) ) : ?>
    code.kb-btn {
        display: inline-block;
        color: #666;
        font: bold 9pt arial;
        text-decoration: none;
        text-align: center;
        padding: 2px 5px;
        margin: 0 5px;
        background: #eff0f2;
        -moz-border-radius: 4px;
        border-radius: 4px;
        border-top: 1px solid #f5f5f5;
        -webkit-box-shadow: inset 0 0 20px #e8e8e8, 0 1px 0 #c3c3c3, 0 1px 0 #c9c9c9, 0 1px 2px #333;
        -moz-box-shadow: inset 0 0 20px #e8e8e8, 0 1px 0 #c3c3c3, 0 1px 0 #c9c9c9, 0 1px 2px #333;
        box-shadow: inset 0 0 20px #e8e8e8, 0 1px 0 #c3c3c3, 0 1px 0 #c9c9c9, 0 1px 2px #333;
        text-shadow: 0px 1px 0px #f5f5f5;
    }
<?php endif; ?>

<?php if ( 'yes' === githuber_get_option( 'support_clipboard', 'githuber_modules' ) ) : ?>
<?php

    $svg = "data:image/svg+xml,%3Csvg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' width='16px' height='16px' viewBox='888 888 16 16' enable-background='new 888 888 16 16' xml:space='preserve'%3E %3Cpath fill='%23333333' d='M903.143,891.429c0.238,0,0.44,0.083,0.607,0.25c0.167,0.167,0.25,0.369,0.25,0.607v10.857 c0,0.238-0.083,0.44-0.25,0.607s-0.369,0.25-0.607,0.25h-8.571c-0.238,0-0.44-0.083-0.607-0.25s-0.25-0.369-0.25-0.607v-2.571 h-4.857c-0.238,0-0.44-0.083-0.607-0.25s-0.25-0.369-0.25-0.607v-6c0-0.238,0.06-0.5,0.179-0.786s0.262-0.512,0.428-0.679 l3.643-3.643c0.167-0.167,0.393-0.309,0.679-0.428s0.547-0.179,0.786-0.179h3.714c0.238,0,0.44,0.083,0.607,0.25 c0.166,0.167,0.25,0.369,0.25,0.607v2.929c0.404-0.238,0.785-0.357,1.143-0.357H903.143z M898.286,893.331l-2.67,2.669h2.67V893.331 z M892.571,889.902l-2.669,2.669h2.669V889.902z M894.321,895.679l2.821-2.822v-3.714h-3.428v3.714c0,0.238-0.083,0.441-0.25,0.607 s-0.369,0.25-0.607,0.25h-3.714v5.714h4.571v-2.286c0-0.238,0.06-0.5,0.179-0.786C894.012,896.071,894.155,895.845,894.321,895.679z M902.857,902.857v-10.286h-3.429v3.714c0,0.238-0.083,0.441-0.25,0.607c-0.167,0.167-0.369,0.25-0.607,0.25h-3.714v5.715H902.857z' /%3E %3C/svg%3E";
    $svg = addslashes( $svg );

?>

    .copy-button {
        cursor: pointer;
        border: 0;
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 500;
        padding: 3px 6px 3px 6px;
        background-color: rgba(255, 255, 255, 0.6);
        position: absolute;
        overflow: hidden;
        top: 5px;
        right: 5px;
        border-radius: 3px;
    }
    .copy-button:before {
        content: "";
        display: inline-block;
        width: 16px;
        height: 16px;
        margin-right: 3px;
        background-size: contain;
        background-image: url("<?php echo $svg; ?>");
        background-repeat: no-repeat;
        position: relative;
        top: 3px;
    }
    pre {
        position: relative;
    }
    pre:hover .copy-button {
        background-color: rgba(255, 255, 255, 0.9);
    }

<?php endif; ?>

<?php if ( 'yes' == githuber_get_option( 'support_toc', 'githuber_modules' ) ) : ?>

    .md-widget-toc {
        padding: 15px;
    }
    .md-widget-toc a {
        color: #333333;
    }
    .post-toc-header {
        font-weight: 600;
        margin-bottom: 10px;
    }
    .md-post-toc {
        font-size: 0.9em;
    }
    .post h2 {
        overflow: hidden;
    }
    .post-toc-block {
        margin: 0 10px 20px 10px;
        overflow: hidden;
    }
    .post-toc-block.with-border {
        border: 1px #dddddd solid;
        padding: 10px;
    }
    .post-toc-block.float-right {
        max-width: 320px;
        float: right;
    }
    .post-toc-block.float-left {
        max-width: 320px;
        float: left;
    }
    .md-widget-toc ul, .md-widget-toc ol, .md-post-toc ul, .md-post-toc ol {
        padding-left: 15px;
        margin: 0;
    }
    .md-widget-toc ul ul, .md-widget-toc ul ol, .md-widget-toc ol ul, .md-widget-toc ol ol, .md-post-toc ul ul, .md-post-toc ul ol, .md-post-toc ol ul, .md-post-toc ol ol {
        padding-left: 2em;
    }
    .md-widget-toc ul ol, .md-post-toc ul ol {
        list-style-type: lower-roman;
    }
    .md-widget-toc ul ul ol, .md-widget-toc ul ol ol, .md-post-toc ul ul ol, .md-post-toc ul ol ol {
        list-style-type: lower-alpha;
    }
    .md-widget-toc ol ul, .md-widget-toc ol ol, .md-post-toc ol ul, .md-post-toc ol ol {
        padding-left: 2em;
    }
    .md-widget-toc ol ol, .md-post-toc ol ol {
        list-style-type: lower-roman;
    }
    .md-widget-toc ol ul ol, .md-widget-toc ol ol ol, .md-post-toc ol ul ol, .md-post-toc ol ol ol {
        list-style-type: lower-alpha;
    }

<?php endif; ?>

<?php if ( 'yes' == githuber_get_option( 'support_mathjax', 'githuber_modules' ) ) : ?>

    .post pre code script, .language-mathjax ~ .copy-button {
        display: none !important;
    }

<?php endif; ?>

<?php if ( 'yes' === githuber_get_option( 'support_emojify', 'githuber_modules' ) ) : ?>
    <?php $emoji_size = githuber_get_option( 'emojify_emoji_size', 'githuber_modules' ); ?> 
    <?php if ( '1.5em' !== $emoji_size ) : ?> 
  
    .post .emoji {
        width: <?php echo $emoji_size; ?>;
        height: <?php echo $emoji_size; ?>;
    }
  
    <?php endif; ?>
<?php endif; ?>
