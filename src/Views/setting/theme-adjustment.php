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
 * @version 1.2.0
 */
?>

<pre style="font-size: 13px;">
remove_action( 'wp_head', 'feed_links_extra' );                
remove_action( 'wp_head', 'feed_links' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'parent_post_rel_link' );
remove_action( 'wp_head', 'start_post_rel_link' );
remove_action( 'wp_head', 'adjacent_posts_rel_link' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
</pre>