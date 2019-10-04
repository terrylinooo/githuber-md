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
```mermaid
sequenceDiagram
participant Alice
participant Bob
Alice->>John: Hello John, how are you?
loop Healthcheck
John->>John: Fight against hypochondria
end
Note right of John: Rational thoughts<br/>prevail...
John-->>Alice: Great!
John->>Bob: How about you?
Bob-->>John: Jolly good!
```
</code>
</pre>
<p class="description"><?php echo __( 'Block identification code:', 'wp-future-md' ); ?> <span class="example-tag">mermaid</span></p>
<p class="description"><?php echo __( 'The Markdown text above will be rendered to:', 'wp-future-md' ); ?></p>

<pre class="setting-example"><img src="<?= FUTURE_PLUGIN_URL ?>assets/images/demo_mermaid.gif"></pre>

