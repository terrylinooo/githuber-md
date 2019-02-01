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
 * @version 1.4.0
 */
?>

<pre style="font-size: 13px; height: 200px; overflow-y: scroll; border: 1px #dddddd solid; background-color: #ffffff; padding: 20px">
1.0.0

* First release.

1.1.0

* Add Image-Paste module.

1.2.0

* Image paste - Provide an option that allows you directly upload to Imgur.com.
* Rename plugin name from "Githuber MD" to "WP Githuber MD".
* Add new settings.

1.2.5

* Add support to WordPress 5.0 - Thanks to <a href="https://wordpress.org/plugins/classic-editor/" target="_blank">Classic Editor</a> plugin.

1.3.0

* Add a HTML to Markdown tool beside the editor.
* Add an option that allows users to turn off `auto-save`.
* Add more information in About page.

1.3.1

* Backward compatible with PHP 5.3 and WordPress 4.0.x. Tested OKAY.
* Modify HTML-to-Markdown helper and image-paste description.
* Improve HTML-to-Markdown helper.
* Add language packs for zh_TW and zh_CN.

1.3.2

* Add language pack: zh_TW.
* Fix bugs.

1.4.0

* Add Mermaid module.
* Add language pack: zh_CN.
* Fx bugs.

1.4.1

* Add line-number setting for Markdown Editor.
* Adjust KaTeX module - Now it can only used in the Markdown code block defined with `katax`.
* Improve Markdown editor - Adjust the dialog button's look and fix the overflow issues.
* Yoast SEO's CSS has a global class name `path` uses animation, will break the Marmaid's SVG in the editor's preview panel, because they both uses the same common class name. We force to remove the animation attribute to make it work.

1.4.2

* Fix bug: HTML-to-Markdown helper.

1.4.3

* Bug fix - issue #3 - Thank to <a href="https://github.com/wojciehm">wojciehm</a> for reporting this issue.
* Display a notice if  user's PHP version does not meet the minimum requirement.

1.5.0

* * Detect Jetpack Markdown module.

= 1.5.2

* Fix bug: Inserting image not working. (issue #6)
* Support KaTex inline. (feature request #7)
* Add custom Markdown syntax block for HTML 5 `figure` (feature request #8)

</pre>
