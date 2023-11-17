<?php
/**
 * The template to display single post
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0
 */

// Full post loading
$full_post_loading          = preservation_get_value_gp( 'action' ) == 'full_post_loading';

// Prev post loading
$prev_post_loading          = preservation_get_value_gp( 'action' ) == 'prev_post_loading';
$prev_post_loading_type     = preservation_get_theme_option( 'posts_navigation_scroll_which_block' );

// Position of the related posts
$preservation_related_position   = preservation_get_theme_option( 'related_position' );

// Type of the prev/next post navigation
$preservation_posts_navigation   = preservation_get_theme_option( 'posts_navigation' );
$preservation_prev_post          = false;
$preservation_prev_post_same_cat = preservation_get_theme_option( 'posts_navigation_scroll_same_cat' );

// Rewrite style of the single post if current post loading via AJAX and featured image and title is not in the content
if ( ( $full_post_loading 
		|| 
		( $prev_post_loading && 'article' == $prev_post_loading_type )
	) 
	&& 
	! in_array( preservation_get_theme_option( 'single_style' ), array( 'style-6' ) )
) {
	preservation_storage_set_array( 'options_meta', 'single_style', 'style-6' );
}

do_action( 'preservation_action_prev_post_loading', $prev_post_loading, $prev_post_loading_type );

get_header();

while ( have_posts() ) {

	the_post();

	// Type of the prev/next post navigation
	if ( 'scroll' == $preservation_posts_navigation ) {
		$preservation_prev_post = get_previous_post( $preservation_prev_post_same_cat );  // Get post from same category
		if ( ! $preservation_prev_post && $preservation_prev_post_same_cat ) {
			$preservation_prev_post = get_previous_post( false );                    // Get post from any category
		}
		if ( ! $preservation_prev_post ) {
			$preservation_posts_navigation = 'links';
		}
	}

	// Override some theme options to display featured image, title and post meta in the dynamic loaded posts
	if ( $full_post_loading || ( $prev_post_loading && $preservation_prev_post ) ) {
		preservation_sc_layouts_showed( 'featured', false );
		preservation_sc_layouts_showed( 'title', false );
		preservation_sc_layouts_showed( 'postmeta', false );
	}

	// If related posts should be inside the content
	if ( strpos( $preservation_related_position, 'inside' ) === 0 ) {
		ob_start();
	}

	// Display post's content
	get_template_part( apply_filters( 'preservation_filter_get_template_part', 'templates/content', 'single-' . preservation_get_theme_option( 'single_style' ) ), 'single-' . preservation_get_theme_option( 'single_style' ) );

	// If related posts should be inside the content
	if ( strpos( $preservation_related_position, 'inside' ) === 0 ) {
		$preservation_content = ob_get_contents();
		ob_end_clean();

		ob_start();
		do_action( 'preservation_action_related_posts' );
		$preservation_related_content = ob_get_contents();
		ob_end_clean();

		if ( ! empty( $preservation_related_content ) ) {
			$preservation_related_position_inside = max( 0, min( 9, preservation_get_theme_option( 'related_position_inside' ) ) );
			if ( 0 == $preservation_related_position_inside ) {
				$preservation_related_position_inside = mt_rand( 1, 9 );
			}

			$preservation_p_number         = 0;
			$preservation_related_inserted = false;
			$preservation_in_block         = false;
			$preservation_content_start    = strpos( $preservation_content, '<div class="post_content' );
			$preservation_content_end      = strrpos( $preservation_content, '</div>' );

			for ( $i = max( 0, $preservation_content_start ); $i < min( strlen( $preservation_content ) - 3, $preservation_content_end ); $i++ ) {
				if ( $preservation_content[ $i ] != '<' ) {
					continue;
				}
				if ( $preservation_in_block ) {
					if ( strtolower( substr( $preservation_content, $i + 1, 12 ) ) == '/blockquote>' ) {
						$preservation_in_block = false;
						$i += 12;
					}
					continue;
				} else if ( strtolower( substr( $preservation_content, $i + 1, 10 ) ) == 'blockquote' && in_array( $preservation_content[ $i + 11 ], array( '>', ' ' ) ) ) {
					$preservation_in_block = true;
					$i += 11;
					continue;
				} else if ( 'p' == $preservation_content[ $i + 1 ] && in_array( $preservation_content[ $i + 2 ], array( '>', ' ' ) ) ) {
					$preservation_p_number++;
					if ( $preservation_related_position_inside == $preservation_p_number ) {
						$preservation_related_inserted = true;
						$preservation_content = ( $i > 0 ? substr( $preservation_content, 0, $i ) : '' )
											. $preservation_related_content
											. substr( $preservation_content, $i );
					}
				}
			}
			if ( ! $preservation_related_inserted ) {
				if ( $preservation_content_end > 0 ) {
					$preservation_content = substr( $preservation_content, 0, $preservation_content_end ) . $preservation_related_content . substr( $preservation_content, $preservation_content_end );
				} else {
					$preservation_content .= $preservation_related_content;
				}
			}
		}

		preservation_show_layout( $preservation_content );
	}

	// Comments
	do_action( 'preservation_action_before_comments' );
	comments_template();
	do_action( 'preservation_action_after_comments' );

	// Related posts
	if ( 'below_content' == $preservation_related_position
		&& ( 'scroll' != $preservation_posts_navigation || preservation_get_theme_option( 'posts_navigation_scroll_hide_related' ) == 0 )
		&& ( ! $full_post_loading || preservation_get_theme_option( 'open_full_post_hide_related' ) == 0 )
	) {
		do_action( 'preservation_action_related_posts' );
	}

	// Post navigation: type 'scroll'
	if ( 'scroll' == $preservation_posts_navigation && ! $full_post_loading ) {
		?>
		<div class="nav-links-single-scroll"
			data-post-id="<?php echo esc_attr( get_the_ID( $preservation_prev_post ) ); ?>"
			data-post-link="<?php echo esc_attr( get_permalink( $preservation_prev_post ) ); ?>"
			data-post-title="<?php the_title_attribute( array( 'post' => $preservation_prev_post ) ); ?>"
			<?php do_action( 'preservation_action_nav_links_single_scroll_data', $preservation_prev_post ); ?>
		></div>
		<?php
	}
}

get_footer();
