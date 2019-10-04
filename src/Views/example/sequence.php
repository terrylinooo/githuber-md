<?php 
if ( ! defined('FUTURE_PLUGIN_NAME') ) die;
/**
 * View for Controller/Setting
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Future
 * @since 1.2.0
 * @version 1.3.0
 */
?>

<pre class="prettyprint setting-example">
<code class="language-markdown">
```seq
A->B: Message
B->C: Message
C->A: Message
```
</code>
</pre>
<p class="description"><?php echo __( 'Block identification code:', 'wp-future-md' ); ?> <span class="example-tag">seq</span> <span class="example-tag">sequence</span></p>
<p class="description"><?php echo __( 'The Markdown text above will be rendered to:', 'wp-future-md' ); ?></p>

<pre class="setting-example"><img src="<?= FUTURE_PLUGIN_URL ?>assets/images/demo_sequence.gif"></pre>

