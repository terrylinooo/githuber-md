# WP Githuber MD

![Screenshot](https://i.imgur.com/3O854Jm.png)

An all-in-on Markdown WordPress plugin and also improves [Githuber theme](https://github.com/terrylinooo/githuber) functionality.

## How it works

1. WP Githuber MD will save your Markdown content into `wp_posts`.`post_content_filtered`.
2. Parse the Markdown to HTML, save the parsed HTML content into `wp_posts`.`post_content`.

This plugin will detect your Markdown content and decide what scripts will be loaded, to avoid loading unnecessary scripts.
For example, if you enabled `syntax highlight`, you have to update your post again to take effects.

## Suggestions

The better situation to use this plugin is you just started a new blog.

If you're planning to use this plugin in an existing blog, be sure to:

- At the first, backup your data, we do not guarantee things work as expected.
- Turn off other Markdown plugins, because the similar plugins might do the same things when submitting your posts, may have some syntax conversion issues between Markdown and HTML.


## Features

* Markdown editor.
* Live preivew.
* Image copy & paste.
* Syntax highlight.
* Flow chart.
* KaTex.
* Sequence diagram.
* Github flavored Markdown task list.
* Markdown extra...

### Features For Githuber Theme:

* Menu: Bootstrap 4 menu.
* Widget: Table of content.
* Post type: GitHub repository.

### Other screenshots

![Screenshot](https://i.imgur.com/yamYEN8.png)

![Screenshot](https://i.imgur.com/CxvZERS.png)



