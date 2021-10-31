=== WP Githuber MD - WordPress Markdown Editor ===
Contributors: terrylin
Tags: markdown, markdown editor, katex, mermaid, flow chart, github
Requires at least: 4.0
Tested up to: 5.8.1
Stable tag: 1.16.1
Requires PHP: 5.3.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl.html

== Description ==

An all-in-on WordPress Markdown Plugin provides a variety of features such as Markdown editor, live-preview, image-paste, HTML-to-Markdown helper, and more..

Read detailed document, please visit [https://github.com/terrylinooo/githuber-md](https://github.com/terrylinooo/githuber-md).

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
For example, if you enabled Syntax highlight, you have to update your post again to take effects.

== Suggestions ==

The better situation to use this plugin is you just started a new blog.

If you're planning to use this plugin in an existing blog, be sure to:

- Turn off other Markdown plugins, because the similar plugins might do the same things when submitting your posts, may have some syntax conversion issues between Markdown and HTML.
- My suggestion is to turn off revision and auto-save, there are options in setting page.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wp-githuber-md` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Go to the WP Githuber MD menu in Plugins and set your Markdown options.

== Features ==

* [Markdown editor](https://markdown-editor.github.io/).
* Live preview.
* Spell check.
* Enable / disable Markdown for single post.
* Support Gutenberg editor.
* Support custom post types.
* Support Markdown extra syntax.
* HTML-to-Markdown tool.
* Image copy & paste (support uploading to Imgur.com and sm.ms)
* Highlight code syntax. (prism.js or hightlight.js)
* Flow chart.
* KaTex.
* Sequence diagram.
* Mermaid.
* MathJax.
* Emoji.
* Github flavored Markdown task list.
* Fetch remote images.
* Keyword suggestion tool.
* and more...

== Frequently Asked Questions ==

You'll find answers to many of your questions on [Report issues](https://github.com/terrylinooo/githuber-md/issues).

== Translations ==

Traditional Chinese (zh_TW) by [Alex Lion](https://www.alexclassroom.com/).
Simplified Chinese (zh_CN) by [Terry Lin](https://terryl.in/zh/).

== Screenshots ==

1. HTML-to-Markdown tool.
2. Image copy and paste.
3. Update post and see result.
4. Syntax Highlighing
5. Mermaid.
6. KaTex.
7. Flow Chart.
8. Sequence Diagram.
9. Setting Page 1. (Markdown)
10. Setting page 2. (Modules)
10. Setting page 3. (Extensions)
10. Setting page 4. (Preferences)

== Copyright ==

WP Githuber MD, Copyright 2018-2020 TerryL.in
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

= 1.6.0

* Support cusotm post types.
* Add `Enable Markdown` option beside editor.
* Support Gutenberg editor. Now you are able to disable Markdown for a post then back to use Gutenberg editor.

= 1.6.2

* Add a new setting option in ImagePaste module. (feature request #16)
* Add new option when using `Add media -> Insert to post` that allows user insert HTML into post. (feature request #17)
* Fix bug: Classic editor's visual mode is not showed after disabling post type option. (issue #15)
* Imporve: Beautify Markdown switcher.

= 1.7.0

* Improve setting page UI.
* Add examples in setting pages.
* Issue #22 - footnote.
* Feautue request #19 - Add support to shortcode.
* And other issues...

= 1.7.1

* Bug fix and a bit improvement.

= 1.7.2

* Fix issue: disable-revisions not working.
* Add donation information.

= 1.7.3

* Fix issues: #29, issue #30 and issue #31.
* Fix issue: Makrdown extra not working.

= 1.7.4

* Fix Javascript syntax error (Link Opening Method).

= 1.8.0

* Fix issue #39: Live-preview doesn't react when Sync Scrolling is off.
* Feature request #36: Image-paste - add suport to sm.ms (another image hosting service)
* Fix bug: register_activation_hook does not implement when activating plugin at the first time.
* Fix issue: incorrect variable name in unstall.php.

= 1.8.1

* Fix issue: wp_get_attachment_url() doesn't distinguish whether a page request arrives via HTTP or HTTPS
* Fix issue #38 - preserve inline code block.
* Add Simplified Chinese language package.

= 1.8.2

* Fix issue #42: Markdown syntax takes effect between two inline code blocks.

= 1.8.5

* Add feature: Githuber MD extensions - Inline code block with keyboard style. Example: `{ctrl}`
* Fix issue: inline code block doesn't work on just one character, for example `a`. This issue is associated with issue #42

= 1.8.6

* Fix issue #44: Unable to insert linked images.
* Fix issue #45: HTML-to-Markdown doesn't transform table as expected.

= 1.9.0

* Add feature: TOC (Table of content) module.
* Add feature: Transform `&amp;` to `&` in URLs.

= 1.9.1

* Fix issue #48: Because some systems don't have `php_fileinfo` installed, use `$_FILES['type']` instead.

= 1.10.0

* Feature request #53: Add `Copy-to-Clipboard` button on syntax highlighting code blocks.
* Fix issue #52 - Remove auto-match highlighter.
* Fix issue #57 - Preserve code blocks in a list.

= 1.10.1

* Feature request #59: Move the front added .css and .js to the inline script section.
* Issue #62: Fix add media button that is invalid for other types of media files such as audio.
* Fix incorrect link coloring in marked.js
* Issue #49: Images are inserted at the end of the document.

= 1.11.0

* Feature request #65: Spell check.
* Add new settings in the Preferences tab.

= 1.11.1

* Fix issue #68: Cannot display javascript template text correctly on code blocks.
* Fix issue: TOC should not be displayed on homepage and archive pages.
* Improve: Spellcheck compatibility check.
* Improve: Allow Markdown syntax in code blocks.

= 1.11.2

* Fix issue #31 - This issue occurred again because of the modification of #57.

= 1.11.3

* Fix issue #70: Bug occurs when adding featured image.
* Fix issue #71: Support Shortcodes Ultimate plugin.

= 1.11.4

* Fix issue #72: Syntax highlight - use prism autoloader plugin instead.
* Feature request #74: Add an option for match highlighter.

= 1.11.5

- Jetpack compatibility #80, #81, thanks @jeherve
- Fix issue #92
- Remove donation information.

= 1.11.6

- Fix issue #89 - Code block parsing problems.
- Fix issue #96 - KaTex not working when using only inline syntax.

= 1.11.7

- Fix issue #99 - a CSS conflict in menu page.
- Fix issue #91 - post_id check.
- Feature request #98 - Add a new option in Preferences.

= 1.11.8

- Fix issue #107 - Fix parsing inline KaTax syntax.
- Fix issue #104 - UL list does not display correctly in the preview panel.

= 1.11.9

- Fix issue #126 - Conflict with WordPress 5.3 ( `add_plugins_menu` function changes )
- PR #118 - Allow adding links to other file types rather than only inserting images.

= 1.12.0

- Feature request #75 - Save images to local folder.
- New feature - Syntax highlighter by highlight.js
- Hide `support comment` option because it doesn't work now.
- Update uninstall.php

= 1.12.1

- Move settings to the option submenu.

= 1.12.2

- Fix issue #144 - Deregistering `autosave` script gives error. (Fixed by alpipego. Thanks for contributing.)
- Fix issue #143 - Unable to upload images to sm.ms. (The v1 API is deprecated.)
- Fix issue #147 - Update CodeMirror themes.

= 1.13.0

- New feature - MathJax.

= 1.13.1

- Bug fix - Missing property in Markdown class.

= 1.14.0

- Feature request #86, #135 - Emojify module.
- Fix issue #163 - Update KaTex version.
- Fix issue #127, #149 - Link opening method.
- Fix issue #156 - Highlight C code block.
- Fix issue #120 - FlowChart identification code - flow, which occurs a conflict to Prism.
- Fix issue #103 - Markdown editor UI - add support to zh_CN

= 1.15.0

- New feature - Keyword suggestion tool.

= 1.15.1

- Fix issue #184 - The position of typing dialog should be under the focused line.
- Fix issue #187 - Task List - Compatible with PHP 7.4

= 1.15.2

- Fix errors in WP CLI.

= 1.16.0

- Fix issue #209 - HTML unescaped in code blocks.
- Fix issue #210 - Inline MathJax is not displayed.
- Upgrade Mermaid JavaScript library from 8.0.0 to 8.9.0
- Upgrade KaTax JavaScript library from 0.11.1 to 0.12.0
- Upgrade Flowchart.js JavaScript library from 1.11.3 to 1.14.1

= 1.16.1

- Fix issue #253 - KaTax... by xxNull-lsk
- Fix issue #252 - Bulk action... by hmaragy
- Improve #252 Update composer.json to allow bedrock installs by jawngee
- Fix issue #230 - jsdeliver url by Bronya0
- Test up to WordPress 5.8.1
- Test up to PHP 8.0

== Upgrade Notice ==

= Currently no logs now.
