<?php
/**
 * Theme customizer
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0
 */


//--------------------------------------------------------------
//-- First run actions after switch theme
//--------------------------------------------------------------
if ( ! function_exists( 'preservation_customizer_action_switch_theme' ) ) {
	add_action( 'after_switch_theme', 'preservation_customizer_action_switch_theme' );
	/**
	 * Duplicate theme options from the main theme (template) to the child theme
	 * and regenerate CSS with custom colors and fonts after a theme switched.
	 *
	 * Hooks: add_action( 'after_switch_theme', 'preservation_customizer_action_switch_theme' );
	 */
	function preservation_customizer_action_switch_theme() {
		// Duplicate theme options between parent and child themes
		$duplicate = preservation_get_theme_setting( 'duplicate_options' );
		if ( in_array( $duplicate, array( 'child', 'both' ) ) ) {
			$theme_slug      = get_template();
			$theme_time      = (int) get_option( "preservation_options_timestamp_{$theme_slug}" );
			$stylesheet_slug = get_stylesheet();

			// If child-theme is activated - duplicate options from template to the child-theme
			if ( $theme_slug != $stylesheet_slug ) {
				$stylesheet_time = (int) get_option( "preservation_options_timestamp_{$stylesheet_slug}" );
				if ( $theme_time > $stylesheet_time ) {
					preservation_customizer_duplicate_theme_options( $theme_slug, $stylesheet_slug, $theme_time );
				}

				// If main theme (template) is activated and 'duplicate_options' == 'child'
				// (duplicate options only from template to the child-theme) - regenerate CSS with custom colors and fonts
			} elseif ( 'child' == $duplicate && $theme_time > 0 ) {
				preservation_customizer_save_css();
			}
		}
	}
}

if ( ! function_exists( 'preservation_customizer_duplicate_theme_options' ) ) {
	/**
	 * Duplicate a theme options between the template and the child-theme.
	 *
	 * @param string $from    Theme slug where options will be copied from.
	 * @param string $to      Theme slug where options will be copied to
	 * @param int $timestamp  Options copy time (timestamp).
	 */
	function preservation_customizer_duplicate_theme_options( $from, $to, $timestamp = 0 ) {
		if ( 0 == $timestamp ) {
			$timestamp = get_option( "preservation_options_timestamp_{$from}" );
		}
		$from         = "theme_mods_{$from}";
		$from_options = get_option( $from );
		$to           = "theme_mods_{$to}";
		$to_options   = get_option( $to );
		if ( is_array( $from_options ) ) {
			if ( ! is_array( $to_options ) ) {
				$to_options = array();
			}
			// List of theme options to duplicate
			$theme_options = preservation_storage_get( 'options' );
			// List of core options to duplicate
			$additional_options = apply_filters( 'preservation_filter_duplicate_core_options_list', array(
				'header_image',
				'header_image_data',
				'header_video',
				'external_header_video',
				'background_color',
				'background_image',
			) );
			// Start duplicate
			foreach ( $from_options as $k => $v ) {
				if ( apply_filters( 'preservation_filter_duplicate_theme_option',
						isset( $theme_options[ $k ] )                           // If it's a theme option
						|| in_array( $k, $additional_options ),                 // or one of core options from list
						$k,
						$v
						)
				) {
					$to_options[ $k ] = $v;
				}
			}
			update_option( $to, $to_options );
			update_option( "preservation_options_timestamp_{$to}", $timestamp );
		}
	}
}


//--------------------------------------------------------------
//-- Add a new panel to the Customizer Controls
//--------------------------------------------------------------
if ( ! function_exists( 'preservation_customizer_setup3' ) ) {
	add_action( 'after_setup_theme', 'preservation_customizer_setup3', 3 );
	/**
	 * Add a new panel 'Plugins settings' to the Theme Options.
	 *
	 * Hooks: add_action( 'after_setup_theme', 'preservation_customizer_setup3', 3 ); // 3 - add/remove Theme Options elements
	 */
	function preservation_customizer_setup3() {
		preservation_storage_merge_array( 'options', '', array(
			'cpt' => array(
				'title'    => esc_html__( 'Plugins settings', 'preservation' ),
				'desc'     => '',
				'priority' => 400,
				'icon'     => 'icon-plugins',
				'type'     => 'panel',
			),
		) );
	}
}

if ( ! function_exists( 'preservation_customizer_setup4' ) ) {
	add_action( 'after_setup_theme', 'preservation_customizer_setup4', 4 );
	/**
	 * Finish (close) a new panel 'Plugins settings' in the Theme Options.
	 *
	 * Hooks: add_action( 'after_setup_theme', 'preservation_customizer_setup4', 4 );
	 */
	function preservation_customizer_setup4() {
		preservation_storage_merge_array( 'options', '', array(
			'cpt_end' => array(
				'type' => 'panel_end',
			),
		) );
	}
}

if ( ! function_exists( 'preservation_customizer_setup3_2' ) ) {
	add_action( 'after_setup_theme', 'preservation_customizer_setup3_2', 3 );
	/**
	 * Add an option 'Show helpers' to the Theme Options after the option 'color_schemes_info'
	 *
	 * Hooks: add_action( 'after_setup_theme', 'preservation_customizer_setup3_2', 3 ); // 3 - add/remove Theme Options elements
	 */
	function preservation_customizer_setup3_2() {
		preservation_storage_set_array_after( 'options', 'color_schemes_info', array(
			'color_scheme_helpers' => array(
				'title'      => esc_html__( 'Show helpers', 'preservation' ),
				'desc'       => wp_kses_data( __( 'Display color scheme helpers in Customizer over each block with assigned color scheme', 'preservation' ) ),
				'std'        => 0,
				'refresh'    => false,
				'pro_only'   => PRESERVATION_THEME_FREE,
				'type'       => 'switch',
			),
		) );
	}
}


//--------------------------------------------------------------
//-- Register Customizer Controls
//--------------------------------------------------------------

// Start priority for the new controls
define( 'PRESERVATION_CUSTOMIZE_PRIORITY', 200 );

if ( ! function_exists( 'preservation_customizer_custom_controls' ) ) {
	add_action( 'customize_register', 'preservation_customizer_custom_controls' );
	/**
	 * Include the file 'theme-options/theme-customizer-controls.php' with a custom controls definitions for the Customizer.
	 *
	 * Hooks: add_action( 'customize_register', 'preservation_customizer_custom_controls' );
	 *
	 * @param object $wp_customize  An object with instance of the class WP_Customize. Not used in this function.
	 */
	function preservation_customizer_custom_controls( $wp_customize ) {
		require_once PRESERVATION_THEME_DIR . 'theme-options/theme-customizer-controls.php';
	}
}

if ( ! function_exists( 'preservation_customizer_register_controls' ) ) {
	add_action( 'customize_register', 'preservation_customizer_register_controls', 20 );
	/**
	 * Parse the array with Theme Options and add controls to the customizer for each field.
	 *
	 * Hooks: add_action( 'customize_register', 'preservation_customizer_register_controls', 20 );
	 *
	 * @param object $wp_customize  An object with instance of the class WP_Customize.
	 */
	function preservation_customizer_register_controls( $wp_customize ) {

		$is_demo = false;
		if ( is_admin() ) {
			$user = wp_get_current_user();
			$is_demo = is_object( $user ) && ! empty( $user->data->user_login ) && 'backstage_customizer_user' == $user->data->user_login;
		}

		$refresh_auto = preservation_get_theme_setting( 'customize_refresh' ) != 'manual';

		$panels   = array( '' );
		$p        = 0;
		$sections = array( '' );
		$s        = 0;

		$expand = array();

		$i = PRESERVATION_CUSTOMIZE_PRIORITY;

		// Reload Theme Options before create controls
		if ( is_admin() ) {
			preservation_storage_set( 'options_reloaded', true );
			preservation_load_theme_options();
		}

		$options = preservation_storage_get( 'options' );

		foreach ( $options as $id => $opt ) {
			$i = ! empty( $opt['priority'] )
					? $opt['priority']
					: ( in_array( $opt['type'], array( 'panel', 'section' ) )
							? PRESERVATION_CUSTOMIZE_PRIORITY
							: $i++
						);

			if ( ! empty( $opt['hidden'] ) ) {
				continue;
			}

			if ( $is_demo && empty( $opt['demo'] ) ) {
				continue;
			}

			if ( ! isset( $opt['title'] ) ) {
				$opt['title'] = '';
			}
			if ( ! isset( $opt['desc'] ) ) {
				$opt['desc'] = '';
			}

			$transport = $refresh_auto && ( ! isset( $opt['refresh'] ) || true === $opt['refresh'] ) ? 'refresh' : 'postMessage';

			if ( ! empty( $opt['override'] ) ) {
				$opt['title'] .= ' *';
			}

			// URL to redirect preview area and/or JS callback on expand panel
			if ( in_array( $opt['type'], array( 'panel', 'section' ) ) && ! empty( $opt['expand_url'] ) || ! empty( $opt['expand_callback'] ) ) {
				$expand[ $id ] = array( 'type' => $opt['type'] );
				if ( ! empty( $opt['expand_url'] ) ) {
					$expand[ $id ]['url'] = $opt['expand_url'];
				}
				if ( ! empty( $opt['expand_callback'] ) ) {
					$expand[ $id ]['callback'] = $opt['expand_callback'];
				}
			}

			if ( 'panel' == $opt['type'] ) {

				if ( $p > 0 ) {
					array_pop( $panels );
					$p--;
				}
				if ( $s > 0 ) {
					array_pop( $sections );
					$s--;
				}

				$sec = $wp_customize->get_panel( $id );
				if ( is_object( $sec ) && ! empty( $sec->title ) ) {
					$sec->title       = $opt['title'];
					$sec->description = $opt['desc'];
					if ( ! empty( $opt['priority'] ) ) {
						$sec->priority = $opt['priority'];
					}
					if ( ! empty( $opt['active_callback'] ) ) {
						$sec->active_callback = $opt['active_callback'];
					}
				} else {
					$wp_customize->add_panel(
						esc_attr( $id ), array(
							'title'           => $opt['title'],
							'description'     => $opt['desc'],
							'priority'        => $i,
							'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
						)
					);
				}

				array_push( $panels, $id );
				$p++;

			} elseif ( 'panel_end' == $opt['type'] ) {

				if ( $p > 0 ) {
					array_pop( $panels );
					$p--;
				}

			} elseif ( 'section' == $opt['type'] ) {

				if ( $s > 0 ) {
					array_pop( $sections );
					$s--;
				}

				$sec = $wp_customize->get_section( $id );
				if ( is_object( $sec ) && ! empty( $sec->title ) ) {
					$sec->title       = $opt['title'];
					$sec->description = $opt['desc'];
					$sec->panel       = esc_attr( $panels[ $p ] );
					if ( ! empty( $opt['priority'] ) ) {
						$sec->priority = $opt['priority'];
					}
					if ( ! empty( $opt['active_callback'] ) ) {
						$sec->active_callback = $opt['active_callback'];
					}
				} else {
					$wp_customize->add_section(
						esc_attr( $id ), array(
							'title'           => $opt['title'],
							'description'     => $opt['desc'],
							'panel'           => esc_attr( $panels[ $p ] ),
							'priority'        => $i,
							'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
						)
					);
				}

				array_push( $sections, $id );
				$s++;

			} elseif ( 'section_end' == $opt['type'] ) {

				if ( $s > 0 ) {
					array_pop( $sections );
					$s--;
				}

			} elseif ( 'select' == $opt['type'] ) {

				$wp_customize->add_setting(
					$id, array(
						'default'           => preservation_get_theme_option_std( $id, $opt['std'] ),	// From 1.0.59 used instead preservation_get_theme_option( $id )
						'sanitize_callback' => 'sanitize_text_field',
						'transport'         => $transport,
					)
				);

				$args = array(
						'label'           => $opt['title'],
						'description'     => $opt['desc'],
						'section'         => esc_attr( $sections[ $s ] ),
						'priority'        => $i,
						'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
						'type'            => 'select',
						'choices'         => apply_filters( 'preservation_filter_options_get_list_choises', $opt['options'], $id ),
						'input_attrs'     => array(
							'var_name' => ! empty( $opt['customizer'] ) ? $opt['customizer'] : '',
						),
					);

				if ( ! empty( $opt['pro_only'] ) ) {
					$args['input_attrs']['data-pro-only'] = 'true';
					$wp_customize->add_control( new Preservation_Customize_Theme_Control( $wp_customize, $id, $args ) );
				} else {
					$wp_customize->add_control( $id, $args );
				}

			} elseif ( 'radio' == $opt['type'] ) {

				$wp_customize->add_setting(
					$id, array(
						'default'           => preservation_get_theme_option_std( $id, $opt['std'] ),	// From 1.0.59 used instead preservation_get_theme_option( $id ),
						'sanitize_callback' => 'sanitize_text_field',
						'transport'         => $transport,
					)
				);

				$args = array(
							'label'           => $opt['title'],
							'description'     => $opt['desc'],
							'section'         => esc_attr( $sections[ $s ] ),
							'priority'        => $i,
							'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
							'type'            => 'radio',
							'choices'         => apply_filters( 'preservation_filter_options_get_list_choises', $opt['options'], $id ),
							'input_attrs'     => array(
								'var_name' => ! empty( $opt['customizer'] ) ? $opt['customizer'] : '',
							)
						);

				if ( ! empty( $opt['pro_only'] ) ) {
					$args['input_attrs']['data-pro-only'] = 'true';
					$wp_customize->add_control( new Preservation_Customize_Theme_Control( $wp_customize, $id, $args ) );
				} else {
					$wp_customize->add_control( $id, $args );
				}

			} elseif ( 'checkbox' == $opt['type'] ) {												// Add " || 'switch' == $opt['type'] " to the condition to use checkbox instead switch
				$wp_customize->add_setting(
					$id, array(
						'default'           => preservation_get_theme_option_std( $id, $opt['std'] ),	// From 1.0.59 used instead preservation_get_theme_option( $id ),
						'sanitize_callback' => 'sanitize_text_field',
						'transport'         => $transport,
					)
				);

				$args = array(
						'label'           => $opt['title'],
						'description'     => $opt['desc'],
						'section'         => esc_attr( $sections[ $s ] ),
						'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
						'priority'        => $i,
						'type'            => 'checkbox',
						'input_attrs'     => array(
							'var_name' => ! empty( $opt['customizer'] ) ? $opt['customizer'] : '',
						),
					);

				if ( ! empty( $opt['pro_only'] ) ) {
					$args['input_attrs']['data-pro-only'] = 'true';
					$wp_customize->add_control( new Preservation_Customize_Theme_Control( $wp_customize, $id, $args ) );
				} else {
					$wp_customize->add_control( $id, $args );
				}

			} elseif ( 'switch' == $opt['type'] ) {

				$wp_customize->add_setting(
					$id, array(
						'default'           => preservation_get_theme_option_std( $id, $opt['std'] ),	// From 1.0.59 used instead preservation_get_theme_option( $id ),
						'sanitize_callback' => 'sanitize_text_field',
						'transport'         => $transport,
					)
				);

				$args = array(
							'label'           => $opt['title'],
							'description'     => $opt['desc'],
							'section'         => esc_attr( $sections[ $s ] ),
							'priority'        => $i,
							'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
							'input_attrs'     => array(
								'value'    => preservation_get_theme_option( $id ),
								'var_name' => ! empty( $opt['customizer'] ) ? $opt['customizer'] : '',
							),
						);
				if ( ! empty( $opt['pro_only'] ) ) {
					$args['input_attrs']['data-pro-only'] = 'true';
				}

				$wp_customize->add_control( new Preservation_Customize_Switch_Control( $wp_customize, $id, $args ) );

			} elseif ( 'color' == $opt['type'] ) {

				$wp_customize->add_setting(
					$id, array(
						'default'           => preservation_get_theme_option_std( $id, $opt['std'] ),	// From 1.0.59 used instead preservation_get_theme_option( $id ),
						'sanitize_callback' => 'sanitize_hex_color',
						'transport'         => $transport,
					)
				);

				$args = array(
							'label'           => $opt['title'],
							'description'     => $opt['desc'],
							'section'         => esc_attr( $sections[ $s ] ),
							'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
							'priority'        => $i,
							'input_attrs'     => array(
								'var_name' => ! empty( $opt['customizer'] ) ? $opt['customizer'] : '',
							),
						);
				if ( ! empty( $opt['pro_only'] ) ) {
					$args['input_attrs']['data-pro-only'] = 'true';
				}

				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, $args ) );

			} elseif ( 'image' == $opt['type'] ) {

				$wp_customize->add_setting(
					$id, array(
						'default'           => preservation_remove_protocol_from_url( preservation_get_theme_option_std( $id, $opt['std'] ), false ),	// From 1.0.59 used instead preservation_remove_protocol_from_url( preservation_get_theme_option( $id ), false ),
						'sanitize_callback' => 'sanitize_text_field',
						'transport'         => $transport,
					)
				);

				$args = array(
							'label'           => $opt['title'],
							'description'     => $opt['desc'],
							'section'         => esc_attr( $sections[ $s ] ),
							'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
							'priority'        => $i,
							'input_attrs'     => array(
								'var_name' => ! empty( $opt['customizer'] ) ? $opt['customizer'] : '',
							),
						);
				if ( ! empty( $opt['pro_only'] ) ) {
					$args['input_attrs']['data-pro-only'] = 'true';
				}

				$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $id, $args ) );

			} elseif ( in_array( $opt['type'], array( 'media', 'audio', 'video' ) ) ) {

				$wp_customize->add_setting(
					$id, array(
						'default'           => preservation_remove_protocol_from_url( preservation_get_theme_option_std( $id, $opt['std'] ), false ),	// From 1.0.59 used instead preservation_remove_protocol_from_url( preservation_get_theme_option( $id ), false ),
						'sanitize_callback' => 'sanitize_text_field',
						'transport'         => $transport,
					)
				);

				$args = array(
							'label'           => $opt['title'],
							'description'     => $opt['desc'],
							'section'         => esc_attr( $sections[ $s ] ),
							'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
							'priority'        => $i,
							'input_attrs'     => array(
								'var_name' => ! empty( $opt['customizer'] ) ? $opt['customizer'] : '',
							),
						);
				if ( ! empty( $opt['pro_only'] ) ) {
					$args['input_attrs']['data-pro-only'] = 'true';
				}

				$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $id, $args ) );

			} elseif ( 'icon' == $opt['type'] ) {

				$wp_customize->add_setting(
					$id, array(
						'default'           => preservation_remove_protocol_from_url( preservation_get_theme_option_std( $id, $opt['std'] ), false ),	// From 1.0.59 used instead preservation_remove_protocol_from_url( preservation_get_theme_option( $id ), false ),
						'sanitize_callback' => 'sanitize_text_field',
						'transport'         => $transport,
					)
				);

				$args = array(
							'label'           => $opt['title'],
							'description'     => $opt['desc'],
							'section'         => esc_attr( $sections[ $s ] ),
							'priority'        => $i,
							'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
							'input_attrs'     => array(
								'value'    => preservation_get_theme_option( $id ),
								'var_name' => ! empty( $opt['customizer'] ) ? $opt['customizer'] : '',
							),
						);
				if ( ! empty( $opt['pro_only'] ) ) {
					$args['input_attrs']['data-pro-only'] = 'true';
				}

				$wp_customize->add_control( new Preservation_Customize_Icon_Control( $wp_customize, $id, $args ) );

			} elseif ( 'checklist' == $opt['type'] ) {

				$wp_customize->add_setting(
					$id, array(
						'default'           => preservation_get_theme_option_std( $id, $opt['std'] ),	// From 1.0.59 used instead preservation_get_theme_option( $id ),
						'sanitize_callback' => 'sanitize_text_field',
						'transport'         => $transport,
					)
				);

				$args = array(
							'label'           => $opt['title'],
							'description'     => $opt['desc'],
							'section'         => esc_attr( $sections[ $s ] ),
							'priority'        => $i,
							'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
							'choices'         => apply_filters( 'preservation_filter_options_get_list_choises', $opt['options'], $id ),
							'input_attrs'     => array_merge(
								$opt, array(
									'value'    => preservation_get_theme_option( $id ),
									'var_name' => ! empty( $opt['customizer'] ) ? $opt['customizer'] : '',
								)
							),
						);
				if ( ! empty( $opt['pro_only'] ) ) {
					$args['input_attrs']['data-pro-only'] = 'true';
				}

				$wp_customize->add_control( new Preservation_Customize_Checklist_Control( $wp_customize, $id, $args ) );

			} elseif ( 'choice' == $opt['type'] ) {

				$wp_customize->add_setting(
					$id, array(
						'default'           => preservation_get_theme_option_std( $id, $opt['std'] ),	// From 1.0.59 used instead preservation_get_theme_option( $id ),
						'sanitize_callback' => 'sanitize_text_field',
						'transport'         => $transport,
					)
				);

				$args = array(
							'label'           => $opt['title'],
							'description'     => $opt['desc'],
							'section'         => esc_attr( $sections[ $s ] ),
							'priority'        => $i,
							'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
							'choices'         => apply_filters( 'preservation_filter_options_get_list_choises', $opt['options'], $id ),
							'input_attrs'     => array_merge(
								$opt, array(
									'value'    => preservation_get_theme_option( $id ),
									'var_name' => ! empty( $opt['customizer'] ) ? $opt['customizer'] : '',
								)
							),
						);
				if ( ! empty( $opt['pro_only'] ) ) {
					$args['input_attrs']['data-pro-only'] = 'true';
				}

				$wp_customize->add_control( new Preservation_Customize_Choice_Control( $wp_customize, $id, $args ) );

			} elseif ( in_array( $opt['type'], array( 'slider', 'range' ) ) ) {

				$std = preservation_get_theme_option_std( $id, $opt['std'] );
				if ( preservation_is_inherit( $std ) ) {
					$std = 0;
				}

				$wp_customize->add_setting(
					$id, array(
						'default'           => $std,	// From 1.0.59 used instead preservation_get_theme_option( $id ),
						'sanitize_callback' => 'sanitize_text_field',
						'transport'         => $transport,
					)
				);

				$args = array(
							'label'           => $opt['title'],
							'description'     => $opt['desc'],
							'section'         => esc_attr( $sections[ $s ] ),
							'priority'        => $i,
							'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
							'input_attrs'     => array_merge(
								$opt, array(
									'show_value' => ! isset( $opt['show_value'] ) || $opt['show_value'],
									'value'      => preservation_get_theme_option( $id ),
									'var_name'   => ! empty( $opt['customizer'] ) ? $opt['customizer'] : '',
								)
							),
						);
				if ( ! empty( $opt['pro_only'] ) ) {
					$args['input_attrs']['data-pro-only'] = 'true';
				}

				$wp_customize->add_control( new Preservation_Customize_Range_Control( $wp_customize, $id, $args ) );


			} elseif ( 'scheme_editor' == $opt['type'] ) {

				$wp_customize->add_setting(
					$id, array(
						'default'           => preservation_get_theme_option_std( $id, $opt['std'] ),	// From 1.0.59 used instead preservation_get_theme_option( $id ),
						'sanitize_callback' => 'sanitize_text_field',
						'transport'         => $transport,
					)
				);

				$args = array(
							'label'           => $opt['title'],
							'description'     => $opt['desc'],
							'section'         => esc_attr( $sections[ $s ] ),
							'priority'        => $i,
							'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
							'input_attrs'     => array_merge(
								$opt, array(
									'value'    => preservation_get_theme_option( $id ),
									'var_name' => ! empty( $opt['customizer'] ) ? $opt['customizer'] : '',
								)
							),
						);
				if ( ! empty( $opt['pro_only'] ) ) {
					$args['input_attrs']['data-pro-only'] = 'true';
				}

				$wp_customize->add_control( new Preservation_Customize_Scheme_Editor_Control( $wp_customize, $id, $args ) );

			} elseif ( 'text_editor' == $opt['type'] ) {

				$wp_customize->add_setting(
					$id, array(
						'default'           => preservation_get_theme_option_std( $id, $opt['std'] ),	// From 1.0.59 used instead preservation_get_theme_option( $id ),
						'sanitize_callback' => 'wp_kses_post',
						'transport'         => $transport,
					)
				);

				$args = array(
							'label'           => $opt['title'],
							'description'     => $opt['desc'],
							'section'         => esc_attr( $sections[ $s ] ),
							'priority'        => $i,
							'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
							'input_attrs'     => array_merge(
								$opt, array(
									'value'    => preservation_get_theme_option( $id ),
									'var_name' => ! empty( $opt['customizer'] ) ? $opt['customizer'] : '',
								)
							),
						);
				if ( ! empty( $opt['pro_only'] ) ) {
					$args['input_attrs']['data-pro-only'] = 'true';
				}

				$wp_customize->add_control( new Preservation_Customize_Text_Editor_Control( $wp_customize, $id, $args ) );

			} elseif ( 'button' == $opt['type'] ) {

				$wp_customize->add_setting(
					$id, array(
						'default'           => preservation_get_theme_option_std( $id, $opt['std'] ),	// From 1.0.59 used instead preservation_get_theme_option( $id ),
						'sanitize_callback' => 'sanitize_text_field',
						'transport'         => $transport,
					)
				);

				$args = array(
							'label'           => $opt['title'],
							'description'     => $opt['desc'],
							'section'         => esc_attr( $sections[ $s ] ),
							'priority'        => $i,
							'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
							'input_attrs'     => $opt,
						);
				if ( ! empty( $opt['pro_only'] ) ) {
					$args['input_attrs']['data-pro-only'] = 'true';
				}

				$wp_customize->add_control( new Preservation_Customize_Button_Control( $wp_customize, $id, $args ) );

			} elseif ( 'info' == $opt['type'] ) {

				$wp_customize->add_setting(
					$id, array(
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'transport'         => 'postMessage',
					)
				);

				$args = array(
							'label'           => $opt['title'],
							'description'     => $opt['desc'],
							'section'         => esc_attr( $sections[ $s ] ),
							'priority'        => $i,
							'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
						);

				$wp_customize->add_control( new Preservation_Customize_Info_Control( $wp_customize, $id, $args ) );

			} elseif ( 'hidden' == $opt['type'] ) {

				if ( isset( $opt['std']) ) {		// Need for options without 'std', i.e. type => 'info'
					$wp_customize->add_setting(
						$id, array(
							'default'           => preservation_get_theme_option_std( $id, $opt['std'] ),	// From 1.0.59 used instead preservation_get_theme_option( $id ),
							'sanitize_callback' => 'preservation_sanitize_html',
							'transport'         => 'postMessage',
						)
					);

					$args = array(
								'label'           => $opt['title'],
								'description'     => $opt['desc'],
								'section'         => esc_attr( $sections[ $s ] ),
								'priority'        => $i,
								'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
								'input_attrs'     => array(
									'var_name' => ! empty( $opt['customizer'] ) ? $opt['customizer'] : '',
								),
							);

					$wp_customize->add_control( new Preservation_Customize_Hidden_Control( $wp_customize, $id, $args ) );
				}

			} else {    // if in_array($opt['type'], array('text', 'textarea'))

				if ( ! apply_filters( 'preservation_filter_register_customizer_control', false, $wp_customize, $id, $sections[ $s ], $i, $transport, $opt ) ) {
					
					if ( 'text_editor' == $opt['type'] ) {
						$opt['type'] = 'textarea';
					}

					$wp_customize->add_setting(
						$id, array(
							'default'           => preservation_get_theme_option_std( $id, $opt['std'] ),	// From 1.0.59 used instead preservation_get_theme_option( $id ),
							'sanitize_callback' => ! empty( $opt['sanitize'] )
														? $opt['sanitize']
														: ( 'text' == $opt['type']
																? 'sanitize_text_field'
																: 'wp_kses_post'
															),
							'transport'         => $transport,
						)
					);

					$args = array(
							'label'           => $opt['title'],
							'description'     => $opt['desc'],
							'section'         => esc_attr( $sections[ $s ] ),
							'priority'        => $i,
							'active_callback' => ! empty( $opt['active_callback'] ) ? $opt['active_callback'] : '',
							'type'            => $opt['type'],
							'input_attrs'     => array(
								'var_name' => ! empty( $opt['customizer'] ) ? $opt['customizer'] : '',
							),
						);

					if ( ! empty( $opt['pro_only'] ) ) {
						$args['input_attrs']['data-pro-only'] = 'true';
						$wp_customize->add_control( new Preservation_Customize_Theme_Control( $wp_customize, $id, $args ) );
					} else {
						$wp_customize->add_control( $id, $args );
					}

				}
			}

			// Register Partial Refresh (if supported)
			if ( $refresh_auto && isset( $opt['refresh'] ) && is_string( $opt['refresh'] )
				&& empty( $opt['pro_only'] )
				&& function_exists( "preservation_customizer_partial_refresh_{$id}" )
				&& isset( $wp_customize->selective_refresh )
			) {
				$wp_customize->selective_refresh->add_partial(
					$id, array(
						'selector'            => $opt['refresh'],
						'settings'            => $id,
						'render_callback'     => "preservation_customizer_partial_refresh_{$id}",
						'container_inclusive' => ! empty( $opt['refresh_wrapper'] ),
					)
				);
			}
		}

		// Save expand callbacks to use it in the localize scripts
		preservation_storage_set( 'customizer_expand', $expand );

		// Setup standard WP Controls
		// ---------------------------------

		// Reorder standard WP sections
		$sec = $wp_customize->get_panel( 'nav_menus' );
		if ( is_object( $sec ) ) {
			$sec->priority = 60;
		}
		$sec = $wp_customize->get_panel( 'widgets' );
		if ( is_object( $sec ) ) {
			$sec->priority = 61;
		}
		$sec = $wp_customize->get_section( 'static_front_page' );
		if ( is_object( $sec ) ) {
			$sec->priority = 62;
		}
		$sec = $wp_customize->get_section( 'custom_css' );
		if ( is_object( $sec ) ) {
			$sec->priority = 2000;
		}

		// Modify standard WP controls
		$sec = $wp_customize->get_control( 'blogname' );
		if ( is_object( $sec ) ) {
			$sec->description = esc_html__( 'Use "((" and "))", "{{" and "}}" to modify style and color of parts of the text, "||" to break current line', 'preservation' );
		}
		$sec = $wp_customize->get_setting( 'blogname' );
		if ( is_object( $sec ) ) {
			$sec->transport = 'postMessage';
		}

		$sec = $wp_customize->get_setting( 'blogdescription' );
		if ( is_object( $sec ) ) {
			$sec->transport = 'postMessage';
		}

		$sec = $wp_customize->get_control( 'site_icon' );
		if ( is_object( $sec ) ) {
			$sec->priority = 15;
		}
		$sec = $wp_customize->get_control( 'custom_logo' );
		if ( is_object( $sec ) ) {
			$sec->priority    = 50;
			$sec->description = wp_kses_data( __( 'Select or upload the site logo', 'preservation' ) );
		}

		$sec  = $wp_customize->get_section( 'header_image' );
		$sec2 = $wp_customize->get_control( 'header_image_info' );
		if ( is_object( $sec ) && is_object( $sec2 ) ) {
			$sec2->description = ( ! empty( $sec2->description ) ? $sec2->description . '<br>' : '' ) . $sec->description;
		}

		$sec = $wp_customize->get_control( 'header_image' );
		if ( is_object( $sec ) ) {
			$sec->priority = 300;
			$sec->section  = 'header';
		}
		$sec = $wp_customize->get_control( 'header_video' );
		if ( is_object( $sec ) ) {
			$sec->priority = 310;
			$sec->section  = 'header';
		}
		$sec = $wp_customize->get_control( 'external_header_video' );
		if ( is_object( $sec ) ) {
			$sec->priority = 320;
			$sec->section  = 'header';
		}

		$sec = $wp_customize->get_section( 'background_image' );
		if ( is_object( $sec ) ) {
			$sec->title       = esc_html__( 'Background', 'preservation' );
			$sec->priority    = 310;
			$sec->description = esc_html__( 'Used only if "General settings - Body style" equal to "boxed"', 'preservation' );
		}

		$sec = $wp_customize->get_control( 'background_color' );
		if ( is_object( $sec ) ) {
			$sec->priority = 10;
			$sec->section  = 'background_image';
		}

		// Remove unused sections
		$wp_customize->remove_section( 'colors' );
		$wp_customize->remove_section( 'header_image' );
	}
}

if ( ! function_exists( 'preservation_sanitize_value' ) ) {
	/**
	 * Sanitize a simple value - remove all tags and spaces.
	 *
	 * @param strig $value  A value to sanitize
	 *
	 * @return string       A sanitized value
	 */
	function preservation_sanitize_value( $value ) {
		return empty( $value ) ? $value : trim( strip_tags( $value ) );
	}
}

if ( ! function_exists( 'preservation_sanitize_html' ) ) {
	/**
	 * Sanitize a value with a html-layout - keep only allowed tags
	 *
	 * @param string $value  A value to sanitize
	 *
	 * @return string        A sanitized value
	 */
	function preservation_sanitize_html( $value ) {
		return empty( $value ) ? $value : wp_kses_post( $value );
	}
}

if ( ! function_exists( 'preservation_customizer_get_focus_url' ) ) {
	/**
	 * Return URL to autofocus a related field on click.
	 *
	 * @param string $field  A field name to create the autofocus URL.
	 *
	 * @return string        The autofocus URL for the specified field name.
	 */
	function preservation_customizer_get_focus_url( $field ) {
		return admin_url( "customize.php?autofocus&#91;control&#93;={$field}" );
	}
}

if ( ! function_exists( 'preservation_customizer_get_focus_link' ) ) {
	/**
	 * Return a link to autofocus a related field on click.
	 *
	 * @param string $field  A field name to create the autofocus link.
	 * @param string $text   A title for the link.
	 *
	 * @return string        The autofocus link for the specified field name.
	 */
	function preservation_customizer_get_focus_link( $field, $text ) {
		return sprintf(
			'<a href="%1$s" class="preservation_customizer_link">%2$s</a>',
			esc_url( preservation_customizer_get_focus_url( $field ) ),
			$text
		);
	}
}

if ( ! function_exists( 'preservation_customizer_need_widgets_message' ) ) {
	/**
	 * Display a message "Need to select widgets" in the empty widgetized areas.
	 *
	 * @param string $field  A field name with the widgetized area selected.
	 * @param $text          A title for the autofocus link.
	 */
	function preservation_customizer_need_widgets_message( $field, $text ) {
		?><div class="preservation_customizer_message">
		<?php
			echo wp_kses_data(
				sprintf(
					// Translators: Add widget's name or link to focus specified section
					__( 'You have to choose widget "<b>%s</b>" in this section. You can also select any other widget, and change the purpose of this section', 'preservation' ),
					is_customize_preview()
						? $text
						: preservation_customizer_get_focus_link( $field, $text )
				)
			);
		?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'preservation_customizer_need_trx_addons_message' ) ) {
	/**
	 * Display a message "Need to install plugin ThemeREX Addons" if widgets "Team", "Testimonials", etc. are not available.
	 */
	function preservation_customizer_need_trx_addons_message() {
		?>
		<div class="preservation_customizer_message">
			<?php
			echo wp_kses_data(
				sprintf(
					// Translators: Add the link to install plugin and its name
					__( 'You need to install the <b>%s</b> plugin to be able to add Team members, Testimonials, Services and many other widgets', 'preservation' ),
					is_customize_preview()
						? __( 'ThemeREX Addons', 'preservation' )
						: sprintf(
							// Translators: Make the tag with link to install plugin
							'<a href="%1$s" class="preservation_customizer_link">%2$s</a>',
							esc_url(
								wp_nonce_url(
									self_admin_url( 'update.php?action=install-plugin&plugin=trx_addons' ),
									'install-plugin_trx_addons'
								)
							),
							__( 'ThemeREX Addons', 'preservation' )
						)
				)
			);
			echo '<br>' . wp_kses_data( __( 'Also you can insert in this section any other widgets and to modify its purpose', 'preservation' ) );
			?>
		</div>
		<?php
	}
}


//--------------------------------------------------------------
// Save custom settings to CSS files
//--------------------------------------------------------------

if ( ! function_exists( 'preservation_set_action_save_options' ) ) {
	/**
	 * Set a flag to regenerate styles and scripts on next page load.
	 */
	function preservation_set_action_save_options() {
		if ( preservation_exists_trx_addons() ) {
			update_option( 'preservation_action', '' );
			update_option( 'trx_addons_action', 'trx_addons_action_save_options' );
		} else {
			update_option( 'preservation_action', 'preservation_action_save_options' );
		}
	}
}

if ( ! function_exists( 'preservation_customizer_action_save_after' ) ) {
	add_action( 'customize_save_after', 'preservation_customizer_action_save_after' );
	/**
	 * Update CSS files with custom colors and fonts after custom options saved.
	 *
	 * Hooks: add_action( 'customize_save_after', 'preservation_customizer_action_save_after' );
	 *
	 * @param false $api  Optional. Reference to the object $wp_customize.
	 */
	function preservation_customizer_action_save_after( $api = false ) {

		// Get saved settings
		$settings = $api->settings();

		// Store new schemes colors
		$scheme_storage = $settings['scheme_storage']->value();
		if ( $scheme_storage == serialize( preservation_storage_get( 'schemes_original' ) ) ) {
			remove_theme_mod( 'scheme_storage' );
		} else {
			$schemes = preservation_unserialize( $scheme_storage );
			if ( is_array( $schemes ) && count( $schemes ) > 0 ) {
				preservation_storage_set( 'schemes', $schemes );
			}
		}

		// Store new fonts parameters
		$fonts = preservation_get_theme_fonts();
		foreach ( $fonts as $tag => $v ) {
			foreach ( $v as $css_prop => $css_value ) {
				if ( in_array( $css_prop, array( 'title', 'description' ) ) ) {
					continue;
				}
				if ( isset( $settings[ "{$tag}_{$css_prop}" ] ) ) {
					$fonts[ $tag ][ $css_prop ] = $settings[ "{$tag}_{$css_prop}" ]->value();
				}
			}
		}
		preservation_storage_set( 'theme_fonts', $fonts );

		// Collect options from the external storages
		$theme_mods        = array();
		$external_storages = array();
		$options           = preservation_storage_get( 'options' );
		foreach ( $options as $k => $v ) {
			// Skip non-data options - sections, info, etc.
			if ( ! isset( $v['std'] ) ) {
				continue;
			}
			// Get option value from Customizer
			$value            = isset( $settings[ $k ] )
							? $settings[ $k ]->value()
							: ( in_array( $v['type'], array( 'checkbox', 'switch' ) )  ? 0 : '' );
			$theme_mods[ $k ] = $value;
			// Skip internal options
			if ( empty( $v['options_storage'] ) ) {
				continue;
			}
			// Save option to the external storage
			if ( ! isset( $external_storages[ $v['options_storage'] ] ) ) {
				$external_storages[ $v['options_storage'] ] = array();
			}
			$external_storages[ $v['options_storage'] ][ $k ] = $value;
		}

		// Update options in the external storages
		foreach ( $external_storages as $storage_name => $storage_values ) {
			$storage = get_option( $storage_name, false );
			if ( is_array( $storage ) ) {
				foreach ( $storage_values as $k => $v ) {
					$storage[ $k ] = $v;
				}
				update_option( $storage_name, apply_filters( 'preservation_filter_options_save', $storage, $storage_name ) );
			}
		}

		do_action( 'preservation_action_just_save_options', $theme_mods );

		// Update ThemeOptions save timestamp
		$stylesheet_slug = get_stylesheet();
		$stylesheet_time = time();
		update_option( "preservation_options_timestamp_{$stylesheet_slug}", $stylesheet_time );

		// Synchronize theme options between child and parent themes
		if ( preservation_get_theme_setting( 'duplicate_options' ) == 'both' ) {
			$theme_slug = get_template();
			if ( $theme_slug != $stylesheet_slug ) {
				preservation_customizer_duplicate_theme_options( $stylesheet_slug, $theme_slug, $stylesheet_time );
			}
		}

		// Apply action - moved to the delayed state (see below) to load all enabled modules and apply changes after
		// Attention! Don't remove comment the line below!
		// Not need here: do_action('preservation_action_save_options');
		update_option( 'preservation_action', 'preservation_action_save_options' );
	}
}

if ( ! function_exists( 'preservation_customizer_save_css' ) ) {
	add_action( 'preservation_action_save_options', 'preservation_customizer_save_css', 20 );
	add_action( 'trx_addons_action_save_options', 'preservation_customizer_save_css', 20 );
	/**
	 * Save CSS with custom colors and fonts to the custom.css
	 * and merge styles and scripts to the single file to increase a site load speed.
	 *
	 * Hooks: add_action( 'preservation_action_save_options', 'preservation_customizer_save_css', 20 );
	 *
	 *        add_action( 'trx_addons_action_save_options', 'preservation_customizer_save_css', 20 );
	 *
	 */
	function preservation_customizer_save_css() {
		$msg = '/* ' . esc_html__( "ATTENTION! This file was generated automatically! Don't change it!!!", 'preservation' )
				. "\n----------------------------------------------------------------------- */\n";

		// Save CSS with custom fonts and vars to the __custom.css
		$css = preservation_customizer_get_css();
		preservation_fpc( preservation_get_file_dir( 'css/__custom.css' ), $msg . $css );

		// Merge styles
		// CSS list must be in the next format:
		// 'relative url for css-file' => true | false
		//     true - merge this file always (to the __plugins and to the __plugins-full),
		//     false - not merge this file for optimized mode (only to the __plugins-full)
    	$css_list = apply_filters( 'preservation_filter_merge_styles', array() );
		preservation_merge_css( 'css/__plugins.css', array_keys( $css_list, true ) );
		preservation_merge_css( 'css/__plugins-full.css', array_keys( $css_list ) );

		// Merge responsive styles
		$css_list = apply_filters( 'preservation_filter_merge_styles_responsive', array(
																				'css/responsive.css' => true,
																				)
								);
		preservation_merge_css( 'css/__responsive.css', array_keys( $css_list, true ), true );
		preservation_merge_css( 'css/__responsive-full.css', array_keys( $css_list ), true );

		// If separate single styles are supported with current skin - place its to the stand-alone files
		if ( apply_filters( 'preservation_filters_separate_single_styles', false ) ) {
			// Merge styles for single posts
			preservation_merge_css( 'css/__single.css', array_keys( apply_filters( 'preservation_filter_merge_styles_single', array(
				'css/single.css' => true,
			) ) ) );

			// Merge responsive styles for single posts
			preservation_merge_css( 'css/__single-responsive.css', array_keys( apply_filters( 'preservation_filter_merge_styles_responsive_single', array(
				'css/single-responsive.css' => true,
			) ) ), true );
		}

		// Merge scripts
		// JS list must be in the next format:
		// 'relative url for js-file' => true | false
		//     true - merge this file always (to the __scripts and to the __scripts-full),
		//     false - not merge this file for optimized mode (only to the __scripts-full)
		$js_list = apply_filters( 'preservation_filter_merge_scripts', array(
			'js/skip-link-focus-fix/skip-link-focus-fix.js' => true,
			'js/utils.js' => true,
			'js/init.js' => true,
		) );
		preservation_merge_js( 'js/__scripts.js', array_keys( $js_list, true ) );
		preservation_merge_js( 'js/__scripts-full.js', array_keys( $js_list ) );
	}
}

if ( ! function_exists( 'preservation_merge_styles_convert_keys' ) ) {
	add_filter( 'preservation_filter_merge_styles', 'preservation_merge_styles_convert_keys', 9999, 1 );
	add_filter( 'preservation_filter_merge_styles_responsive', 'preservation_merge_styles_convert_keys', 9999, 1 );
	add_filter( 'preservation_filter_merge_styles_single', 'preservation_merge_styles_convert_keys', 9999, 1 );
	add_filter( 'preservation_filter_merge_styles_responsive_single', 'preservation_merge_styles_convert_keys', 9999, 1 );
	add_filter( 'preservation_filter_merge_scripts', 'preservation_merge_styles_convert_keys', 9999, 1 );
	/**
	 * Convert an array items with numeric keys to the new format ( to compatibility with old themes )
	 *
	 * Hooks: add_filter( 'preservation_filter_merge_styles', 'preservation_merge_styles_convert_keys', 9999, 1 );
	 *        add_filter( 'preservation_filter_merge_styles_responsive', 'preservation_merge_styles_convert_keys', 9999, 1 );
	 *        add_filter( 'preservation_filter_merge_styles_single', 'preservation_merge_styles_convert_keys', 9999, 1 );
	 *        add_filter( 'preservation_filter_merge_styles_responsive_single', 'preservation_merge_styles_convert_keys', 9999, 1 );
	 *        add_filter( 'preservation_filter_merge_scripts', 'preservation_merge_styles_convert_keys', 9999, 1 );
	 *
	 * @param array $list  An array with styles/scripts to be merged.
	 *
	 * @return array       A processed array.
	 */
	function preservation_merge_styles_convert_keys( $list ) {
		if ( is_array( $list ) ) {
			$new_list = array();
			foreach( $list as $k => $v ) {
				if ( is_numeric( $k ) ) {
					$new_list[ $v ] = true;
				} else {
					$new_list[ $k ] = $v;
				}
			}
			$list = $new_list;
			unset( $new_list );
		}
		return $list;
	}
}


//--------------------------------------------------------------
// Add a theme-specific blog styles and scripts to the list
//--------------------------------------------------------------

if ( ! function_exists( 'preservation_customizer_add_blog_styles_and_scripts' ) ) {
	/**
	 * Add a theme-specific blog styles and scripts to the list of files to be merged or loaded on page load.
	 *
	 * Trigger filters "preservation_filter_enqueue_blog_styles" and "preservation_filter_enqueue_blog_scripts" to allow
	 * other modules add their styles / scripts to the list or enqueue its.
	 *
	 * @param array|bool $list  Optional. An array of styles/scripts to be merged to add a blog styles/scripts to it.
	 *                          If false - enqueue a blog styles/scripts to be load on page load. Default is false.
	 * @param string $type      Optional. A type of files to process: styles | scripts. Default is 'styles'
	 * @param bool $responsive  Optional. A main styles or responsive styles should be processed. Default is false.
	 *
	 * @return array|bool       A processed list
	 */
	function preservation_customizer_add_blog_styles_and_scripts( $list = false, $type = 'styles', $responsive = false ) {
		$styles = preservation_storage_get( 'blog_styles' );
		if ( is_array( $styles ) ) {
			if ( preservation_exists_trx_addons() ) {
				$styles = array_merge(
					$styles,
					array(
						'custom' => array( 'styles' => 'custom' )
					)
				);
			}
			foreach ( $styles as $k => $v ) {
				if ( ! empty( $v[ $type ] ) ) {
					foreach ( (array) $v[ $type ] as $s ) {
						if ( apply_filters( "preservation_filter_enqueue_blog_{$type}", true, $k, $s, $list, $responsive ) ) {
							$path = sprintf(
								'templates/blog-styles/%1$s%2$s.%3$s',
								$s,
								$responsive ? '-responsive' : '',
								'styles' == $type ? 'css' : 'js'
							);
							if ( is_array( $list ) ) {
								if ( ! isset( $list[ $path ] ) ) {
									$list[ $path ] = true;
								}
							} else {
								$path = preservation_get_file_url( $path );
								if ( '' != $path ) {
									if ( 'scripts' == $type ) {
										wp_enqueue_script( 'preservation-blog-script-' . esc_attr( $s ), $path, array( 'jquery' ), null, true );
									} else {
										wp_enqueue_style( 'preservation-blog-style-' . esc_attr( $s . ( $responsive ? '-responsive' : '' ) ),  $path, array(), null, $responsive ? preservation_media_for_load_css_responsive( 'blog-styles' ) : 'all' );
									}
								}
							}
						}
					}
				}
			}
		}
		return $list;
	}
}

if ( ! function_exists( 'preservation_customizer_merge_blog_styles' ) ) {
	add_filter( 'preservation_filter_merge_styles', 'preservation_customizer_merge_blog_styles', 8, 1 );
	/**
	 * Add a theme-specific blog styles to the list of files to be merged.
	 * This function is a wrapper for preservation_customizer_add_blog_styles_and_scripts()
	 *
	 * Hooks: add_filter( 'preservation_filter_merge_styles', 'preservation_customizer_merge_blog_styles', 8, 1 );
	 *
	 * @param array $list  An array of styles to be merged to add a blog styles to it.
	 *
	 * @return array       An array with a theme-specific blog styles.
	 */
	function preservation_customizer_merge_blog_styles( $list ) {
		return preservation_customizer_add_blog_styles_and_scripts( $list, 'styles' );
	}
}

if ( ! function_exists( 'preservation_customizer_merge_blog_styles_responsive' ) ) {
	add_filter( 'preservation_filter_merge_styles_responsive', 'preservation_customizer_merge_blog_styles_responsive', 8, 1 );
	/**
	 * Add a theme-specific blog styles for responsive to the list of files to be merged.
	 * This function is a wrapper for preservation_customizer_add_blog_styles_and_scripts()
	 *
	 * Hooks: add_filter( 'preservation_filter_merge_styles_responsive', 'preservation_customizer_merge_blog_styles_responsive', 8, 1 );
	 *
	 * @param array $list  An array of responsive styles to be merged to add a blog styles (responsive) to it.
	 *
	 * @return array       An array with a theme-specific responsive blog styles.
	 */
	function preservation_customizer_merge_blog_styles_responsive( $list ) {
		return preservation_customizer_add_blog_styles_and_scripts( $list, 'styles', true );
	}
}

if ( ! function_exists( 'preservation_customizer_merge_blog_scripts' ) ) {
	add_filter( 'preservation_filter_merge_scripts', 'preservation_customizer_merge_blog_scripts' );
	/**
	 * Add a theme-specific blog scripts to the list of files to be merged.
	 * This function is a wrapper for preservation_customizer_add_blog_styles_and_scripts()
	 *
	 * Hooks: add_filter( 'preservation_filter_merge_scripts', 'preservation_customizer_merge_blog_scripts' );
	 *
	 * @param array $list  An array of scripts to be merged to add a blog scripts to it.
	 *
	 * @return array       An array with a theme-specific blog scripts.
	 */
	function preservation_customizer_merge_blog_scripts( $list ) {
		return preservation_customizer_add_blog_styles_and_scripts( $list, 'scripts' );
	}
}

if ( ! function_exists( 'preservation_customizer_blog_styles' ) ) {
	add_action( 'wp_enqueue_scripts', 'preservation_customizer_blog_styles', 1100 );
	/**
	 * Enqueue a theme-specific blog styles and scripts if debug mode is on.
	 *
	 * Hooks: add_action( 'wp_enqueue_scripts', 'preservation_customizer_blog_styles', 1100 );
	 */
	function preservation_customizer_blog_styles() {
		if ( preservation_is_on( preservation_get_theme_option( 'debug_mode' ) ) ) {
			preservation_customizer_add_blog_styles_and_scripts( false, 'styles' );
			preservation_customizer_add_blog_styles_and_scripts( false, 'scripts' );
		}
	}
}

if ( ! function_exists( 'preservation_customizer_blog_styles_responsive' ) ) {
	add_action( 'wp_enqueue_scripts', 'preservation_customizer_blog_styles_responsive', 2000 );
	/**
	 * Enqueue a theme-specific blog styles for responsive if debug mode is on.
	 *
	 * Hooks: add_action( 'wp_enqueue_scripts', 'preservation_customizer_blog_styles_responsive', 2000 );
	 */
	function preservation_customizer_blog_styles_responsive() {
		if ( preservation_is_on( preservation_get_theme_option( 'debug_mode' ) ) ) {
			preservation_customizer_add_blog_styles_and_scripts( false, 'styles', true );
		}
	}
}


//-------------------------------------------------------------------------------
// Add a theme-specific single styles and scripts to the list
//-------------------------------------------------------------------------------

if ( ! function_exists( 'preservation_customizer_add_single_styles_and_scripts' ) ) {
	/**
	 * Add a theme-specific single styles and scripts to the list of files to be merged or loaded on page load.
	 *
	 * @param array|bool $list      Optional. An array of styles/scripts to be merged to add a single styles/scripts to it.
	 *                              If false - enqueue a single styles/scripts to be load on page load. Default is false.
	 * @param string $type          Optional. A type of files to process: styles | scripts. Default is 'styles'.
	 * @param bool $responsive      Optional. A main styles or responsive styles should be processed. Default is false.
	 * @param string $single_style  Optional. A single style name to be merged or enqueued. Default is empty string.
	 *
	 * @return array|bool           A processed list.
	 */
	function preservation_customizer_add_single_styles_and_scripts( $list = false, $type = 'styles', $responsive = false, $single_style = '' ) {
		$styles = preservation_storage_get( 'single_styles' );
		if ( is_array( $styles ) && ( is_array( $list ) || apply_filters( 'preservation_filter_single_post_header', preservation_is_singular( 'post' ) || preservation_is_singular( 'attachment' ) ) ) ) {
			if ( empty( $single_style ) ) {
				$single_style = preservation_get_theme_option( 'single_style' );
			}
			foreach ( $styles as $k => $v ) {
				if ( ( is_array( $list ) || $k == $single_style ) && ! empty( $v[ $type ] ) ) {
					foreach ( (array) $v[ $type ] as $s ) {
						$path = sprintf(
							'templates/single-styles/%1$s%2$s.%3$s',
							$s,
							$responsive ? '-responsive' : '',
							'styles' == $type ? 'css' : 'js'
						);
						if ( is_array( $list ) ) {
							if ( ! isset( $list[ $path ] ) ) {
								$list[ $path ] = true;
							}
						} else {
							$path = preservation_get_file_url( $path );
							if ( '' != $path ) {
								if ( 'scripts' == $type ) {
									wp_enqueue_script( 'preservation-single-script-' . esc_attr( $s ), $path, array( 'jquery' ), null, true );
								} else {
									if ( false === $list 
										&& preservation_is_on( preservation_get_theme_option( 'debug_mode' ) ) 
										&& preservation_get_theme_option( 'posts_navigation' ) === 'scroll' 
										&& in_array( trx_addons_get_value_gp( 'action' ), array( 'prev_post_loading' ) )
									) {
										preservation_add_inline_css( preservation_fgc( $path ) );
									} else {
										wp_enqueue_style( 'preservation-single-style-' . esc_attr( $s . ( $responsive ? '-responsive' : '' ) ),  $path, array(), null, $responsive ? preservation_media_for_load_css_responsive( 'single-styles' ) : 'all' );
									}
								}
							}
						}
					}
				}
			}
		}
		return $list;
	}
}

if ( ! function_exists( 'preservation_customizer_merge_single_styles' ) ) {
	add_filter( 'preservation_filter_merge_styles', 'preservation_customizer_merge_single_styles', 8, 1 );
	add_filter( 'preservation_filter_merge_styles_single', 'preservation_customizer_merge_single_styles', 8, 1 );
	/**
	 * Add a theme-specific single styles to the list of files to be merged.
	 * This function is a wrapper for preservation_customizer_add_single_styles_and_scripts()
	 *
	 * Hooks: add_filter( 'preservation_filter_merge_styles', 'preservation_customizer_merge_single_styles', 8, 1 );
	 *        add_filter( 'preservation_filter_merge_styles_single', 'preservation_customizer_merge_single_styles', 8, 1 );
	 *
	 * @param array $list  An array of styles to be merged to add a single styles to it.
	 *
	 * @return array       An array with a theme-specific single styles.
	 */
	function preservation_customizer_merge_single_styles( $list ) {
		// If separate single styles is supported with current skin
		if ( apply_filters( 'preservation_filters_separate_single_styles', false ) ) {
			if ( current_filter() == 'preservation_filter_merge_styles_single' ) {
				return preservation_customizer_add_single_styles_and_scripts( $list, 'styles' );
			}
		} else {   // If separate single styles is not supported with current skin - place all styles together
			if ( current_filter() == 'preservation_filter_merge_styles' ) {
				return preservation_customizer_add_single_styles_and_scripts( $list, 'styles' );
			}
		}
		return $list;
	}
}

if ( ! function_exists( 'preservation_customizer_merge_single_styles_responsive' ) ) {
	add_filter( 'preservation_filter_merge_styles_responsive', 'preservation_customizer_merge_single_styles_responsive', 8, 1 );
	add_filter( 'preservation_filter_merge_styles_responsive_single', 'preservation_customizer_merge_single_styles_responsive', 8, 1 );
	/**
	 * Add a theme-specific single styles (responsive) to the list of files to be merged.
	 * This function is a wrapper for preservation_customizer_add_single_styles_and_scripts()
	 *
	 * Hooks: add_filter( 'preservation_filter_merge_styles_responsive', 'preservation_customizer_merge_single_styles_responsive', 8, 1 );
	 *        add_filter( 'preservation_filter_merge_styles_responsive_single', 'preservation_customizer_merge_single_styles_responsive', 8, 1 );
	 *
	 * @param array $list  An array of responsive styles to be merged to add a single styles (responsive) to it.
	 *
	 * @return array       An array with a theme-specific single styles (responsive).
	 */
	function preservation_customizer_merge_single_styles_responsive( $list ) {
		if ( apply_filters( 'preservation_filters_separate_single_styles', false ) ) {
			// If separate single styles is supported with current skin
			if ( current_filter() == 'preservation_filter_merge_styles_responsive_single' ) {
				return preservation_customizer_add_single_styles_and_scripts( $list, 'styles', true );
			}
		} else {
			// If separate single styles is not supported with current skin - place all styles together
			if ( current_filter() == 'preservation_filter_merge_styles_responsive' ) {
				return preservation_customizer_add_single_styles_and_scripts( $list, 'styles', true );
			}
		}
		return $list;
	}
}

if ( ! function_exists( 'preservation_customizer_merge_single_scripts' ) ) {
	add_filter( 'preservation_filter_merge_scripts', 'preservation_customizer_merge_single_scripts' );
	/**
	 * Add a theme-specific single scripts to the list of files to be merged.
	 * This function is a wrapper for preservation_customizer_add_single_styles_and_scripts()
	 *
	 * Hooks: add_filter( 'preservation_filter_merge_scripts', 'preservation_customizer_merge_single_scripts' );
	 *
	 * @param array $list  An array of scripts to be merged to add a single scripts to it.
	 *
	 * @return array       An array with a theme-specific single scripts.
	 */
	function preservation_customizer_merge_single_scripts( $list ) {
		return preservation_customizer_add_single_styles_and_scripts( $list, 'scripts' );
	}
}

if ( ! function_exists( 'preservation_customizer_single_styles' ) ) {
	add_action( 'wp_enqueue_scripts', 'preservation_customizer_single_styles', 1020 );
	/**
	 * Enqueue a theme-specific single styles and scripts to be loaded if a debug mode is on.
	 * This function is a wrapper for preservation_customizer_add_single_styles_and_scripts()
	 *
	 * Hooks: add_action( 'wp_enqueue_scripts', 'preservation_customizer_single_styles', 1020 );
	 */
	function preservation_customizer_single_styles() {
		if ( preservation_is_on( preservation_get_theme_option( 'debug_mode' ) )
			&& apply_filters( 'preservation_filters_load_single_styles', preservation_is_singular( 'post' ) || preservation_is_singular( 'attachment' ) || (int) preservation_get_theme_option( 'open_full_post_in_blog' ) > 0 )
		) {
			preservation_customizer_add_single_styles_and_scripts( false, 'styles' );
			preservation_customizer_add_single_styles_and_scripts( false, 'scripts' );
		}
	}
}

if ( ! function_exists( 'preservation_customizer_single_styles_responsive' ) ) {
	add_action( 'wp_enqueue_scripts', 'preservation_customizer_single_styles_responsive', 2020 );
	/**
	 * Enqueue a theme-specific single responsive styles to be loaded if a debug mode is on.
	 * This function is a wrapper for preservation_customizer_add_single_styles_and_scripts()
	 *
	 * Hooks: add_action( 'wp_enqueue_scripts', 'preservation_customizer_single_styles_responsive', 2020 );
	 */
	function preservation_customizer_single_styles_responsive() {
		if ( preservation_is_on( preservation_get_theme_option( 'debug_mode' ) )
			&& apply_filters( 'preservation_filters_load_single_styles', preservation_is_singular( 'post' ) || preservation_is_singular( 'attachment' ) || (int) preservation_get_theme_option( 'open_full_post_in_blog' ) > 0 )
		) {
			preservation_customizer_add_single_styles_and_scripts( false, 'styles', true );
		}
	}
}


//--------------------------------------------------------------
// Customizer JS and CSS
//--------------------------------------------------------------

if ( ! function_exists( 'preservation_customizer_control_js' ) ) {
	add_action( 'customize_controls_enqueue_scripts', 'preservation_customizer_control_js' );
	/**
	 * Enqueue a theme-specific styles and scripts for the Customizer panel (a left side of the window).
	 * Add color schemes and presets, theme fonts and presets, theme variables, etc. to the localization array.
	 *
	 * Hooks: add_action( 'customize_controls_enqueue_scripts', 'preservation_customizer_control_js' );
	 *
	 * Trigger a filter 'preservation_filter_customizer_vars' to allow other modules to add their variables to the localization array.
	 */
	function preservation_customizer_control_js() {
		wp_enqueue_style( 'preservation-customizer', preservation_get_file_url( 'theme-options/theme-customizer.css' ), array(), null );
		wp_enqueue_script(
			'preservation-customizer',
			preservation_get_file_url( 'theme-options/theme-customizer.js' ),
			array( 'customize-controls', 'iris', 'underscore', 'wp-util' ), null, true
		);
		wp_enqueue_style(  'spectrum', preservation_get_file_url( 'js/colorpicker/spectrum/spectrum.css' ), array(), null );
		wp_enqueue_script( 'spectrum', preservation_get_file_url( 'js/colorpicker/spectrum/spectrum.js' ), array( 'jquery' ), null, true );
		wp_localize_script( 'preservation-customizer', 'preservation_color_schemes', preservation_storage_get( 'schemes' ) );
		wp_localize_script( 'preservation-customizer', 'preservation_simple_schemes', preservation_storage_get( 'schemes_simple' ) );
		wp_localize_script( 'preservation-customizer', 'preservation_sorted_schemes', preservation_storage_get( 'schemes_sorted' ) );
		wp_localize_script( 'preservation-customizer', 'preservation_color_presets', preservation_get_color_presets() );
		wp_localize_script( 'preservation-customizer', 'preservation_additional_colors', preservation_storage_get( 'scheme_colors_add' ) );
		wp_localize_script( 'preservation-customizer', 'preservation_theme_fonts', preservation_storage_get( 'theme_fonts' ) );
		wp_localize_script( 'preservation-customizer', 'preservation_font_presets', preservation_get_font_presets() );
		wp_localize_script( 'preservation-customizer', 'preservation_theme_vars', preservation_get_theme_vars() );
		wp_localize_script(
			'preservation-customizer', 'preservation_customizer_vars', apply_filters(
				'preservation_filter_customizer_vars', array(
					'max_load_fonts'     => preservation_get_theme_setting( 'max_load_fonts' ),
					'decorate_fonts'     => preservation_get_theme_setting( 'decorate_fonts' ),
					'page_width_default' => apply_filters( 'preservation_filter_content_width', preservation_get_theme_option( 'page_width' ) ),
					'msg_reset'          => esc_html__( 'Reset', 'preservation' ),
					'msg_reset_confirm'  => esc_html__( 'Are you sure you want to reset all Theme Options?', 'preservation' ),
					'msg_reload'         => esc_html__( 'Reload', 'preservation' ),
					'msg_reload_title'   => esc_html__( 'Reload preview area to display changes', 'preservation' ),
					'actions'            => array(
						'expand' => preservation_storage_get( 'customizer_expand', array() ),
					),
				)
			)
		);
		wp_localize_script( 'preservation-customizer', 'preservation_dependencies', preservation_get_theme_dependencies() );
		preservation_admin_scripts(true);
		preservation_admin_localize_scripts();
	}
}

if ( ! function_exists( 'preservation_customizer_preview_js' ) ) {
	add_action( 'customize_preview_init', 'preservation_customizer_preview_js' );
	/**
	 * Enqueue a theme-specific scripts for the Customizer preview (a right side of the window).
	 * Add color schemes to the localization array.
	 *
	 * Hooks: add_action( 'customize_preview_init', 'preservation_customizer_preview_js' );
	 */
	function preservation_customizer_preview_js() {
		wp_enqueue_script(
			'preservation-customizer-preview',
			preservation_get_file_url( 'theme-options/theme-customizer-preview.js' ),
			array( 'customize-preview' ), null, true
		);
		wp_localize_script( 'preservation-customizer-preview', 'preservation_color_schemes', preservation_storage_get( 'schemes' ) );
	}
}

if ( ! function_exists( 'preservation_customizer_css_template' ) ) {
	add_action( 'customize_controls_print_footer_scripts', 'preservation_customizer_css_template' );
	/**
	 * Output an Underscore template to generate CSS for the color scheme.
	 * The template generates the css dynamically for instant display in the Customizer preview.
	 *
	 * Hooks: add_action( 'customize_controls_print_footer_scripts', 'preservation_customizer_css_template' );
	 */
	function preservation_customizer_css_template() {
		$colors = array();
		foreach ( preservation_get_scheme_colors() as $k => $v ) {
			$colors[ $k ] = '{{ data.' . esc_attr( $k ) . ' }}';
		}

		$tmpl_holder = 'script';

		$schemes = array_keys( preservation_get_list_schemes() );
		if ( count( $schemes ) > 0 ) {
			foreach ( $schemes as $scheme ) {
				preservation_show_layout(
					preservation_customizer_get_css(
						array(
							'colors'        => $colors,
							'scheme'        => $scheme,
							'fonts'         => false,
							'vars'          => false,
							'remove_spaces' => false,
						)
					),
					'<' . esc_html( $tmpl_holder ) . ' type="text/html" id="tmpl-preservation-color-scheme-' . esc_attr( $scheme ) . '">',
					'</' . esc_html( $tmpl_holder ) . '>'
				);
			}
		}

		// Fonts
		$fonts = preservation_get_theme_fonts();
		if ( is_array( $fonts ) && count( $fonts ) > 0 ) {
			foreach ( $fonts as $tag => $font ) {
				$fonts[ $tag ]['font-family']     = '{{ data["' . $tag . '"]["font-family"] }}';
				$fonts[ $tag ]['font-size']       = '{{ data["' . $tag . '"]["font-size"] }}';
				$fonts[ $tag ]['line-height']     = '{{ data["' . $tag . '"]["line-height"] }}';
				$fonts[ $tag ]['font-weight']     = '{{ data["' . $tag . '"]["font-weight"] }}';
				$fonts[ $tag ]['font-style']      = '{{ data["' . $tag . '"]["font-style"] }}';
				$fonts[ $tag ]['text-decoration'] = '{{ data["' . $tag . '"]["text-decoration"] }}';
				$fonts[ $tag ]['text-transform']  = '{{ data["' . $tag . '"]["text-transform"] }}';
				$fonts[ $tag ]['letter-spacing']  = '{{ data["' . $tag . '"]["letter-spacing"] }}';
				$fonts[ $tag ]['margin-top']      = '{{ data["' . $tag . '"]["margin-top"] }}';
				$fonts[ $tag ]['margin-bottom']   = '{{ data["' . $tag . '"]["margin-bottom"] }}';
			}
			preservation_show_layout(
				preservation_customizer_get_css(
					array(
						'colors'        => false,
						'scheme'        => '',
						'fonts'         => $fonts,
						'vars'          => false,
						'remove_spaces' => false,
					)
				),
				'<' . esc_html( $tmpl_holder ) . ' type="text/html" id="tmpl-preservation-fonts">',
				'</' . esc_html( $tmpl_holder ) . '>'
			);
		}

		// Theme vars
		$vars = preservation_get_theme_vars();
		if ( is_array( $vars ) && count( $vars ) > 0 ) {
			foreach ( $vars as $k => $v ) {
				$vars[ $k ] = '{{ data.' . esc_attr( $k ) . ' }}';
			}
			preservation_show_layout(
				preservation_customizer_get_css(
					array(
						'colors'        => false,
						'scheme'        => '',
						'fonts'         => false,
						'vars'          => $vars,
						'remove_spaces' => false,
					)
				),
				'<' . esc_html( $tmpl_holder ) . ' type="text/html" id="tmpl-preservation-vars">',
				'</' . esc_html( $tmpl_holder ) . '>'
			);
		}

	}
}

if ( ! function_exists( 'preservation_customizer_add_theme_colors' ) ) {
	/**
	 * Add a calculated theme-specific colors to the array. Rules to generate new colors set up in the file 'skin-setup.php'
	 * in the global array 'scheme_colors_add'
	 *
	 * @param array $colors  An array with general theme colors.
	 *
	 * @return array         A processed colors with a new (calculated) entries added.
	 */
	function preservation_customizer_add_theme_colors( $colors ) {
		$add = preservation_storage_get( 'scheme_colors_add' );
		if ( is_array( $add ) ) {
			foreach ( $add as $k => $v ) {
				if ( substr( $colors['text'], 0, 1 ) == '#' ) {
					$clr = $colors[ $v['color'] ];
					if ( isset( $v['hue'] ) || isset( $v['saturation'] ) || isset( $v['brightness'] ) ) {
						$clr = preservation_hsb2hex(
							preservation_hex2hsb(
								$clr,
								isset( $v['hue'] ) ? $v['hue'] : 0,
								isset( $v['saturation'] ) ? $v['saturation'] : 0,
								isset( $v['brightness'] ) ? $v['brightness'] : 0
							)
						);
					}
					if ( isset( $v['alpha'] ) ) {
						$clr = preservation_hex2rgba( $clr, $v['alpha'] );
					}
					$colors[ $k ] = $clr;
				} else {
					$colors[ $k ] = sprintf( '{{ data.%s }}', $k );
				}
			}
		}
		return $colors;
	}
}

if ( ! function_exists( 'preservation_customizer_add_theme_fonts' ) ) {
	/**
	 * Add a separate entries for each font property to the array $fonts.
	 *
	 * Attention! Don't forget setup fonts rules in the 'theme-customizer.js' also.
	 *
	 * @param array $fonts  An array with general theme font settings.
	 *
	 * @return array        A processed fonts with a new entries added.
	 */
	function preservation_customizer_add_theme_fonts( $fonts ) {
		$rez = array();
		foreach ( $fonts as $tag => $font ) {
			
			$rez[ $tag ] = $font;
			
			if ( substr( $font['font-family'], 0, 2 ) != '{{' ) {
				$rez[ $tag . '_font-family' ]     = ! empty( $font['font-family'] ) && ! preservation_is_inherit( $font['font-family'] )
														? 'font-family:' . trim( $font['font-family'] ) . ';'
														: '';
				$rez[ $tag . '_font-size' ]       = ! empty( $font['font-size'] ) && ! preservation_is_inherit( $font['font-size'] )
														? 'font-size:' . preservation_prepare_css_value( $font['font-size'] ) . ';'
														: '';
				$rez[ $tag . '_line-height' ]     = ! empty( $font['line-height'] ) && ! preservation_is_inherit( $font['line-height'] )
														? 'line-height:' . trim( $font['line-height'] ) . ';'
														: '';
				$rez[ $tag . '_font-weight' ]     = ! empty( $font['font-weight'] ) && ! preservation_is_inherit( $font['font-weight'] )
														? 'font-weight:' . trim( $font['font-weight'] ) . ';'
														: '';
				$rez[ $tag . '_font-style' ]      = ! empty( $font['font-style'] ) && ! preservation_is_inherit( $font['font-style'] )
														? 'font-style:' . trim( $font['font-style'] ) . ';'
														: '';
				$rez[ $tag . '_text-decoration' ] = ! empty( $font['text-decoration'] ) && ! preservation_is_inherit( $font['text-decoration'] )
														? 'text-decoration:' . trim( $font['text-decoration'] ) . ';'
														: '';
				$rez[ $tag . '_text-transform' ]  = ! empty( $font['text-transform'] ) && ! preservation_is_inherit( $font['text-transform'] )
														? 'text-transform:' . trim( $font['text-transform'] ) . ';'
														: '';
				$rez[ $tag . '_letter-spacing' ]  = ! empty( $font['letter-spacing'] ) && ! preservation_is_inherit( $font['letter-spacing'] )
														? 'letter-spacing:' . preservation_prepare_css_value( $font['letter-spacing'] ) . ';'
														: '';
				$rez[ $tag . '_margin-top' ]      = ! empty( $font['margin-top'] ) && ! preservation_is_inherit( $font['margin-top'] )
														? 'margin-top:' . preservation_prepare_css_value( $font['margin-top'] ) . ';'
														: '';
				$rez[ $tag . '_margin-bottom' ]   = ! empty( $font['margin-bottom'] ) && ! preservation_is_inherit( $font['margin-bottom'] )
														? 'margin-bottom:' . preservation_prepare_css_value( $font['margin-bottom'] ) . ';'
														: '';
			} else {
				$rez[ $tag . '_font-family' ]     = '{{ data["' . $tag . '_font-family"] }}';
				$rez[ $tag . '_font-size' ]       = '{{ data["' . $tag . '_font-size"] }}';
				$rez[ $tag . '_line-height' ]     = '{{ data["' . $tag . '_line-height"] }}';
				$rez[ $tag . '_font-weight' ]     = '{{ data["' . $tag . '_font-weight"] }}';
				$rez[ $tag . '_font-style' ]      = '{{ data["' . $tag . '_font-style"] }}';
				$rez[ $tag . '_text-decoration' ] = '{{ data["' . $tag . '_text-decoration"] }}';
				$rez[ $tag . '_text-transform' ]  = '{{ data["' . $tag . '_text-transform"] }}';
				$rez[ $tag . '_letter-spacing' ]  = '{{ data["' . $tag . '_letter-spacing"] }}';
				$rez[ $tag . '_margin-top' ]      = '{{ data["' . $tag . '_margin-top"] }}';
				$rez[ $tag . '_margin-bottom' ]   = '{{ data["' . $tag . '_margin-bottom"] }}';
			}
		}
		return $rez;
	}
}

if ( ! function_exists( 'preservation_customizer_add_theme_vars' ) ) {
	/**
	 * Add a calculated theme-specific variables to the array.
	 *
	 * Attention! Don't forget setup a vars rules in the 'theme-customizer.js' also.
	 *
	 * @param array $vars  An array with general theme vars.
	 *
	 * @return array       A processed vars with a new (calculated) entries added.
	 */
	function preservation_customizer_add_theme_vars( $vars ) {
		$rez = $vars;
		// Add border radius
		if ( isset( $vars['rad'] ) ) {
			if ( substr( $vars['rad'], 0, 2 ) != '{{' ) {
				$rez['rad']      = preservation_prepare_css_value( ! empty( $vars['rad'] ) ? $vars['rad'] : 0 );
				$rez['rad_koef'] = ! empty( $vars['rad'] ) ? 1 : 0;
			} else {
				$rez['rad_koef'] = '{{ data.rad_koef }}';
			}
		}
		// Add page components
		if ( isset( $vars['page_width'] ) ) {
			if ( substr( $vars['page_width'], 0, 2 ) != '{{' ) {
				if ( empty( $vars['page_width'] ) ) {
					$vars['page_width'] = apply_filters( 'preservation_filter_content_width', preservation_get_theme_option( 'page_width' ) );
				}
				$rez['page_width']          = preservation_prepare_css_value( $vars['page_width'] );
				$rez['page_boxed_extra']    = preservation_prepare_css_value( $vars['page_boxed_extra'] );
				$rez['page_fullwide_extra'] = preservation_prepare_css_value( $vars['page_fullwide_extra'] );
				$rez['page_fullwide_max']   = preservation_prepare_css_value( $vars['page_fullwide_max'] );
				$rez['grid_gap']            = preservation_prepare_css_value( $vars['grid_gap'] );
				$rez['sidebar_prc']         = (int)$vars['sidebar_width'] / (int)$vars['page_width'];
				$rez['sidebar_gap_prc']     = (int)$vars['sidebar_gap'] / (int)$vars['page_width'];
				$rez['sidebar_width']       = preservation_prepare_css_value( $vars['sidebar_width'] );
				$rez['sidebar_gap']         = preservation_prepare_css_value( $vars['sidebar_gap'] );
			} else {
				$rez['sidebar_prc']     = '{{ data.sidebar_prc }}';
				$rez['sidebar_gap_prc'] = '{{ data.sidebar_gap_prc }}';
			}
		}
		return apply_filters( 'preservation_filter_add_theme_vars', $rez, $vars );
	}
}


//----------------------------------------------------------------------------------------------
// Add a fix to allow theme-specific sidebars in Customizer (if is_customize_preview() mode)
//----------------------------------------------------------------------------------------------
if ( ! function_exists( 'preservation_customizer_fix_sidebars' ) && is_customize_preview() && is_front_page() ) {
	add_action( 'wp_footer', 'preservation_customizer_fix_sidebars' );
	/**
	 * Output all registeres sidebars to the footer and make its hidden
	 * to allow theme-specific sidebars in Customizer preview mode.
	 *
	 * Hooks: add_action( 'wp_footer', 'preservation_customizer_fix_sidebars' );
	 */
	function preservation_customizer_fix_sidebars() {
		$sidebars = preservation_get_sidebars();
		if ( is_array( $sidebars ) ) {
			foreach ( $sidebars as $sb => $params ) {
				if ( ! empty( $params['front_page_section'] ) && is_active_sidebar( $sb ) ) {
					?>
					<div class="hidden"><?php dynamic_sidebar( $sb ); ?></div><?php
				}
			}
		}
	}
}


// Load theme options and styles
require_once PRESERVATION_THEME_DIR . 'theme-specific/theme-setup.php';
require_once PRESERVATION_THEME_DIR . 'theme-options/theme-customizer-css-vars.php';
require_once PRESERVATION_THEME_DIR . 'theme-options/theme-options.php';
require_once PRESERVATION_THEME_DIR . 'theme-options/theme-options-override.php';
if ( ! PRESERVATION_THEME_FREE ) {
	require_once PRESERVATION_THEME_DIR . 'theme-options/theme-options-qsetup.php';
}
