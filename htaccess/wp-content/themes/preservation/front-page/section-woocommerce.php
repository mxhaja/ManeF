<?php
$preservation_woocommerce_sc = preservation_get_theme_option( 'front_page_woocommerce_products' );
if ( ! empty( $preservation_woocommerce_sc ) ) {
	?><div class="front_page_section front_page_section_woocommerce<?php
		$preservation_scheme = preservation_get_theme_option( 'front_page_woocommerce_scheme' );
		if ( ! empty( $preservation_scheme ) && ! preservation_is_inherit( $preservation_scheme ) ) {
			echo ' scheme_' . esc_attr( $preservation_scheme );
		}
		echo ' front_page_section_paddings_' . esc_attr( preservation_get_theme_option( 'front_page_woocommerce_paddings' ) );
		if ( preservation_get_theme_option( 'front_page_woocommerce_stack' ) ) {
			echo ' sc_stack_section_on';
		}
	?>"
			<?php
			$preservation_css      = '';
			$preservation_bg_image = preservation_get_theme_option( 'front_page_woocommerce_bg_image' );
			if ( ! empty( $preservation_bg_image ) ) {
				$preservation_css .= 'background-image: url(' . esc_url( preservation_get_attachment_url( $preservation_bg_image ) ) . ');';
			}
			if ( ! empty( $preservation_css ) ) {
				echo ' style="' . esc_attr( $preservation_css ) . '"';
			}
			?>
	>
	<?php
		// Add anchor
		$preservation_anchor_icon = preservation_get_theme_option( 'front_page_woocommerce_anchor_icon' );
		$preservation_anchor_text = preservation_get_theme_option( 'front_page_woocommerce_anchor_text' );
		if ( ( ! empty( $preservation_anchor_icon ) || ! empty( $preservation_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
			echo do_shortcode(
				'[trx_sc_anchor id="front_page_section_woocommerce"'
											. ( ! empty( $preservation_anchor_icon ) ? ' icon="' . esc_attr( $preservation_anchor_icon ) . '"' : '' )
											. ( ! empty( $preservation_anchor_text ) ? ' title="' . esc_attr( $preservation_anchor_text ) . '"' : '' )
											. ']'
			);
		}
	?>
		<div class="front_page_section_inner front_page_section_woocommerce_inner
			<?php
			if ( preservation_get_theme_option( 'front_page_woocommerce_fullheight' ) ) {
				echo ' preservation-full-height sc_layouts_flex sc_layouts_columns_middle';
			}
			?>
				"
				<?php
				$preservation_css      = '';
				$preservation_bg_mask  = preservation_get_theme_option( 'front_page_woocommerce_bg_mask' );
				$preservation_bg_color_type = preservation_get_theme_option( 'front_page_woocommerce_bg_color_type' );
				if ( 'custom' == $preservation_bg_color_type ) {
					$preservation_bg_color = preservation_get_theme_option( 'front_page_woocommerce_bg_color' );
				} elseif ( 'scheme_bg_color' == $preservation_bg_color_type ) {
					$preservation_bg_color = preservation_get_scheme_color( 'bg_color', $preservation_scheme );
				} else {
					$preservation_bg_color = '';
				}
				if ( ! empty( $preservation_bg_color ) && $preservation_bg_mask > 0 ) {
					$preservation_css .= 'background-color: ' . esc_attr(
						1 == $preservation_bg_mask ? $preservation_bg_color : preservation_hex2rgba( $preservation_bg_color, $preservation_bg_mask )
					) . ';';
				}
				if ( ! empty( $preservation_css ) ) {
					echo ' style="' . esc_attr( $preservation_css ) . '"';
				}
				?>
		>
			<div class="front_page_section_content_wrap front_page_section_woocommerce_content_wrap content_wrap woocommerce">
				<?php
				// Content wrap with title and description
				$preservation_caption     = preservation_get_theme_option( 'front_page_woocommerce_caption' );
				$preservation_description = preservation_get_theme_option( 'front_page_woocommerce_description' );
				if ( ! empty( $preservation_caption ) || ! empty( $preservation_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					// Caption
					if ( ! empty( $preservation_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
						?>
						<h2 class="front_page_section_caption front_page_section_woocommerce_caption front_page_block_<?php echo ! empty( $preservation_caption ) ? 'filled' : 'empty'; ?>">
						<?php
							echo wp_kses( $preservation_caption, 'preservation_kses_content' );
						?>
						</h2>
						<?php
					}

					// Description (text)
					if ( ! empty( $preservation_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
						?>
						<div class="front_page_section_description front_page_section_woocommerce_description front_page_block_<?php echo ! empty( $preservation_description ) ? 'filled' : 'empty'; ?>">
						<?php
							echo wp_kses( wpautop( $preservation_description ), 'preservation_kses_content' );
						?>
						</div>
						<?php
					}
				}

				// Content (widgets)
				?>
				<div class="front_page_section_output front_page_section_woocommerce_output list_products shop_mode_thumbs">
					<?php
					if ( 'products' == $preservation_woocommerce_sc ) {
						$preservation_woocommerce_sc_ids      = preservation_get_theme_option( 'front_page_woocommerce_products_per_page' );
						$preservation_woocommerce_sc_per_page = count( explode( ',', $preservation_woocommerce_sc_ids ) );
					} else {
						$preservation_woocommerce_sc_per_page = max( 1, (int) preservation_get_theme_option( 'front_page_woocommerce_products_per_page' ) );
					}
					$preservation_woocommerce_sc_columns = max( 1, min( $preservation_woocommerce_sc_per_page, (int) preservation_get_theme_option( 'front_page_woocommerce_products_columns' ) ) );
					echo do_shortcode(
						"[{$preservation_woocommerce_sc}"
										. ( 'products' == $preservation_woocommerce_sc
												? ' ids="' . esc_attr( $preservation_woocommerce_sc_ids ) . '"'
												: '' )
										. ( 'product_category' == $preservation_woocommerce_sc
												? ' category="' . esc_attr( preservation_get_theme_option( 'front_page_woocommerce_products_categories' ) ) . '"'
												: '' )
										. ( 'best_selling_products' != $preservation_woocommerce_sc
												? ' orderby="' . esc_attr( preservation_get_theme_option( 'front_page_woocommerce_products_orderby' ) ) . '"'
													. ' order="' . esc_attr( preservation_get_theme_option( 'front_page_woocommerce_products_order' ) ) . '"'
												: '' )
										. ' per_page="' . esc_attr( $preservation_woocommerce_sc_per_page ) . '"'
										. ' columns="' . esc_attr( $preservation_woocommerce_sc_columns ) . '"'
						. ']'
					);
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
