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

<pre style="background-color: #ffffff; padding: 5px;">

<code style="background-color: #ffffff">%[Alt text](http://yoururl.com/test.jpg "Figcaption text")</code>

</pre>
<?php

echo __( 'will be transformed to:', 'wp-githuber-md' );

?><br />

<pre style="background-color: #ffffff; padding: 5px;">

<code style="background-color: #ffffff">&lt;figure&gt;
    &lt;img src=&quot;http://yoururl.com/test.jpg&quot; alt=&quot;Alt text&quot;&gt;
    &lt;figcaption&gt;Figcaption text&lt;/figcaption&gt;
&lt;/figure&gt;
</code>

</pre>

