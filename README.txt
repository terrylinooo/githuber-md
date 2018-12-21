=== Githuber MD ===
Contributors: terrylin
Tags: githuber, markdown
Requires at least: 4.7
Tested up to: 4.7
Stable tag: 4.7
Requires PHP: 5.6
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl.html

== Description ==

An all-in-on Markdown WordPress plugin and also improves [Githuber theme](https://github.com/terrylinooo/githuber) functionality.

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

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Go to the Githuber MD menu in Plugins and set your Markdown options.

== Features ==

Features For Markdown

* Markdown editor.
* Live preivew.
* Image copy & paste.
* Syntax highlight.
* Flow chart.
* KaTex.
* Sequence diagram.
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

Githuber MD, Copyright 2018 TerryL.in
Githuber is distributed under the terms of the GNU GPL

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

== Upgrade Notice ==

= Currently no logs now.



