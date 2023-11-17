<?php
/**
 * The template to display default site footer
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0.10
 */

?>
<footer class="footer_wrap footer_default
<?php
$preservation_footer_scheme = preservation_get_theme_option( 'footer_scheme' );
if ( ! empty( $preservation_footer_scheme ) && ! preservation_is_inherit( $preservation_footer_scheme  ) ) {
	echo ' scheme_' . esc_attr( $preservation_footer_scheme );
}
?>
				">
	<?php

	// Footer widgets area
	get_template_part( apply_filters( 'preservation_filter_get_template_part', 'templates/footer-widgets' ) );

	// Logo
	get_template_part( apply_filters( 'preservation_filter_get_template_part', 'templates/footer-logo' ) );

	// Socials
	get_template_part( apply_filters( 'preservation_filter_get_template_part', 'templates/footer-socials' ) );

	// Copyright area
	get_template_part( apply_filters( 'preservation_filter_get_template_part', 'templates/footer-copyright' ) );

	?>
</footer><!-- /.footer_wrap -->
