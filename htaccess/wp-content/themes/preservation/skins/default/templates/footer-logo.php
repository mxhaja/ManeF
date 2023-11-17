<?php
/**
 * The template to display the site logo in the footer
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0.10
 */

// Logo
if ( preservation_is_on( preservation_get_theme_option( 'logo_in_footer' ) ) ) {
	$preservation_logo_image = preservation_get_logo_image( 'footer' );
	$preservation_logo_text  = get_bloginfo( 'name' );
	if ( ! empty( $preservation_logo_image['logo'] ) || ! empty( $preservation_logo_text ) ) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if ( ! empty( $preservation_logo_image['logo'] ) ) {
					$preservation_attr = preservation_getimagesize( $preservation_logo_image['logo'] );
					echo '<a href="' . esc_url( home_url( '/' ) ) . '">'
							. '<img src="' . esc_url( $preservation_logo_image['logo'] ) . '"'
								. ( ! empty( $preservation_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $preservation_logo_image['logo_retina'] ) . ' 2x"' : '' )
								. ' class="logo_footer_image"'
								. ' alt="' . esc_attr__( 'Site logo', 'preservation' ) . '"'
								. ( ! empty( $preservation_attr[3] ) ? ' ' . wp_kses_data( $preservation_attr[3] ) : '' )
							. '>'
						. '</a>';
				} elseif ( ! empty( $preservation_logo_text ) ) {
					echo '<h1 class="logo_footer_text">'
							. '<a href="' . esc_url( home_url( '/' ) ) . '">'
								. esc_html( $preservation_logo_text )
							. '</a>'
						. '</h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
