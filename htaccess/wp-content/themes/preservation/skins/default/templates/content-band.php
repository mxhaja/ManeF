<?php
/**
 * 'Band' template to display the content
 *
 * Used for index/archive/search.
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.71.0
 */

$preservation_template_args = get_query_var( 'preservation_template_args' );

$preservation_columns       = 1;

$preservation_expanded      = ! preservation_sidebar_present() && preservation_get_theme_option( 'expand_content' ) == 'expand';

$preservation_post_format   = get_post_format();
$preservation_post_format   = empty( $preservation_post_format ) ? 'standard' : str_replace( 'post-format-', '', $preservation_post_format );

if ( is_array( $preservation_template_args ) ) {
	$preservation_columns    = empty( $preservation_template_args['columns'] ) ? 1 : max( 1, $preservation_template_args['columns'] );
	$preservation_blog_style = array( $preservation_template_args['type'], $preservation_columns );
	if ( ! empty( $preservation_template_args['slider'] ) ) {
		?><div class="slider-slide swiper-slide">
		<?php
	} elseif ( $preservation_columns > 1 ) {
	    $preservation_columns_class = preservation_get_column_class( 1, $preservation_columns, ! empty( $preservation_template_args['columns_tablet']) ? $preservation_template_args['columns_tablet'] : '', ! empty($preservation_template_args['columns_mobile']) ? $preservation_template_args['columns_mobile'] : '' );
				?><div class="<?php echo esc_attr( $preservation_columns_class ); ?>"><?php
	}
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class( 'post_item post_item_container post_layout_band post_format_' . esc_attr( $preservation_post_format ) );
	preservation_add_blog_animation( $preservation_template_args );
	?>
>
	<?php

	// Sticky label
	if ( is_sticky() && ! is_paged() ) {
		?>
		<span class="post_label label_sticky"></span>
		<?php
	}

	// Featured image
	$preservation_hover      = ! empty( $preservation_template_args['hover'] ) && ! preservation_is_inherit( $preservation_template_args['hover'] )
							? $preservation_template_args['hover']
							: preservation_get_theme_option( 'image_hover' );
	$preservation_components = ! empty( $preservation_template_args['meta_parts'] )
							? ( is_array( $preservation_template_args['meta_parts'] )
								? $preservation_template_args['meta_parts']
								: array_map( 'trim', explode( ',', $preservation_template_args['meta_parts'] ) )
								)
							: preservation_array_get_keys_by_value( preservation_get_theme_option( 'meta_parts' ) );
	preservation_show_post_featured( apply_filters( 'preservation_filter_args_featured',
		array(
			'no_links'   => ! empty( $preservation_template_args['no_links'] ),
			'hover'      => $preservation_hover,
			'meta_parts' => $preservation_components,
			'thumb_bg'   => true,
			'thumb_ratio'   => '1:1',
			'thumb_size' => ! empty( $preservation_template_args['thumb_size'] )
								? $preservation_template_args['thumb_size']
								: preservation_get_thumb_size( 
								in_array( $preservation_post_format, array( 'gallery', 'audio', 'video' ) )
									? ( strpos( preservation_get_theme_option( 'body_style' ), 'full' ) !== false
										? 'full'
										: ( $preservation_expanded 
											? 'big' 
											: 'medium-square'
											)
										)
									: 'masonry-big'
								)
		),
		'content-band',
		$preservation_template_args
	) );

	?><div class="post_content_wrap"><?php

		// Title and post meta
		$preservation_show_title = get_the_title() != '';
		$preservation_show_meta  = count( $preservation_components ) > 0 && ! in_array( $preservation_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );
		if ( $preservation_show_title ) {
			?>
			<div class="post_header entry-header">
				<?php
				// Categories
				if ( apply_filters( 'preservation_filter_show_blog_categories', $preservation_show_meta && in_array( 'categories', $preservation_components ), array( 'categories' ), 'band' ) ) {
					do_action( 'preservation_action_before_post_category' );
					?>
					<div class="post_category">
						<?php
						preservation_show_post_meta( apply_filters(
															'preservation_filter_post_meta_args',
															array(
																'components' => 'categories',
																'seo'        => false,
																'echo'       => true,
																'cat_sep'    => false,
																),
															'hover_' . $preservation_hover, 1
															)
											);
						?>
					</div>
					<?php
					$preservation_components = preservation_array_delete_by_value( $preservation_components, 'categories' );
					do_action( 'preservation_action_after_post_category' );
				}
				// Post title
				if ( apply_filters( 'preservation_filter_show_blog_title', true, 'band' ) ) {
					do_action( 'preservation_action_before_post_title' );
					if ( empty( $preservation_template_args['no_links'] ) ) {
						the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
					} else {
						the_title( '<h4 class="post_title entry-title">', '</h4>' );
					}
					do_action( 'preservation_action_after_post_title' );
				}
				?>
			</div><!-- .post_header -->
			<?php
		}

		// Post content
		if ( ! isset( $preservation_template_args['excerpt_length'] ) && ! in_array( $preservation_post_format, array( 'gallery', 'audio', 'video' ) ) ) {
			$preservation_template_args['excerpt_length'] = 13;
		}
		if ( apply_filters( 'preservation_filter_show_blog_excerpt', empty( $preservation_template_args['hide_excerpt'] ) && preservation_get_theme_option( 'excerpt_length' ) > 0, 'band' ) ) {
			?>
			<div class="post_content entry-content">
				<?php
				// Post content area
				preservation_show_post_content( $preservation_template_args, '<div class="post_content_inner">', '</div>' );
				?>
			</div><!-- .entry-content -->
			<?php
		}
		// Post meta
		if ( apply_filters( 'preservation_filter_show_blog_meta', $preservation_show_meta, $preservation_components, 'band' ) ) {
			if ( count( $preservation_components ) > 0 ) {
				do_action( 'preservation_action_before_post_meta' );
				preservation_show_post_meta(
					apply_filters(
						'preservation_filter_post_meta_args', array(
							'components' => join( ',', $preservation_components ),
							'seo'        => false,
							'echo'       => true,
						), 'band', 1
					)
				);
				do_action( 'preservation_action_after_post_meta' );
			}
		}
		// More button
		if ( apply_filters( 'preservation_filter_show_blog_readmore', ! $preservation_show_title || ! empty( $preservation_template_args['more_button'] ), 'band' ) ) {
			if ( empty( $preservation_template_args['no_links'] ) ) {
				do_action( 'preservation_action_before_post_readmore' );
				preservation_show_post_more_link( $preservation_template_args, '<div class="more-wrap">', '</div>' );
				do_action( 'preservation_action_after_post_readmore' );
			}
		}
		?>
	</div>
</article>
<?php

if ( is_array( $preservation_template_args ) ) {
	if ( ! empty( $preservation_template_args['slider'] ) || $preservation_columns > 1 ) {
		?>
		</div>
		<?php
	}
}
