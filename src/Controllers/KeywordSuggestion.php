<?php
/**
 * Class KeywordSuggestion
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.15.0
 * @version 1.15.0
 */

namespace Githuber\Controller;

class KeywordSuggestion extends ControllerAbstract {

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
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
            add_action( 'wp_ajax_githuber_keyword_suggestion', array( $this, 'admin_githuber_keyword_suggestion' ) );

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
		wp_enqueue_script( 'githuber-md-ks', $this->githuber_plugin_url . 'assets/js/githuber-md-ks.js', array(), $this->version, true );

		$data['ajax_url'] = admin_url( 'admin-ajax.php' );
		$data['post_id']  = githuber_get_current_post_id();

		wp_localize_script( 'githuber-md-ks', 'ks_config', $data );
	}

	/**
	 * Register the `HtmlToMarkdown` meta box in the post-editor.
	 */
	public function add_meta_box() {
		
		if ( ! githuber_current_user_can( 'edit_posts' ) ) {
			return false;
		}

		add_meta_box(
			'keyword_suggesion_meta_box',
			__( 'Keyword Suggestions', 'wp-githuber-md' ) . '<div class="bg-icon-md"></div>',
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
		echo githuber_load_view( 'metabox/keyword-suggestion-tool' );
	}

	/**
	 * Do action hook for keyword suggestions.
	 */
	public function admin_githuber_keyword_suggestion() {

        if ( 
            isset( $_GET['_wpnonce'], $_GET['post_id'], $_GET['keyword'] ) && 
            wp_verify_nonce( $_GET['_wpnonce'], 'keyword_suggession_action' ) && 
            current_user_can( 'edit_post', $_GET['post_id'] )
        ) {

            // Default response.
            $response = array(
                'success' => false,
                'result'  => '',
            );

            if ( ! empty( $_GET['keyword'] ) ) {
                $keyword = $_GET['keyword'];
            }

            $lang = str_replace( '_', '-', get_locale() );

            $keyword_string = $this->query( $keyword, $lang );
            $keyword_list   = explode(',', $keyword_string);
            $result         = '';
    
            foreach ($keyword_list as $word) {
                $result .= '<span class="githuber-md-keyword">' . $word . '</span>';
            }

            if ( ! empty( $result ) ) {
                $response = array(
                    'success' => true,
                    'result'  => $result,
                );
            }
        
        } else {
            $response['error'] = __( 'Error while uploading file.', 'wp-githuber-md' );
        }

        header('Content-type: application/json');

		echo json_encode( $response );

		// To avoid wp_ajax return "0" string to break the vaild json string.
		wp_die();
    }
    
    /**
     * Query a keyword's long-tail terms through Google Suggestions.
     *
     * @param string $keyword A keyword that you want to
     * 
     * @return string
     */
    private function query( $keyword = '', $lang = 'en-US' ) {

        if ( ! empty( $keyword ) && strlen( $keyword ) > 4 ) {
    
            $url = 'http://suggestqueries.google.com/complete/search?output=firefox&client=firefox&hl=' . $lang . '&q=' . urlencode( $keyword );
            $ch  = curl_init( $url );

            curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch, CURLOPT_TIMEOUT, 5 );
            curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT x.y; Win64; x64; rv:10.0) Gecko/20100101 Firefox/10.0' );

            $data = curl_exec( $ch );

            curl_close($ch);

            if ( null !== ( $data = json_decode( $data, true ) ) ) {
                $keywords = $data[1];

                return implode( ',', $keywords );
            }
        }

        return '';
    }
}
