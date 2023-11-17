<?php
/**
 * The template to display the user's avatar, bio and socials on the Author page
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.71.0
 */
?>

<div class="author_page author vcard" itemprop="author" itemscope="itemscope" itemtype="<?php echo esc_attr( preservation_get_protocol( true ) ); ?>//schema.org/Person">

	<div class="author_avatar" itemprop="image">
		<?php
		$preservation_mult = preservation_get_retina_multiplier();
		echo get_avatar( get_the_author_meta( 'user_email' ), 120 * $preservation_mult );
		?>
	</div><!-- .author_avatar -->

	<h4 class="author_title" itemprop="name"><span class="fn"><?php the_author(); ?></span></h4>

	<?php
	$preservation_author_description = get_the_author_meta( 'description' );
	if ( ! empty( $preservation_author_description ) ) {
		?>
		<div class="author_bio" itemprop="description"><?php echo wp_kses( wpautop( $preservation_author_description ), 'preservation_kses_content' ); ?></div>
		<?php
	}
	?>

	<div class="author_details">
		<span class="author_posts_total">
			<?php
			$preservation_posts_total = count_user_posts( get_the_author_meta('ID'), 'post' );
			if ( $preservation_posts_total > 0 ) {
				// Translators: Add the author's posts number to the message
				echo wp_kses( sprintf( _n( '%s article published', '%s articles published', $preservation_posts_total, 'preservation' ),
										'<span class="author_posts_total_value">' . number_format_i18n( $preservation_posts_total ) . '</span>'
								 		),
							'preservation_kses_content'
							);
			} else {
				esc_html_e( 'No posts published.', 'preservation' );
			}
			?>
		</span><?php
			ob_start();
			do_action( 'preservation_action_user_meta', 'author-page' );
			$preservation_socials = ob_get_contents();
			ob_end_clean();
			preservation_show_layout( $preservation_socials,
				'<span class="author_socials"><span class="author_socials_caption">' . esc_html__( 'Follow:', 'preservation' ) . '</span>',
				'</span>'
			);
		?>
	</div><!-- .author_details -->

</div><!-- .author_page -->
