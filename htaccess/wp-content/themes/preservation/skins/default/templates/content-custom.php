<?php
/**
 * The custom template to display the content
 *
 * Used for index/archive/search.
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0.50
 */

$preservation_template_args = get_query_var( 'preservation_template_args' );
if ( is_array( $preservation_template_args ) ) {
	$preservation_columns    = empty( $preservation_template_args['columns'] ) ? 2 : max( 1, $preservation_template_args['columns'] );
	$preservation_blog_style = array( $preservation_template_args['type'], $preservation_columns );
} else {
	$preservation_blog_style = explode( '_', preservation_get_theme_option( 'blog_style' ) );
	$preservation_columns    = empty( $preservation_blog_style[1] ) ? 2 : max( 1, $preservation_blog_style[1] );
}
$preservation_blog_id       = preservation_get_custom_blog_id( join( '_', $preservation_blog_style ) );
$preservation_blog_style[0] = str_replace( 'blog-custom-', '', $preservation_blog_style[0] );
$preservation_expanded      = ! preservation_sidebar_present() && preservation_get_theme_option( 'expand_content' ) == 'expand';
$preservation_components    = ! empty( $preservation_template_args['meta_parts'] )
							? ( is_array( $preservation_template_args['meta_parts'] )
								? join( ',', $preservation_template_args['meta_parts'] )
								: $preservation_template_args['meta_parts']
								)
							: preservation_array_get_keys_by_value( preservation_get_theme_option( 'meta_parts' ) );
$preservation_post_format   = get_post_format();
$preservation_post_format   = empty( $preservation_post_format ) ? 'standard' : str_replace( 'post-format-', '', $preservation_post_format );

$preservation_blog_meta     = preservation_get_custom_layout_meta( $preservation_blog_id );
$preservation_custom_style  = ! empty( $preservation_blog_meta['scripts_required'] ) ? $preservation_blog_meta['scripts_required'] : 'none';

if ( ! empty( $preservation_template_args['slider'] ) || $preservation_columns > 1 || ! preservation_is_off( $preservation_custom_style ) ) {
	?><div class="
		<?php
		if ( ! empty( $preservation_template_args['slider'] ) ) {
			echo 'slider-slide swiper-slide';
		} else {
			echo esc_attr( ( preservation_is_off( $preservation_custom_style ) ? 'column' : sprintf( '%1$s_item %1$s_item', $preservation_custom_style ) ) . "-1_{$preservation_columns}" );
		}
		?>
	">
	<?php
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
			'post_item post_item_container post_format_' . esc_attr( $preservation_post_format )
					. ' post_layout_custom post_layout_custom_' . esc_attr( $preservation_columns )
					. ' post_layout_' . esc_attr( $preservation_blog_style[0] )
					. ' post_layout_' . esc_attr( $preservation_blog_style[0] ) . '_' . esc_attr( $preservation_columns )
					. ( ! preservation_is_off( $preservation_custom_style )
						? ' post_layout_' . esc_attr( $preservation_custom_style )
							. ' post_layout_' . esc_attr( $preservation_custom_style ) . '_' . esc_attr( $preservation_columns )
						: ''
						)
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
	// Custom layout
	do_action( 'preservation_action_show_layout', $preservation_blog_id, get_the_ID() );
	?>
</article><?php
if ( ! empty( $preservation_template_args['slider'] ) || $preservation_columns > 1 || ! preservation_is_off( $preservation_custom_style ) ) {
	?></div><?php
	// Need opening PHP-tag above just after </div>, because <div> is a inline-block element (used as column)!
}
