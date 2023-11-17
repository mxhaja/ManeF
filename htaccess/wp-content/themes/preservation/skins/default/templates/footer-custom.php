<?php
/**
 * The template to display default site footer
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0.10
 */

$preservation_footer_id = preservation_get_custom_footer_id();
$preservation_footer_meta = get_post_meta( $preservation_footer_id, 'trx_addons_options', true );
if ( ! empty( $preservation_footer_meta['margin'] ) ) {
	preservation_add_inline_css( sprintf( '.page_content_wrap{padding-bottom:%s}', esc_attr( preservation_prepare_css_value( $preservation_footer_meta['margin'] ) ) ) );
}
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr( $preservation_footer_id ); ?> footer_custom_<?php echo esc_attr( sanitize_title( get_the_title( $preservation_footer_id ) ) ); ?>
						<?php
						$preservation_footer_scheme = preservation_get_theme_option( 'footer_scheme' );
						if ( ! empty( $preservation_footer_scheme ) && ! preservation_is_inherit( $preservation_footer_scheme  ) ) {
							echo ' scheme_' . esc_attr( $preservation_footer_scheme );
						}
						?>
						">
	<?php
	// Custom footer's layout
	do_action( 'preservation_action_show_layout', $preservation_footer_id );
	?>
</footer><!-- /.footer_wrap -->
