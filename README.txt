=== WP Githuber MD ===
Contributors: terrylin
Tags: githuber, markdown
Requires at least: 4.0
Tested up to: 5.0.2
Stable tag: 1.3.1
Requires PHP: 5.3
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl.html

== Description ==

An all-in-on [WordPress Markdown Plugin](https://terryl.in/en/repository/wordpress-markdown-plugin-githuber-md/) provides a variety of features such as Markdown editor, live-preivew, image-paste, HTML-to-Markdown helper, and more..

== Demo ==

https://youtu.be/it1noNCTXa4

The Video shows you the following steps:

1. Convert HTML to Markdown by using `HTML to Markdown` tool.
2. Cut up a selection area of an image and copy it from Photoshop, then paste it to Markdown Editor.
3. Click "Update" button to save Markdown to `post_content_filtered` and save HTML to `post_content` (it is what you will see in result). 
4. View the result.

== How it works ==

1. Githuber MD will save your Markdown content into `wp_posts`.`post_content_filtered`.
2. Parse the Markdown to HTML, save the parsed HTML content into `wp_posts`.`post_content`.

This plugin will detect your Markdown content and decide what scripts will be loaded, to avoid loading unnecessary scripts.
For example, if you enabled `Syntax highlight`, you have to update your post again to take effects.

== Suggestions ==

The better situation to use this plugin is you just started a new blog.

If you're planning to use this plugin in an existing blog, be sure to:

- At the first, backup your data, we do not guarantee things work as expected.
- Turn off other Markdown plugins, because the similar plugins might do the same things when submitting your posts, may have some syntax conversion issues between Markdown and HTML.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wp-githuber-md` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Go to the Githuber MD menu in Plugins and set your Markdown options.

== Features ==

Features For Markdown

* Markdown editor.
* Live preivew.
* [Image copy & paste](https://terryl.in/en/githuber-md-image-paste/). (support uploading to Imgur)
* Syntax highlight.
* [Flow chart](https://terryl.in/en/githuber-md-flow-chart/).
* [KaTex](https://terryl.in/en/githuber-md-katax/).
* [Sequence diagram](https://terryl.in/en/githuber-md-sequence-diagrams/).
* Github flavored Markdown task list.
* Markdown extra.

Features For Githuber Theme:

* Widget: Table of Content.
* Post type: GitHub Repository.

== Frequently Asked Questions ==

You'll find answers to many of your questions on [Report issues](https://github.com/terrylinooo/githuber-md/issues).

== Screenshots ==

1. Markdown editor.
2. Plugin setting page - Markdown.
3. Plugin setting page - Modules.

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
* Add language packs for zh_TW and zh_CN.
* Improve HTML-to-Markdown helper.

== Upgrade Notice ==

= Currently no logs now.