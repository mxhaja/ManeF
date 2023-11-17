<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0.10
 */

// Copyright area
?> 
<div class="footer_copyright_wrap
<?php
$preservation_copyright_scheme = preservation_get_theme_option( 'copyright_scheme' );
if ( ! empty( $preservation_copyright_scheme ) && ! preservation_is_inherit( $preservation_copyright_scheme  ) ) {
	echo ' scheme_' . esc_attr( $preservation_copyright_scheme );
}
?>
				">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text">
			<?php
				$preservation_copyright = preservation_get_theme_option( 'copyright' );
			if ( ! empty( $preservation_copyright ) ) {
				// Replace {{Y}} or {Y} with the current year
				$preservation_copyright = str_replace( array( '{{Y}}', '{Y}' ), date( 'Y' ), $preservation_copyright );
				// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
				$preservation_copyright = preservation_prepare_macros( $preservation_copyright );
				// Display copyright
				echo wp_kses( nl2br( $preservation_copyright ), 'preservation_kses_content' );
			}
			?>
			</div>
		</div>
	</div>
</div>
