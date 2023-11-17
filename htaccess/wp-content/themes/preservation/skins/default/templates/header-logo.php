<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0
 */

$preservation_args = get_query_var( 'preservation_logo_args' );

// Site logo
$preservation_logo_type   = isset( $preservation_args['type'] ) ? $preservation_args['type'] : '';
$preservation_logo_image  = preservation_get_logo_image( $preservation_logo_type );
$preservation_logo_text   = preservation_is_on( preservation_get_theme_option( 'logo_text' ) ) ? get_bloginfo( 'name' ) : '';
$preservation_logo_slogan = get_bloginfo( 'description', 'display' );
if ( ! empty( $preservation_logo_image['logo'] ) || ! empty( $preservation_logo_text ) ) {
	?><a class="sc_layouts_logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php
		if ( ! empty( $preservation_logo_image['logo'] ) ) {
			if ( empty( $preservation_logo_type ) && function_exists( 'the_custom_logo' ) && is_numeric($preservation_logo_image['logo']) && (int) $preservation_logo_image['logo'] > 0 ) {
				the_custom_logo();
			} else {
				$preservation_attr = preservation_getimagesize( $preservation_logo_image['logo'] );
				echo '<img src="' . esc_url( $preservation_logo_image['logo'] ) . '"'
						. ( ! empty( $preservation_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $preservation_logo_image['logo_retina'] ) . ' 2x"' : '' )
						. ' alt="' . esc_attr( $preservation_logo_text ) . '"'
						. ( ! empty( $preservation_attr[3] ) ? ' ' . wp_kses_data( $preservation_attr[3] ) : '' )
						. '>';
			}
		} else {
			preservation_show_layout( preservation_prepare_macros( $preservation_logo_text ), '<span class="logo_text">', '</span>' );
			preservation_show_layout( preservation_prepare_macros( $preservation_logo_slogan ), '<span class="logo_slogan">', '</span>' );
		}
		?>
	</a>
	<?php
}
