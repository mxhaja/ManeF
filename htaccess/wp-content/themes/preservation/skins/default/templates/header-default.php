<?php
/**
 * The template to display default site header
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0
 */

$preservation_header_css   = '';
$preservation_header_image = get_header_image();
$preservation_header_video = preservation_get_header_video();
if ( ! empty( $preservation_header_image ) && preservation_trx_addons_featured_image_override( is_singular() || preservation_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$preservation_header_image = preservation_get_current_mode_image( $preservation_header_image );
}

?><header class="top_panel top_panel_default
	<?php
	echo ! empty( $preservation_header_image ) || ! empty( $preservation_header_video ) ? ' with_bg_image' : ' without_bg_image';
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

	// Main menu
	get_template_part( apply_filters( 'preservation_filter_get_template_part', 'templates/header-navi' ) );

	// Mobile header
	if ( preservation_is_on( preservation_get_theme_option( 'header_mobile_enabled' ) ) ) {
		get_template_part( apply_filters( 'preservation_filter_get_template_part', 'templates/header-mobile' ) );
	}

	// Page title and breadcrumbs area
	if ( ! is_single() ) {
		get_template_part( apply_filters( 'preservation_filter_get_template_part', 'templates/header-title' ) );
	}

	// Header widgets area
	get_template_part( apply_filters( 'preservation_filter_get_template_part', 'templates/header-widgets' ) );
	?>
</header>
