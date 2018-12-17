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

global $pagenow;

if ( 'plugins.php' !== $pagenow ) {
	/**
	 * The author card.
	 *
	 * @param integer $avatar_size The avatar size.
	 * @param string  $icon_size   The social icon size. sm: 24px. md: 32px. lg: 48px. xl: 64px.
	 *
	 * @return void
	 */
	function githuber_author_card( $avatar_size = 96, $icon_size = 'sm' ) {
		$description = get_the_author_meta( 'description' );
		$pattern     = get_shortcode_regex();
		$author_link = '';

		if ( preg_match_all( '/' . $pattern . '/s', $description, $matches ) ) {
			$all_matches = [];
			foreach ( $matches[0] as $shortcode ) {
				$all_matches[] = $shortcode;
				$author_link  .= do_shortcode( $shortcode );
			}
			$description = str_replace( $all_matches, '', $description );
		}
		?>
			<h3 class="section-title"><?php esc_html_e( 'Author', 'githuber' ); ?></h3>
			<aside class="author-card" itemscope itemprop="author" itemtype="http://schema.org/Person">
				<div class="author-avatar">
					<img src="<?php echo esc_url( get_avatar_url( get_the_author_meta( 'ID' ), array( 'size' => $avatar_size ) ) ); ?>" class="rounded-circle" itemprop="image">
				</div>
				<div class="author-info">
					<div class="author-title">
						<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" itemprop="name">
							<?php echo esc_html( get_the_author_meta( 'display_name' ) ); ?>
						</a>
					</div>
					<div class="author-description" itemprop="description">  
						<?php echo $description; ?>
					</div>
					<div class="author-links brand-<?php echo $icon_size; ?>">
						<?php echo $author_link; ?>
					</div>
				</div>
			</aside>
		<?php
	}
}
