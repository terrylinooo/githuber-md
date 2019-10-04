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
%[Alt text](http://yoururl.com/test.jpg "Figcaption text")
</code>
</pre>
<?php

echo __( 'The Markdown text above will be transformed to:', 'wp-future-md' );

?><br />

<pre class="prettyprint setting-example">
<code class="language-html">
&lt;figure&gt;
    &lt;img src=&quot;http://yoururl.com/test.jpg&quot; alt=&quot;Alt text&quot;&gt;
    &lt;figcaption&gt;Figcaption text&lt;/figcaption&gt;
&lt;/figure&gt;
</code>
</pre>

