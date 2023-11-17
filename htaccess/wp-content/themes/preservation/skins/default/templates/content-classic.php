<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0
 */

$preservation_template_args = get_query_var( 'preservation_template_args' );

if ( is_array( $preservation_template_args ) ) {
	$preservation_columns    = empty( $preservation_template_args['columns'] ) ? 2 : max( 1, $preservation_template_args['columns'] );
	$preservation_blog_style = array( $preservation_template_args['type'], $preservation_columns );
    $preservation_columns_class = preservation_get_column_class( 1, $preservation_columns, ! empty( $preservation_template_args['columns_tablet']) ? $preservation_template_args['columns_tablet'] : '', ! empty($preservation_template_args['columns_mobile']) ? $preservation_template_args['columns_mobile'] : '' );
} else {
	$preservation_blog_style = explode( '_', preservation_get_theme_option( 'blog_style' ) );
	$preservation_columns    = empty( $preservation_blog_style[1] ) ? 2 : max( 1, $preservation_blog_style[1] );
    $preservation_columns_class = preservation_get_column_class( 1, $preservation_columns );
}
$preservation_expanded   = ! preservation_sidebar_present() && preservation_get_theme_option( 'expand_content' ) == 'expand';

$preservation_post_format = get_post_format();
$preservation_post_format = empty( $preservation_post_format ) ? 'standard' : str_replace( 'post-format-', '', $preservation_post_format );

?><div class="<?php
	if ( ! empty( $preservation_template_args['slider'] ) ) {
		echo ' slider-slide swiper-slide';
	} else {
		echo ( preservation_is_blog_style_use_masonry( $preservation_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $preservation_columns ) : esc_attr( $preservation_columns_class ) );
	}
?>"><article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $preservation_post_format )
				. ' post_layout_classic post_layout_classic_' . esc_attr( $preservation_columns )
				. ' post_layout_' . esc_attr( $preservation_blog_style[0] )
				. ' post_layout_' . esc_attr( $preservation_blog_style[0] ) . '_' . esc_attr( $preservation_columns )
	);
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
								: explode( ',', $preservation_template_args['meta_parts'] )
								)
							: preservation_array_get_keys_by_value( preservation_get_theme_option( 'meta_parts' ) );

	preservation_show_post_featured( apply_filters( 'preservation_filter_args_featured',
		array(
			'thumb_size' => ! empty( $preservation_template_args['thumb_size'] )
				? $preservation_template_args['thumb_size']
				: preservation_get_thumb_size(
				'classic' == $preservation_blog_style[0]
						? ( strpos( preservation_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $preservation_columns > 2 ? 'big' : 'huge' )
								: ( $preservation_columns > 2
									? ( $preservation_expanded ? 'square' : 'square' )
									: ($preservation_columns > 1 ? 'square' : ( $preservation_expanded ? 'huge' : 'big' ))
									)
							)
						: ( strpos( preservation_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $preservation_columns > 2 ? 'masonry-big' : 'full' )
								: ($preservation_columns === 1 ? ( $preservation_expanded ? 'huge' : 'big' ) : ( $preservation_columns <= 2 && $preservation_expanded ? 'masonry-big' : 'masonry' ))
							)
			),
			'hover'      => $preservation_hover,
			'meta_parts' => $preservation_components,
			'no_links'   => ! empty( $preservation_template_args['no_links'] ),
        ),
        'content-classic',
        $preservation_template_args
    ) );

	// Title and post meta
	$preservation_show_title = get_the_title() != '';
	$preservation_show_meta  = count( $preservation_components ) > 0 && ! in_array( $preservation_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $preservation_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php

			// Post meta
			if ( apply_filters( 'preservation_filter_show_blog_meta', $preservation_show_meta, $preservation_components, 'classic' ) ) {
				if ( count( $preservation_components ) > 0 ) {
					do_action( 'preservation_action_before_post_meta' );
					preservation_show_post_meta(
						apply_filters(
							'preservation_filter_post_meta_args', array(
							'components' => join( ',', $preservation_components ),
							'seo'        => false,
							'echo'       => true,
						), $preservation_blog_style[0], $preservation_columns
						)
					);
					do_action( 'preservation_action_after_post_meta' );
				}
			}

			// Post title
			if ( apply_filters( 'preservation_filter_show_blog_title', true, 'classic' ) ) {
				do_action( 'preservation_action_before_post_title' );
				if ( empty( $preservation_template_args['no_links'] ) ) {
					the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
				} else {
					the_title( '<h4 class="post_title entry-title">', '</h4>' );
				}
				do_action( 'preservation_action_after_post_title' );
			}

			if( !in_array( $preservation_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
				// More button
				if ( apply_filters( 'preservation_filter_show_blog_readmore', ! $preservation_show_title || ! empty( $preservation_template_args['more_button'] ), 'classic' ) ) {
					if ( empty( $preservation_template_args['no_links'] ) ) {
						do_action( 'preservation_action_before_post_readmore' );
						preservation_show_post_more_link( $preservation_template_args, '<div class="more-wrap">', '</div>' );
						do_action( 'preservation_action_after_post_readmore' );
					}
				}
			}
			?>
		</div><!-- .entry-header -->
		<?php
	}

	// Post content
	if( in_array( $preservation_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
		ob_start();
		if (apply_filters('preservation_filter_show_blog_excerpt', empty($preservation_template_args['hide_excerpt']) && preservation_get_theme_option('excerpt_length') > 0, 'classic')) {
			preservation_show_post_content($preservation_template_args, '<div class="post_content_inner">', '</div>');
		}
		// More button
		if(! empty( $preservation_template_args['more_button'] )) {
			if ( empty( $preservation_template_args['no_links'] ) ) {
				do_action( 'preservation_action_before_post_readmore' );
				preservation_show_post_more_link( $preservation_template_args, '<div class="more-wrap">', '</div>' );
				do_action( 'preservation_action_after_post_readmore' );
			}
		}
		$preservation_content = ob_get_contents();
		ob_end_clean();
		preservation_show_layout($preservation_content, '<div class="post_content entry-content">', '</div><!-- .entry-content -->');
	}
	?>

</article></div><?php
// Need opening PHP-tag above, because <div> is a inline-block element (used as column)!
