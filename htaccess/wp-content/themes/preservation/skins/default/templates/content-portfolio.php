<?php
/**
 * The Portfolio template to display the content
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

$preservation_post_format = get_post_format();
$preservation_post_format = empty( $preservation_post_format ) ? 'standard' : str_replace( 'post-format-', '', $preservation_post_format );

?><div class="
<?php
if ( ! empty( $preservation_template_args['slider'] ) ) {
	echo ' slider-slide swiper-slide';
} else {
	echo ( preservation_is_blog_style_use_masonry( $preservation_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $preservation_columns ) : esc_attr( $preservation_columns_class ));
}
?>
"><article id="post-<?php the_ID(); ?>" 
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $preservation_post_format )
		. ' post_layout_portfolio'
		. ' post_layout_portfolio_' . esc_attr( $preservation_columns )
		. ( 'portfolio' != $preservation_blog_style[0] ? ' ' . esc_attr( $preservation_blog_style[0] )  . '_' . esc_attr( $preservation_columns ) : '' )
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

	$preservation_hover   = ! empty( $preservation_template_args['hover'] ) && ! preservation_is_inherit( $preservation_template_args['hover'] )
								? $preservation_template_args['hover']
								: preservation_get_theme_option( 'image_hover' );

	if ( 'dots' == $preservation_hover ) {
		$preservation_post_link = empty( $preservation_template_args['no_links'] )
								? ( ! empty( $preservation_template_args['link'] )
									? $preservation_template_args['link']
									: get_permalink()
									)
								: '';
		$preservation_target    = ! empty( $preservation_post_link ) && false === strpos( $preservation_post_link, home_url() )
								? ' target="_blank" rel="nofollow"'
								: '';
	}
	
	// Meta parts
	$preservation_components = ! empty( $preservation_template_args['meta_parts'] )
							? ( is_array( $preservation_template_args['meta_parts'] )
								? $preservation_template_args['meta_parts']
								: explode( ',', $preservation_template_args['meta_parts'] )
								)
							: preservation_array_get_keys_by_value( preservation_get_theme_option( 'meta_parts' ) );

	// Featured image
	preservation_show_post_featured( apply_filters( 'preservation_filter_args_featured',
        array(
			'hover'         => $preservation_hover,
			'no_links'      => ! empty( $preservation_template_args['no_links'] ),
			'thumb_size'    => ! empty( $preservation_template_args['thumb_size'] )
								? $preservation_template_args['thumb_size']
								: preservation_get_thumb_size(
									preservation_is_blog_style_use_masonry( $preservation_blog_style[0] )
										? (	strpos( preservation_get_theme_option( 'body_style' ), 'full' ) !== false || $preservation_columns < 3
											? 'masonry-big'
											: 'masonry'
											)
										: (	strpos( preservation_get_theme_option( 'body_style' ), 'full' ) !== false || $preservation_columns < 3
											? 'square'
											: 'square'
											)
								),
			'thumb_bg' => preservation_is_blog_style_use_masonry( $preservation_blog_style[0] ) ? false : true,
			'show_no_image' => true,
			'meta_parts'    => $preservation_components,
			'class'         => 'dots' == $preservation_hover ? 'hover_with_info' : '',
			'post_info'     => 'dots' == $preservation_hover
										? '<div class="post_info"><h5 class="post_title">'
											. ( ! empty( $preservation_post_link )
												? '<a href="' . esc_url( $preservation_post_link ) . '"' . ( ! empty( $target ) ? $target : '' ) . '>'
												: ''
												)
												. esc_html( get_the_title() ) 
											. ( ! empty( $preservation_post_link )
												? '</a>'
												: ''
												)
											. '</h5></div>'
										: '',
            'thumb_ratio'   => 'info' == $preservation_hover ?  '100:102' : '',
        ),
        'content-portfolio',
        $preservation_template_args
    ) );
	?>
</article></div><?php
// Need opening PHP-tag above, because <article> is a inline-block element (used as column)!