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
 * @version 1.3.0
 */
?>

<p class="description"><?php echo __( 'KaTeX block:', 'wp-githuber-md' ); ?></p>

<pre class="prettyprint setting-example">
<code class="language-markdown">
```katex
f(x) = \int_{-\infty}^\infty\hat f(\xi)\,e^{2 \pi i \xi x}\,d\xi
```
</code>
</pre>
<p class="description"><?php echo __( 'Block identification code:', 'wp-githuber-md' ); ?> <span class="example-tag">katax</span></p>
<p class="description"><?php echo __( 'KaTeX inline:', 'wp-githuber-md' ); ?></p>

<pre class="prettyprint setting-example">
<code class="language-markdown">
`$$ f(x) = \int_{-\infty}^\infty\hat f(\xi)\,e^{2 \pi i \xi x}\,d\xi $$`
</code>
</pre>

<p class="description"><?php echo __( 'The Markdown text above will be rendered to:', 'wp-githuber-md' ); ?></p>

<pre class="setting-example"><img src="<?= GITHUBER_PLUGIN_URL ?>assets/images/demo_katex.gif"></pre>

