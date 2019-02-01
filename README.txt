=== WP Githuber MD - WordPress Markdown Editor ===
Contributors: terrylin
Tags: markdown, markdown editor, katex, mermaid, flow chart, github
Requires at least: 4.0
Tested up to: 5.0.3
Stable tag: 1.5.2
Requires PHP: 5.3.6
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl.html

== Description ==

An all-in-on [WordPress Markdown Plugin](https://terryl.in/en/repository/wordpress-markdown-plugin-githuber-md/) provides a variety of features such as Markdown editor, live-preivew, image-paste, HTML-to-Markdown helper, and more..

Read detailed document, please visit [Wiki](https://github.com/terrylinooo/githuber-md/wiki).

== Demo ==

https://youtu.be/it1noNCTXa4

The Video shows you the following steps:

1. Convert HTML to Markdown by using `HTML to Markdown` tool.
2. Cut up a selection area of an image and copy it from Photoshop, then paste it to Markdown Editor.
3. Click "Update" button to save Markdown to `post_content_filtered` and save HTML to `post_content` (it is what you will see in result). 
4. View the result.

== How it works ==

1. WP Githuber MD will save your Markdown content into `wp_posts`.`post_content_filtered`.
2. Parse the Markdown to HTML, save the parsed HTML content into `wp_posts`.`post_content`.

This plugin will detect your Markdown content and decide what scripts will be loaded, to avoid loading unnecessary scripts.
For example, if you enabled `Syntax highlight`, you have to update your post again to take effects.

== Suggestions ==

The better situation to use this plugin is you just started a new blog.

If you're planning to use this plugin in an existing blog, be sure to:

- Turn off other Markdown plugins, because the similar plugins might do the same things when submitting your posts, may have some syntax conversion issues between Markdown and HTML.
- My personal suggestion is to turn off `revision` and `auto-save`, there are options in setting page.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wp-githuber-md` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Go to the WP Githuber MD menu in Plugins and set your Markdown options.

== Features ==

* Markdown editor.
* Live preivew.
* [HTML-to-Markdown helper](https://terryl.in/en/githuber-md-html2markdown/).
* [Image copy & paste](https://terryl.in/en/githuber-md-image-paste/). (support uploading to Imgur)
* Syntax highlighting
* [Flow chart](https://terryl.in/en/githuber-md-flow-chart/).
* [KaTex](https://terryl.in/en/githuber-md-katax/).
* [Sequence diagram](https://terryl.in/en/githuber-md-sequence-diagrams/). (#1)
* [Mermaid](https://terryl.in/en/githuber-md-mermaid/).
* Github flavored Markdown task list.
* Markdown extra...

== Frequently Asked Questions ==

You'll find answers to many of your questions on [Report issues](https://github.com/terrylinooo/githuber-md/issues).

== Translations ==

Traditional Chinese (zh_TW) by [阿力獅](https://www.alexclassroom.com/)
Simplified Chinese (zh_CN)

== Screenshots ==

1. HTML-to-Markdown tool.
2. Image copy and paste.
3. Update post and see result. 
4. Syntax Highlighing
5. Mermaid.
6. KaTex.
7. Flow Chart.
8. Sequence Diagram.
9. Setting Page 1.
10. Setting page 2.

== Copyright ==

WP Githuber MD, Copyright 2018 TerryL.in
WP Githuber MD is distributed under the terms of the GNU GPL

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

== Changelog ==

= 1.0.0

* First release.

= 1.1.0

* Add Image-Paste module.

= 1.2.0

* Image paste - Provide an option that allows you directly upload to Imgur.com.
* Rename plugin name from "Githuber MD" to "WP Githuber MD".
* Add new settings.

= 1.2.5

* Add support to WordPress 5.0 - Thanks to [Classic Editor](https://wordpress.org/plugins/classic-editor/) plugin.

= 1.3.0

* Add a `HTML to Markdown` tool beside editor.
* Add an option that allows users to turn off `auto-save`.
* Add more information in About page.

= 1.3.1

* Backward compatible with PHP 5.3 and WordPress 4.0.x. (Tested in PHP 5.3.5 with WordPress 4.0.25 OKAY.)
* Modify HTML-to-Markdown helper and image-paste description.
* Add language packs template.
* Improve HTML-to-Markdown helper.

= 1.3.2

* Add language pack: zh_TW.
* Fix bugs.

= 1.4.0

* Add Mermaid module.
* Add language pack: zh_CN.
* Fx bugs.

= 1.4.1

* Add line-number setting for Markdown Editor.
* Adjust KaTeX module - Now it can only used in the Markdown code blocks that are defined with `katax`.
* Improve Markdown editor - Adjust the dialog button's look and fix the overflow issues.
* Yoast SEO's CSS has a global class name `path` uses animation, will break the Marmaid's SVG in the editor's preview panel, because they both uses the same common class name. We force to remove the animation attribute to make it work.

= 1.4.2

* Fix bug: HTML-to-Markdown helper.

= 1.4.3

* Bug fix - issue #3 - Thank to wojciehm@github for reporting this issue.
* Display a notice if  user's PHP version does not meet the minimum requirement.

= 1.5.0

* Detect Jetpack Markdown module.

= 1.5.2

* Fix bug: Inserting image not working. (issue #6)
* Support KaTex inline. (feature request #7)
* Add custom Markdown syntax block for HTML 5 `figure` (feature request #8)

== Known Issues ==

* #1 - Sequence Diagram: this feature is only available in WordPress version > 4.5, because it uses underscore.js, and it has confict issues with WordPress' plupload uploader in early version. You can use Mermaid instead of it. We have already hidden this option in setting while an user uses that version < 4.5

== Upgrade Notice ==

= Currently no logs now.
