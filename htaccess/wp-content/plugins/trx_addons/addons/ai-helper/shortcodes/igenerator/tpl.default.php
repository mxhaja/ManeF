<?php
/**
 * The style "default" of the IGenerator
 *
 * @package ThemeREX Addons
 * @since v2.20.2
 */

$args = get_query_var('trx_addons_args_sc_igenerator');

$models = TrxAddons\AiHelper\Lists::get_list_ai_image_models();

?><div <?php if ( ! empty( $args['id'] ) ) echo ' id="' . esc_attr( $args['id'] ) . '"'; ?> 
	class="sc_igenerator sc_igenerator_<?php
		echo esc_attr( $args['type'] );
		if ( ! empty( $args['class'] ) ) echo ' ' . esc_attr( $args['class'] );
		?>"<?php
	if ( ! empty( $args['css'] ) ) echo ' style="' . esc_attr( $args['css'] ) . '"';
	trx_addons_sc_show_attributes( 'sc_igenerator', $args, 'sc_wrapper' );
	?>><?php

	trx_addons_sc_show_titles('sc_igenerator', $args);

	?><div class="sc_igenerator_content sc_item_content"<?php trx_addons_sc_show_attributes( 'sc_igenerator', $args, 'sc_items_wrapper' ); ?>>
		<div class="sc_igenerator_form<?php
			if ( ! empty( $args['align'] ) && ! trx_addons_is_off( $args['align'] ) ) {
				echo ' sc_igenerator_form_align_' . esc_attr( $args['align'] );
			}
			?>"
			data-igenerator-number="<?php echo esc_attr( $args['number'] ); ?>"
			data-igenerator-demo-images="<?php echo ! empty( $args['demo_images'] ) && ! empty( $args['demo_images'][0]['url'] ) ? '1' : ''; ?>"
			data-igenerator-settings="<?php
				echo esc_attr( trx_addons_encode_settings( array(
					'number' => $args['number'],
					'columns' => $args['columns'],
					'columns_tablet' => $args['columns_tablet'],
					'columns_mobile' => $args['columns_mobile'],
					'size' => $args['size'],
					'demo_thumb_size' => $args['demo_thumb_size'],
					'demo_images' => $args['demo_images'],
					'model' => $args['model'],
					'premium' => ! empty( $args['premium'] ) ? 1 : 0,
					'show_download' => ! empty( $args['show_download'] ) ? 1 : 0,
				) ) );
		?>">
			<div class="sc_igenerator_form_inner"<?php
				// If a shortcode is called not from Elementor, we need to add the width of the prompt field and alignment
				if ( empty( $args['prompt_width_extra'] ) ) {
					$css = '';
					if ( ! empty( $args['prompt_width'] ) && (int)$args['prompt_width'] < 100 ) {
						$css = 'width:' . esc_attr( $args['prompt_width'] ) . '%;';
					}
					if ( ! empty( $css ) ) {
						echo ' style="' . esc_attr( $css ) . '"';
					}
				}
			?>>
				<div class="sc_igenerator_form_field sc_igenerator_form_field_prompt<?php
					if ( ! empty( $args['show_settings'] ) && (int) $args['show_settings'] > 0 ) {
						echo ' sc_igenerator_form_field_prompt_with_settings';
					}
				?>">
					<div class="sc_igenerator_form_field_inner">
						<input type="text" value="<?php echo esc_attr( $args['prompt'] ); ?>" class="sc_igenerator_form_field_prompt_text" placeholder="<?php esc_attr_e('Describe what you want or hit a tag below', 'trx_addons'); ?>">
						<a href="#" class="sc_igenerator_form_field_prompt_button<?php if ( empty( $args['prompt'] ) ) echo ' sc_igenerator_form_field_prompt_button_disabled'; ?>"><?php
							if ( ! empty( $args['button_text'] ) ) {
								echo esc_html( $args['button_text'] );
							} else {
								esc_html_e('Generate', 'trx_addons');
							}
						?></a>
					</div>
					<?php if ( ! empty( $args['show_settings'] ) && (int) $args['show_settings'] > 0 ) { ?>
						<a href="#" class="sc_igenerator_form_settings_button trx_addons_icon-sliders"></a>
						<div class="sc_igenerator_form_settings"><?php
							if ( is_array( $models ) ) {
								foreach ( $models as $model => $title ) {
									$id = 'sc_igenerator_form_settings_field_model_' . str_replace( '/', '-', $model );
									?><div class="sc_igenerator_form_settings_field">
										<input type="radio" name="sc_igenerator_form_settings_field_model" value="<?php echo esc_attr( $model ); ?>"<?php
										if ( ! empty( $args['model'] ) && $args['model'] == $model ) {
											echo ' checked="checked"';
										}
									?> id="<?php echo esc_attr( $id ); ?>"><label for="<?php echo esc_attr( $id ); ?>"><?php
										echo esc_html( $title );
									?></label></div><?php
								}
							}
						?></div>
					<?php } ?>
				</div>
				<div class="sc_igenerator_form_field sc_igenerator_form_field_tags"><?php
					if ( ! empty( $args['tags_label'] ) ) {
						?><span class="sc_igenerator_form_field_tags_label"><?php echo esc_html( $args['tags_label'] ); ?></span><?php
					}
					if ( ! empty( $args['tags'] ) && is_array( $args['tags'] ) && count( $args['tags'] ) > 0 ) {
						?><span class="sc_igenerator_form_field_tags_list"><?php
							foreach ( $args['tags'] as $tag ) {
								?><a href="#" class="sc_igenerator_form_field_tags_item" data-tag-prompt="<?php echo esc_attr( $tag['prompt'] ); ?>"><?php echo esc_html( $tag['title'] ); ?></a><?php
							}
						?></span><?php
					}
				?></div>
			</div>
			<div class="trx_addons_loading">
			</div><?php
			if ( ! empty( $args['show_limits'] ) ) {
				$premium = ! empty( $args['premium'] ) && (int)$args['premium'] == 1;
				$suffix = $premium ? '_premium' : '';
				$limits = (int)trx_addons_get_option( "ai_helper_sc_igenerator_limits{$suffix}" ) > 0;
				if ( $limits ) {
					$generated = 0;
					if ( $premium ) {
						$user_id = get_current_user_id();
						$user_level = apply_filters( 'trx_addons_filter_sc_igenerator_user_level', $user_id > 0 ? 'default' : '', $user_id );
						if ( ! empty( $user_level ) ) {
							$levels = trx_addons_get_option( "ai_helper_sc_igenerator_levels_premium" );
							$level_idx = trx_addons_array_search( $levels, 'level', $user_level );
							$user_limit = $level_idx !== false ? $levels[ $level_idx ] : false;
							if ( isset( $user_limit['limit'] ) && trim( $user_limit['limit'] ) !== '' ) {
								$generated = trx_addons_sc_igenerator_get_total_generated( $user_limit['per'], $suffix, $user_id );
							}
						}
					}
					if ( ! $premium || empty( $user_level ) || ! isset( $user_limit['limit'] ) || trim( $user_limit['limit'] ) === '' ) {
						$generated = trx_addons_sc_igenerator_get_total_generated( 'hour', $suffix );
						$user_limit = array(
							'limit' => (int)trx_addons_get_option( "ai_helper_sc_igenerator_limit_per_hour{$suffix}" ),
							'requests' => (int)trx_addons_get_option( "ai_helper_sc_igenerator_limit_per_visitor{$suffix}" ),
							'per' => 'hour'
						);
					}
					if ( isset( $user_limit['limit'] ) && trim( $user_limit['limit'] ) !== '' ) {
						?><div class="sc_igenerator_limits">
							<span class="sc_igenerator_limits_total"><?php
								$periods = TrxAddons\AiHelper\Lists::get_list_ai_image_periods();
								echo wp_kses( sprintf(
													__( 'Limits%s: %s%s.', 'trx_addons' ),
													! empty( $periods[ $user_limit['per'] ] ) ? ' ' . sprintf( __( 'per %s', 'trx_addons' ), strtolower( $periods[ $user_limit['per'] ] ) ) : '',
													sprintf( __( '%s images', 'trx_addons' ), '<span class="sc_igenerator_limits_total_value">' . (int)$user_limit['limit'] . '</span>' ),
													! empty( $user_limit['requests'] ) ? sprintf( __( ', %s requests from a single visitor', 'trx_addons' ), '<span class="sc_igenerator_limits_total_requests">' . (int)$user_limit['requests'] . '</span>' ) : '',
												),
												'trx_addons_kses_content'
											);
							?></span>
							<span class="sc_igenerator_limits_used"><?php
								echo wp_kses( sprintf(
													__( 'Used: %s images%s.', 'trx_addons' ),
													'<span class="sc_igenerator_limits_used_value">' . min( $generated, (int)$user_limit['limit'] )  . '</span>',
													! empty( $user_limit['requests'] ) ? sprintf( __( ', %s requests', 'trx_addons' ), '<span class="sc_igenerator_limits_used_requests">' . (int)trx_addons_get_value_gpc( 'trx_addons_ai_helper_igenerator_count' ) . '</span>' ) : '',
												),
												'trx_addons_kses_content'
											);
							?></span>
						</div><?php
					}
				}
			}
			?><div class="sc_igenerator_message"<?php
				// If a shortcode is called not from Elementor, we need to add the width of the prompt field and alignment
				if ( empty( $args['prompt_width_extra'] ) ) {
					if ( ! empty( $args['prompt_width'] ) && (int)$args['prompt_width'] < 100 ) {
						echo ' style="max-width:' . esc_attr( $args['prompt_width'] ) . '%"';
					}
				}
			?>>
				<div class="sc_igenerator_message_inner"></div>
				<a href="#" class="sc_igenerator_message_close trx_addons_button_close" title="<?php esc_html_e( 'Close', 'trx_addons' ); ?>"><span class="trx_addons_button_close_icon"></span></a>
			</div>
		</div>
		<div class="sc_igenerator_images sc_igenerator_images_columns_<?php echo esc_attr( $args['columns'] ); ?> sc_igenerator_images_size_<?php echo esc_attr( $args['size'] ); ?>"></div>
	</div>

	<?php trx_addons_sc_show_links('sc_igenerator', $args); ?>

</div>