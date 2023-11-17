<?php
/**
 * The template to display the socials in the footer
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0.10
 */


// Socials
if ( preservation_is_on( preservation_get_theme_option( 'socials_in_footer' ) ) ) {
	$preservation_output = preservation_get_socials_links();
	if ( '' != $preservation_output ) {
		?>
		<div class="footer_socials_wrap socials_wrap">
			<div class="footer_socials_inner">
				<?php preservation_show_layout( $preservation_output ); ?>
			</div>
		</div>
		<?php
	}
}
