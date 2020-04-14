<?php 
if ( ! defined('GITHUBER_PLUGIN_NAME') ) die; 
/**
 * View for Controller/HtmlToMarkdown
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.6.0
 * @version 1.6.0
 */
if ( ! isset( $markdown_this_post_choice ) ) {
   return;
}
?>
<div class="submitbox p-r">
	<div class="misc-publishing-actions">
		<?php if ( 'no' !== $markdown_this_post_choice ) : ?>
		<div class="wpmd">
			<?php if ( $is_markdown_this_post ) : ?>
				<input type="checkbox" name="markdown_this_post" id="markdown-switch" value="yes" checked /><label for="markdown-switch">Toggle</label>
			<?php else : ?>
				<input type="checkbox" name="markdown_this_post" id="markdown-switch" value="yes" /><label for="markdown-switch">Toggle</label>
			<?php endif; ?>
		</div>
		<?php else : ?>
		<div class="wpmd">
			<input type="checkbox" name="markdown_this_post" id="markdown-switch" value="yes" /><label for="markdown-switch">Toggle</label>
		</div>
		<?php endif; ?>
	</div>
</div>

<?php if ( 'yes' == githuber_get_option( 'support_mathjax', 'githuber_modules' ) ) : ?>
<!-- BEGIN - This section is a templete for MathJax module -->
<script type="text/x-mathjax-config"> 
	MathJax.Hub.Config({
		showProcessingMessages: false,
		messageStyle: "none",
		extensions: [
			"tex2jax.js",
			"TeX/mediawiki-texvc.js",
			"TeX/noUndefined.js",
			"TeX/autoload-all.js",
			"TeX/AMSmath.js",
			"TeX/AMSsymbols.js"
		],
		jax: [
			"input/TeX",
			"output/SVG"
		],
		elements: document.getElementsByClassName("mathjax"),
		tex2jax: {
			skipTags: [
				"script",
				"noscript",
				"style",
				"textarea"
			],
			processClass: "mathjax"
		},
		processEscapes: true,
		preview: "none"
	});
</script>
<!-- END - This section is a templete for MathJax module -->
<?php endif; ?>

