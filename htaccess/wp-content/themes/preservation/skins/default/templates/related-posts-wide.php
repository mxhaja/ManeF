<?php
/**
 * The template 'Style 5' to displaying related posts
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0.54
 */

$preservation_link        = get_permalink();
$preservation_post_format = get_post_format();
$preservation_post_format = empty( $preservation_post_format ) ? 'standard' : str_replace( 'post-format-', '', $preservation_post_format );
?><div id="post-<?php the_ID(); ?>" <?php post_class( 'related_item post_format_' . esc_attr( $preservation_post_format ) ); ?> data-post-id="<?php the_ID(); ?>">
	<?php
	preservation_show_post_featured(
		array(
			'thumb_size'    => apply_filters( 'preservation_filter_related_thumb_size', preservation_get_thumb_size( (int) preservation_get_theme_option( 'related_posts' ) == 1 ? 'big' : 'med' ) ),
		)
	);
	?>
	<div class="post_header entry-header">
		<h6 class="post_title entry-title"><a href="<?php echo esc_url( $preservation_link ); ?>"><?php
			if ( '' == get_the_title() ) {
				esc_html_e( '- No title -', 'preservation' );
			} else {
				the_title();
			}
		?></a></h6>
		<?php
		if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
			?>
			<div class="post_meta">
				<a href="<?php echo esc_url( $preservation_link ); ?>" class="post_meta_item post_date"><?php echo wp_kses_data( preservation_get_date() ); ?></a>
			</div>
			<?php
		}
		?>
	</div>
</div>
