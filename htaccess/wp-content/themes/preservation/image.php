<?php
/**
 * The template to display the attachment
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0
 */


get_header();

while ( have_posts() ) {
	the_post();

	// Display post's content
	get_template_part( apply_filters( 'preservation_filter_get_template_part', 'templates/content', 'single-' . preservation_get_theme_option( 'single_style' ) ), 'single-' . preservation_get_theme_option( 'single_style' ) );

	// Parent post navigation.
	$preservation_posts_navigation = preservation_get_theme_option( 'posts_navigation' );
	if ( 'links' == $preservation_posts_navigation ) {
		?>
		<div class="nav-links-single<?php
			if ( ! preservation_is_off( preservation_get_theme_option( 'posts_navigation_fixed' ) ) ) {
				echo ' nav-links-fixed fixed';
			}
		?>">
			<?php
			the_post_navigation( apply_filters( 'preservation_filter_post_navigation_args', array(
					'prev_text' => '<span class="nav-arrow"></span>'
						. '<span class="meta-nav" aria-hidden="true">' . esc_html__( 'Published in', 'preservation' ) . '</span> '
						. '<span class="screen-reader-text">' . esc_html__( 'Previous post:', 'preservation' ) . '</span> '
						. '<h5 class="post-title">%title</h5>'
						. '<span class="post_date">%date</span>',
			), 'image' ) );
			?>
		</div>
		<?php
	}

	// Comments
	do_action( 'preservation_action_before_comments' );
	comments_template();
	do_action( 'preservation_action_after_comments' );
}

get_footer();
