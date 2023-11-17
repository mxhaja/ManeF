<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0
 */

// Page (category, tag, archive, author) title

if ( preservation_need_page_title() ) {
	preservation_sc_layouts_showed( 'title', true );
	preservation_sc_layouts_showed( 'postmeta', true );
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						// Post meta on the single post
						if ( is_single() ) {
							?>
							<div class="sc_layouts_title_meta">
							<?php
								preservation_show_post_meta(
									apply_filters(
										'preservation_filter_post_meta_args', array(
											'components' => join( ',', preservation_array_get_keys_by_value( preservation_get_theme_option( 'meta_parts' ) ) ),
											'counters'   => join( ',', preservation_array_get_keys_by_value( preservation_get_theme_option( 'counters' ) ) ),
											'seo'        => preservation_is_on( preservation_get_theme_option( 'seo_snippets' ) ),
										), 'header', 1
									)
								);
							?>
							</div>
							<?php
						}

						// Blog/Post title
						?>
						<div class="sc_layouts_title_title">
							<?php
							$preservation_blog_title           = preservation_get_blog_title();
							$preservation_blog_title_text      = '';
							$preservation_blog_title_class     = '';
							$preservation_blog_title_link      = '';
							$preservation_blog_title_link_text = '';
							if ( is_array( $preservation_blog_title ) ) {
								$preservation_blog_title_text      = $preservation_blog_title['text'];
								$preservation_blog_title_class     = ! empty( $preservation_blog_title['class'] ) ? ' ' . $preservation_blog_title['class'] : '';
								$preservation_blog_title_link      = ! empty( $preservation_blog_title['link'] ) ? $preservation_blog_title['link'] : '';
								$preservation_blog_title_link_text = ! empty( $preservation_blog_title['link_text'] ) ? $preservation_blog_title['link_text'] : '';
							} else {
								$preservation_blog_title_text = $preservation_blog_title;
							}
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr( $preservation_blog_title_class ); ?>">
								<?php
								$preservation_top_icon = preservation_get_term_image_small();
								if ( ! empty( $preservation_top_icon ) ) {
									$preservation_attr = preservation_getimagesize( $preservation_top_icon );
									?>
									<img src="<?php echo esc_url( $preservation_top_icon ); ?>" alt="<?php esc_attr_e( 'Site icon', 'preservation' ); ?>"
										<?php
										if ( ! empty( $preservation_attr[3] ) ) {
											preservation_show_layout( $preservation_attr[3] );
										}
										?>
									>
									<?php
								}
								echo wp_kses_data( $preservation_blog_title_text );
								?>
							</h1>
							<?php
							if ( ! empty( $preservation_blog_title_link ) && ! empty( $preservation_blog_title_link_text ) ) {
								?>
								<a href="<?php echo esc_url( $preservation_blog_title_link ); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html( $preservation_blog_title_link_text ); ?></a>
								<?php
							}

							// Category/Tag description
							if ( ! is_paged() && ( is_category() || is_tag() || is_tax() ) ) {
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
							}

							?>
						</div>
						<?php

						// Breadcrumbs
						ob_start();
						do_action( 'preservation_action_breadcrumbs' );
						$preservation_breadcrumbs = ob_get_contents();
						ob_end_clean();
						preservation_show_layout( $preservation_breadcrumbs, '<div class="sc_layouts_title_breadcrumbs">', '</div>' );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
