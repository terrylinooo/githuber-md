<?php
/**
 * Class HtmlToMarkdown
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.3.0
 * @version 1.4.2
 */

namespace Githuber\Controller;

class FetchRemoteImage extends ControllerAbstract {

	/**
	 * The remote image URL list.
	 *
	 * @var array
	 */
	public static $image_list = array();

	/**
	 * Constructer.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Initialize.
	 */
	public function init() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	/**
	 * Initalize to WP `admin_init` hook.
	 */
	public function admin_init() {
		$user          = wp_get_current_user();
		$allowed_roles = array( 'editor', 'administrator', 'author' );

		// For security reasons, only authorized logged-in users can update content.
		if ( array_intersect( $allowed_roles, $user->roles ) || is_super_admin() ) {

			// Add the sidebar metabox to posts.
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		}
	}

	/**
	 * Register CSS style files.
	 */
	public function admin_enqueue_styles( $hook_suffix ) {

	}

	/**
	 * Register JS files.
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {

	}

	/**
	 * Register the `HtmlToMarkdown` meta box in the post-editor.
	 */
	public function add_meta_box() {
		
		if ( ! githuber_current_user_can( 'edit_posts' ) ) {
			return false;
		}

		add_meta_box(
			'remote_image_meta_box',
			__( 'Fetch Remote Image', 'wp-githuber-md' ) . '<div class="bg-icon-md"></div>',
			array( $this, 'show_meta_box' ),
			null,
			'side',
			'high'
		);
	}
	
	/**
	 * Show `HtmlToMarkdown` meta box.
	 */
	public function show_meta_box() {
		echo githuber_load_view( 'metabox/fetch-remote-image' );
	}

    /**
     * Find all remote image URLs, fetch them and save them into local folder.
     * 
     * @param string $post_content Post content
     * @return string
     */
    public static function covert( $post_content ) {

        preg_match_all( '/<img.*?src=[\'"](.*?)[\'"].*?>/i', $post_content, $matches );

        $img_elements = $matches[1];
        $site_url     = str_replace( array( 'https', 'http' ), '', get_site_url() );

        foreach( $img_elements as $i => $img_remote_url ) {

            if ( strpos( $img_remote_url, $site_url ) !== false ) {
				// Yep, the two images are in the same domain name.
				// Nothing to do.
            } else {
		
				$grabbed_image_content = self::grab_image( $img_remote_url );

				if ( ! empty( $grabbed_image_content ) ) {
					$new_url = self::save_image( $grabbed_image_content, $img_remote_url );
				}

                self::$image_list[ $i ]['before'] = $img_remote_url;
                self::$image_list[ $i ]['after']  = $new_url;
			}
		}
		
		// Replace the remote image URLs with the new local images.
		foreach ( self::$image_list as $image_info ) {
			$post_content = str_replace( $image_info['before'], $image_info['after'], $post_content );
		}

		return $post_content;
    }

    /**
     * Grab remote image.
     *
     * @param string $url    The remote target.
     * @return bool
     */
    public static function grab_image( $url ) {

        $ch = curl_init( $url );

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13' );
    
		$raw = curl_exec($ch);
    
        $http_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
        curl_close ($ch);
    
        if ( 200 === $http_response_code ) {
            return $raw;
        } else {
            // The remote site probably blocks this connection.
            return '';
        }
	}
	
	/**
	 * Save images into local folder.
	 *
	 * @param string $content The image content.
	 * @param string $url     The remote image's URL.
	 * @return void
	 */
	public static function save_image( $content,  $url )
	{
		$image_info = getimagesize( $url );

		$post_id = githuber_get_current_post_id();

		if ( empty( $post_id ) ) {
			return '';
		}

		$ext = '';
		if ( ! empty( $image_info['mime'] )) {
			if ( 'image/png' === $image_info['mime'] ) {
				$ext = 'png';
			} elseif ( 'image/gif' === $image_info['mime'] ) {
				$ext = 'gif';
			} elseif ( 'image/jepg' === $image_info['mime'] ) {
				$ext = 'jpg';
			}
		} else {
			return '';
		}

		$upload_dir  = wp_upload_dir();
		$upload_path = $upload_dir['path'];
		$online_path = $upload_dir['url'];

		$filename = 'post-' . $post_id . '-' . uniqid() . '.' . $ext;

		file_put_contents( $upload_path . '/' . $filename, $content );

		if ( is_ssl() ) {
			$online_path = str_replace( 'http://', 'https://', $online_path );
		}

		return $online_path . '/' . $filename;
	}
}