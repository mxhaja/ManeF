<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0
 */

							do_action( 'preservation_action_page_content_end_text' );
							
							// Widgets area below the content
							preservation_create_widgets_area( 'widgets_below_content' );
						
							do_action( 'preservation_action_page_content_end' );
							?>
						</div>
						<?php
						
						do_action( 'preservation_action_after_page_content' );

						// Show main sidebar
						get_sidebar();

						do_action( 'preservation_action_content_wrap_end' );
						?>
					</div>
					<?php

					do_action( 'preservation_action_after_content_wrap' );

					// Widgets area below the page and related posts below the page
					$preservation_body_style = preservation_get_theme_option( 'body_style' );
					$preservation_widgets_name = preservation_get_theme_option( 'widgets_below_page' );
					$preservation_show_widgets = ! preservation_is_off( $preservation_widgets_name ) && is_active_sidebar( $preservation_widgets_name );
					$preservation_show_related = preservation_is_single() && preservation_get_theme_option( 'related_position' ) == 'below_page';
					if ( $preservation_show_widgets || $preservation_show_related ) {
						if ( 'fullscreen' != $preservation_body_style ) {
							?>
							<div class="content_wrap">
							<?php
						}
						// Show related posts before footer
						if ( $preservation_show_related ) {
							do_action( 'preservation_action_related_posts' );
						}

						// Widgets area below page content
						if ( $preservation_show_widgets ) {
							preservation_create_widgets_area( 'widgets_below_page' );
						}
						if ( 'fullscreen' != $preservation_body_style ) {
							?>
							</div>
							<?php
						}
					}
					do_action( 'preservation_action_page_content_wrap_end' );
					?>
			</div>
			<?php
			do_action( 'preservation_action_after_page_content_wrap' );

			// Don't display the footer elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ( ! preservation_is_singular( 'post' ) && ! preservation_is_singular( 'attachment' ) ) || ! in_array ( preservation_get_value_gp( 'action' ), array( 'full_post_loading', 'prev_post_loading' ) ) ) {
				
				// Skip link anchor to fast access to the footer from keyboard
				?>
				<a id="footer_skip_link_anchor" class="preservation_skip_link_anchor" href="#"></a>
				<?php

				do_action( 'preservation_action_before_footer' );

				// Footer
				$preservation_footer_type = preservation_get_theme_option( 'footer_type' );
				if ( 'custom' == $preservation_footer_type && ! preservation_is_layouts_available() ) {
					$preservation_footer_type = 'default';
				}
				get_template_part( apply_filters( 'preservation_filter_get_template_part', "templates/footer-" . sanitize_file_name( $preservation_footer_type ) ) );

				do_action( 'preservation_action_after_footer' );

			}
			?>

			<?php do_action( 'preservation_action_page_wrap_end' ); ?>

		</div>

		<?php do_action( 'preservation_action_after_page_wrap' ); ?>

	</div>

	<?php do_action( 'preservation_action_after_body' ); ?>

	<?php wp_footer(); ?>

</body>
</html>