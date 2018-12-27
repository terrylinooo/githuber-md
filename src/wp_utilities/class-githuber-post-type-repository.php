<?php
/**
 * Githuber_Post_Type_Repository
 *
 * @package   Githuber
 * @author    Terry Lin <terrylinooo>
 * @license   GPLv3 (or later)
 * @link      https://terryl.in
 * @copyright 2018 Terry Lin
 */

/**
 * Githuber_Post_Type_Repository
 */
class Githuber_Post_Type_Repository {

	/**
	 * Constructer.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'save_post', array( $this, 'save_meta_box' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_enqueue_script' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
	}

	/**
	 * Register custom post type: Repository.
	 *
	 * @return void
	 */
	public function register_post_type() {
		register_post_type( 'repository',
			array(
				'labels' => array(
					'name'               => __( 'Repositories', 'wp-githuber-md' ),
					'singular_name'      => __( 'Repository', 'wp-githuber-md' ),
					'add_new'            => __( 'Add New', 'wp-githuber-md' ),
					'add_new_item'       => __( 'Add New Repository', 'wp-githuber-md' ),
					'edit'               => __( 'Edit', 'wp-githuber-md' ),
					'edit_item'          => __( 'Edit Repository', 'wp-githuber-md' ),
					'new_item'           => __( 'New Repository', 'wp-githuber-md' ),
					'view'               => __( 'View Repository', 'wp-githuber-md' ),
					'view_item'          => __( 'View Repository', 'wp-githuber-md' ),
					'search_items'       => __( 'Search Repository', 'wp-githuber-md' ),
					'not_found'          => __( 'No Repository Posts found', 'wp-githuber-md' ),
					'not_found_in_trash' => __( 'No Repository Posts found in Trash', 'wp-githuber-md' ),
				),

				'public'       => true,
				'hierarchical' => true,
				'has_archive'  => true,
				'can_export'   => true,
				'menu_icon'    => 'dashicons-lightbulb',
				'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'author' ),
				'taxonomies'   => array( 'post_tag', 'category' ),
			)
		);

		register_taxonomy_for_object_type( 'category', 'repository' );
		register_taxonomy_for_object_type( 'post_tag', 'repository' );
	}

	/**
	 * Create Custom meta box for Repository
	 *
	 * @return void
	 */
	public function add_meta_box() {
		add_meta_box(
			'repository_meta_box',            // id.
			'GitHub Repository',              // title.
			array( $this, 'show_meta_box' ),  // callback.
			'repository',                     // screen.
			'normal',                         // context.
			'high'                            // priority.
		);
	}

	/**
	 * Show custom meta box for Repository
	 *
	 * @return void
	 */
	public function show_meta_box() {
		global $post;
		$meta = get_post_meta( $post->ID, 'github_repository', true );

		?>

		<input type="hidden" name="metabox_nonce" value="<?php echo esc_html( wp_create_nonce( basename( __FILE__ ) ) ); ?>">
		<table>
			<tr>
				<td><strong>URL</strong></td>
				<td><input type="text" name="github_repository[url]" style="width: 100%" value="<?php echo esc_url( $meta['url'] ); ?>"></td>
			</tr>
			<tr>
				<td><strong>Buttons</strong></td>
				<td>
					<?php

					foreach ( array( 'star', 'fork', 'watch', 'issue', 'download' ) as $v ) :
						$checked = '';
						if ( ! empty( $meta[ $v ] ) ) {
							$checked = 'checked';
						}
					?>

					<label class="selectit"><input type="checkbox" name="github_repository[<?php echo $v; ?>]" value="<?php echo $v; ?>" <?php echo $checked; ?>> <?php echo ucfirst($v); ?></label> &nbsp;
					<?php endforeach; ?>
				</td>
			</tr>
		</table>
		</p>

		<?php
	}

	/**
	 * Save custom meta box for Repository
	 *
	 * @param integer $post_id Post's ID.
	 * @return integer if return.
	 */
	public function save_meta_box( $post_id ) {
		// verify nonce.
		if ( ! empty( $_POST['metabox_nonce'] ) && ! wp_verify_nonce( sanitize_key( $_POST['metabox_nonce'] ), basename( __FILE__ ) ) ) {
			return $post_id;
		}
		// check autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		// check permissions.
		if ( ! empty( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		if ( !empty( $_POST['github_repository'] ) ) {
			$old = get_post_meta( $post_id, 'github_repository', true );
			$new = $_POST['github_repository'];
	
			if ( $new && $new !== $old ) {
				update_post_meta( $post_id, 'github_repository', $new );
			} elseif ( '' === $new && $old ) {
				delete_post_meta( $post_id, 'github_repository', $old );
			}
		}
	}

	/**
	 * Register GitHub button script.
	 *
	 * @return void
	 */
	public function add_enqueue_script() {
		if ( is_single() && 'repository' === get_post_type() ) {
			wp_enqueue_script( 'github-buttons', 'https://buttons.github.io/buttons.js', [], false, true );
		}
	}
}