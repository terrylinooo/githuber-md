<?php
/**
 * Githuber functions.
 *
 * @package   Githuber
 * @author    Terry Lin <terrylinooo>
 * @license   GPLv3 (or later)
 * @link      https://terryl.in
 * @copyright 2018 Terry Lin
 */

remove_action( 'wp_head', 'feed_links_extra' );                // Display the links to the extra feeds such as category feeds.
remove_action( 'wp_head', 'feed_links' );                      // Display the links to the general feeds: Post and Comment Feed.
remove_action( 'wp_head', 'rsd_link' );                        // Display the link to the Really Simple Discovery service endpoint, EditURI link.
remove_action( 'wp_head', 'wlwmanifest_link' );                // Display the link to the Windows Live Writer manifest file.
remove_action( 'wp_head', 'index_rel_link' );                  // Index link.
remove_action( 'wp_head', 'parent_post_rel_link' );            // Prev link.
remove_action( 'wp_head', 'start_post_rel_link' );             // Start link.
remove_action( 'wp_head', 'adjacent_posts_rel_link' );         // Display relational links for the posts adjacent to the current post.
remove_action( 'wp_head', 'wp_generator' );                    // Display the XHTML generator that is generated on the wp_head hook, WP version.
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );