<?php 
if ( ! defined('GITHUBER_PLUGIN_NAME') ) die; 
/**
 * View for Controller/Setting
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.2.0
 * @version 1.8.4
 */
?>

<pre class="prettyprint setting-example">
<code class="language-markdown">
Happy Markdowning!!

1. Use `{ctrl}`+`{c}` to copy text.
2. Use `{ctrl}`+`{v}` to paste text.
3. Open task manager: `{ctrl}`+`{alt}`+`{del}`
</code>
</pre>

<p class="description"><?php echo __( 'The Markdown text above will be rendered to:', 'wp-githuber-md' ); ?></p>

<pre class="setting-example"><img src="<?= GITHUBER_PLUGIN_URL ?>assets/images/demo_inline_keyboard.gif"></pre>
