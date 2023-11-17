<?php
/**
 * The template to display the background video in the header
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0.14
 */
$preservation_header_video = preservation_get_header_video();
$preservation_embed_video  = '';
if ( ! empty( $preservation_header_video ) && ! preservation_is_from_uploads( $preservation_header_video ) ) {
	if ( preservation_is_youtube_url( $preservation_header_video ) && preg_match( '/[=\/]([^=\/]*)$/', $preservation_header_video, $matches ) && ! empty( $matches[1] ) ) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr( $matches[1] ); ?>"></div>
		<?php
	} else {
		?>
		<div id="background_video"><?php preservation_show_layout( preservation_get_embed_video( $preservation_header_video ) ); ?></div>
		<?php
	}
}
