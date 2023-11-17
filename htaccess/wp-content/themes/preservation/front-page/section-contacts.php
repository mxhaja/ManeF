<div class="front_page_section front_page_section_contacts<?php
	$preservation_scheme = preservation_get_theme_option( 'front_page_contacts_scheme' );
	if ( ! empty( $preservation_scheme ) && ! preservation_is_inherit( $preservation_scheme ) ) {
		echo ' scheme_' . esc_attr( $preservation_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( preservation_get_theme_option( 'front_page_contacts_paddings' ) );
	if ( preservation_get_theme_option( 'front_page_contacts_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$preservation_css      = '';
		$preservation_bg_image = preservation_get_theme_option( 'front_page_contacts_bg_image' );
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
	$preservation_anchor_icon = preservation_get_theme_option( 'front_page_contacts_anchor_icon' );
	$preservation_anchor_text = preservation_get_theme_option( 'front_page_contacts_anchor_text' );
if ( ( ! empty( $preservation_anchor_icon ) || ! empty( $preservation_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_contacts"'
									. ( ! empty( $preservation_anchor_icon ) ? ' icon="' . esc_attr( $preservation_anchor_icon ) . '"' : '' )
									. ( ! empty( $preservation_anchor_text ) ? ' title="' . esc_attr( $preservation_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_contacts_inner
	<?php
	if ( preservation_get_theme_option( 'front_page_contacts_fullheight' ) ) {
		echo ' preservation-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$preservation_css      = '';
			$preservation_bg_mask  = preservation_get_theme_option( 'front_page_contacts_bg_mask' );
			$preservation_bg_color_type = preservation_get_theme_option( 'front_page_contacts_bg_color_type' );
			if ( 'custom' == $preservation_bg_color_type ) {
				$preservation_bg_color = preservation_get_theme_option( 'front_page_contacts_bg_color' );
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
		<div class="front_page_section_content_wrap front_page_section_contacts_content_wrap content_wrap">
			<?php

			// Title and description
			$preservation_caption     = preservation_get_theme_option( 'front_page_contacts_caption' );
			$preservation_description = preservation_get_theme_option( 'front_page_contacts_description' );
			if ( ! empty( $preservation_caption ) || ! empty( $preservation_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				// Caption
				if ( ! empty( $preservation_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<h2 class="front_page_section_caption front_page_section_contacts_caption front_page_block_<?php echo ! empty( $preservation_caption ) ? 'filled' : 'empty'; ?>">
					<?php
						echo wp_kses( $preservation_caption, 'preservation_kses_content' );
					?>
					</h2>
					<?php
				}

				// Description
				if ( ! empty( $preservation_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<div class="front_page_section_description front_page_section_contacts_description front_page_block_<?php echo ! empty( $preservation_description ) ? 'filled' : 'empty'; ?>">
					<?php
						echo wp_kses( wpautop( $preservation_description ), 'preservation_kses_content' );
					?>
					</div>
					<?php
				}
			}

			// Content (text)
			$preservation_content = preservation_get_theme_option( 'front_page_contacts_content' );
			$preservation_layout  = preservation_get_theme_option( 'front_page_contacts_layout' );
			if ( 'columns' == $preservation_layout && ( ! empty( $preservation_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				<div class="front_page_section_columns front_page_section_contacts_columns columns_wrap">
					<div class="column-1_3">
				<?php
			}

			if ( ( ! empty( $preservation_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				<div class="front_page_section_content front_page_section_contacts_content front_page_block_<?php echo ! empty( $preservation_content ) ? 'filled' : 'empty'; ?>">
					<?php
					echo wp_kses( $preservation_content, 'preservation_kses_content' );
					?>
				</div>
				<?php
			}

			if ( 'columns' == $preservation_layout && ( ! empty( $preservation_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div><div class="column-2_3">
				<?php
			}

			// Shortcode output
			$preservation_sc = preservation_get_theme_option( 'front_page_contacts_shortcode' );
			if ( ! empty( $preservation_sc ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_output front_page_section_contacts_output front_page_block_<?php echo ! empty( $preservation_sc ) ? 'filled' : 'empty'; ?>">
					<?php
					preservation_show_layout( do_shortcode( $preservation_sc ) );
					?>
				</div>
				<?php
			}

			if ( 'columns' == $preservation_layout && ( ! empty( $preservation_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div></div>
				<?php
			}
			?>

		</div>
	</div>
</div>
