<?php 
if ( ! defined('GITHUBER_PLUGIN_NAME') ) die; 
/**
 * View for Controller/Setting
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.7.0
 * @version 1.7.0
 */
?>

<p class="description"><?php echo __( 'MathJax block:', 'wp-githuber-md' ); ?></p>

<pre class="prettyprint setting-example">
<code class="language-markdown">
```mathjax
f(x) = \int_{-\infty}^\infty\hat f(\xi)\,e^{2 \pi i \xi x}\,d\xi
```
</code>
</pre>
<p class="description"><?php echo __( 'Block identification code:', 'wp-githuber-md' ); ?> <span class="example-tag">mathjax</span></p>
<p class="description"><?php echo __( 'MathJax inline:', 'wp-githuber-md' ); ?></p>

<pre class="prettyprint setting-example">
<code class="language-markdown">
`$ f(x) = \int_{-\infty}^\infty\hat f(\xi)\,e^{2 \pi i \xi x}\,d\xi $`
</code>
</pre>

<p class="description"><?php echo __( 'The Markdown text above will be rendered to:', 'wp-githuber-md' ); ?></p>

<pre class="setting-example"><img src="<?= GITHUBER_PLUGIN_URL ?>assets/images/demo_katex.gif"></pre>

