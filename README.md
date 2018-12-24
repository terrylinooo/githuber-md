# WP Githuber MD

![Screenshot](https://i.imgur.com/3O854Jm.png)

An all-in-on Markdown WordPress plugin and also improves [Githuber theme](https://github.com/terrylinooo/githuber) functionality.

## How it works

1. WP Githuber MD will save your Markdown content into `wp_posts`.`post_content_filtered`.
2. Parse the Markdown to HTML, save the parsed HTML content into `wp_posts`.`post_content`.

This plugin will detect your Markdown content and decide what scripts will be loaded, to avoid loading unnecessary scripts.
For example, if you enabled `syntax highlight`, you have to update your post again to take effects.

## Requirement

* PHP version > 5.6
* WordPress version > 4.7
* Tested up to 5.0.2

## Suggestions

The better situation to use this plugin is you just started a new blog.

If you're planning to use this plugin in an existing blog, be sure to:

- At the first, backup your data, we do not guarantee things work as expected.
- Turn off other Markdown plugins, because the similar plugins might do the same things when submitting your posts, may have some syntax conversion issues between Markdown and HTML.


## Features

* Markdown editor.
* Live preivew.
* [Image copy & paste](https://terryl.in/en/githuber-md-image-paste/).
* Syntax highlight.
* [Flow chart](https://terryl.in/en/githuber-md-flow-chart/).
* [KaTex](https://terryl.in/en/githuber-md-katax/).
* [Sequence diagram](https://terryl.in/en/githuber-md-sequence-diagrams/).
* Github flavored Markdown task list.
* Markdown extra...

### Features For Githuber Theme:

* Menu: Bootstrap 4 menu.
* Widget: Table of content.
* Post type: GitHub repository.

### Other screenshots

#### Image paste
![file](https://i.imgur.com/BQgn2So.gif)

#### Setting pages
![Screenshot](https://i.imgur.com/tpRAEI5.png)

![Screenshot](https://i.imgur.com/UylHwpr.png)

## Todo in the next version: 1.3.0

* HTML to Markdown convertor - An option allows you quicky convert all old posts into Markdown text, then you can use WP Githuber MD immediately in your existing blogs.

## Changelog

1.0.0

* First release.

1.1.0

* Add Image-Paste module.

1.2.0

* Image paste - Provide an option that allows you directly upload to Imgur.com.
* Rename plugin name from "Githuber MD" to "WP Githuber MD".
* Add new settings.



