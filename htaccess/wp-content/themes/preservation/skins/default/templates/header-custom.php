<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0.06
 */

$preservation_header_css   = '';
$preservation_header_image = get_header_image();
$preservation_header_video = preservation_get_header_video();
if ( ! empty( $preservation_header_image ) && preservation_trx_addons_featured_image_override( is_singular() || preservation_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$preservation_header_image = preservation_get_current_mode_image( $preservation_header_image );
}

$preservation_header_id = preservation_get_custom_header_id();
$preservation_header_meta = get_post_meta( $preservation_header_id, 'trx_addons_options', true );
if ( ! empty( $preservation_header_meta['margin'] ) ) {
	preservation_add_inline_css( sprintf( '.page_content_wrap{padding-top:%s}', esc_attr( preservation_prepare_css_value( $preservation_header_meta['margin'] ) ) ) );
}

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr( $preservation_header_id ); ?> top_panel_custom_<?php echo esc_attr( sanitize_title( get_the_title( $preservation_header_id ) ) ); ?>
				<?php
				echo ! empty( $preservation_header_image ) || ! empty( $preservation_header_video )
					? ' with_bg_image'
					: ' without_bg_image';
				if ( '' != $preservation_header_video ) {
					echo ' with_bg_video';
				}
				if ( '' != $preservation_header_image ) {
					echo ' ' . esc_attr( preservation_add_inline_css_class( 'background-image: url(' . esc_url( $preservation_header_image ) . ');' ) );
				}
				if ( is_single() && has_post_thumbnail() ) {
					echo ' with_featured_image';
				}
				if ( preservation_is_on( preservation_get_theme_option( 'header_fullheight' ) ) ) {
					echo ' header_fullheight preservation-full-height';
				}
				$preservation_header_scheme = preservation_get_theme_option( 'header_scheme' );
				if ( ! empty( $preservation_header_scheme ) && ! preservation_is_inherit( $preservation_header_scheme  ) ) {
					echo ' scheme_' . esc_attr( $preservation_header_scheme );
				}
				?>
">
	<?php

	// Background video
	if ( ! empty( $preservation_header_video ) ) {
		get_template_part( apply_filters( 'preservation_filter_get_template_part', 'templates/header-video' ) );
	}

	// Custom header's layout
	do_action( 'preservation_action_show_layout', $preservation_header_id );

	// Header widgets area
	get_template_part( apply_filters( 'preservation_filter_get_template_part', 'templates/header-widgets' ) );

	?>
</header>
