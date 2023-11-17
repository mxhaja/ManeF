<div class="front_page_section front_page_section_blog<?php
	$preservation_scheme = preservation_get_theme_option( 'front_page_blog_scheme' );
	if ( ! empty( $preservation_scheme ) && ! preservation_is_inherit( $preservation_scheme ) ) {
		echo ' scheme_' . esc_attr( $preservation_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( preservation_get_theme_option( 'front_page_blog_paddings' ) );
	if ( preservation_get_theme_option( 'front_page_blog_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$preservation_css      = '';
		$preservation_bg_image = preservation_get_theme_option( 'front_page_blog_bg_image' );
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
	$preservation_anchor_icon = preservation_get_theme_option( 'front_page_blog_anchor_icon' );
	$preservation_anchor_text = preservation_get_theme_option( 'front_page_blog_anchor_text' );
if ( ( ! empty( $preservation_anchor_icon ) || ! empty( $preservation_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_blog"'
									. ( ! empty( $preservation_anchor_icon ) ? ' icon="' . esc_attr( $preservation_anchor_icon ) . '"' : '' )
									. ( ! empty( $preservation_anchor_text ) ? ' title="' . esc_attr( $preservation_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_blog_inner
	<?php
	if ( preservation_get_theme_option( 'front_page_blog_fullheight' ) ) {
		echo ' preservation-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$preservation_css      = '';
			$preservation_bg_mask  = preservation_get_theme_option( 'front_page_blog_bg_mask' );
			$preservation_bg_color_type = preservation_get_theme_option( 'front_page_blog_bg_color_type' );
			if ( 'custom' == $preservation_bg_color_type ) {
				$preservation_bg_color = preservation_get_theme_option( 'front_page_blog_bg_color' );
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
		<div class="front_page_section_content_wrap front_page_section_blog_content_wrap content_wrap">
			<?php
			// Caption
			$preservation_caption = preservation_get_theme_option( 'front_page_blog_caption' );
			if ( ! empty( $preservation_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<h2 class="front_page_section_caption front_page_section_blog_caption front_page_block_<?php echo ! empty( $preservation_caption ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( $preservation_caption, 'preservation_kses_content' ); ?></h2>
				<?php
			}

			// Description (text)
			$preservation_description = preservation_get_theme_option( 'front_page_blog_description' );
			if ( ! empty( $preservation_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_description front_page_section_blog_description front_page_block_<?php echo ! empty( $preservation_description ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( wpautop( $preservation_description ), 'preservation_kses_content' ); ?></div>
				<?php
			}

			// Content (widgets)
			?>
			<div class="front_page_section_output front_page_section_blog_output">
				<?php
				if ( is_active_sidebar( 'front_page_blog_widgets' ) ) {
					dynamic_sidebar( 'front_page_blog_widgets' );
				} elseif ( current_user_can( 'edit_theme_options' ) ) {
					if ( ! preservation_exists_trx_addons() ) {
						preservation_customizer_need_trx_addons_message();
					} else {
						preservation_customizer_need_widgets_message( 'front_page_blog_caption', 'ThemeREX Addons - Blogger' );
					}
				}
				?>
			</div>
		</div>
	</div>
</div>
