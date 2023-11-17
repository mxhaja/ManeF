<?php
/* Contact Form 7 support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'preservation_cf7_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'preservation_cf7_theme_setup9', 9 );
	function preservation_cf7_theme_setup9() {
		if ( preservation_exists_cf7() ) {
			add_action( 'wp_enqueue_scripts', 'preservation_cf7_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_cf7', 'preservation_cf7_frontend_scripts', 10, 1 );
			add_filter( 'preservation_filter_merge_styles', 'preservation_cf7_merge_styles' );
			add_filter( 'preservation_filter_merge_scripts', 'preservation_cf7_merge_scripts' );
		}
		if ( is_admin() ) {
			add_filter( 'preservation_filter_tgmpa_required_plugins', 'preservation_cf7_tgmpa_required_plugins' );
			add_filter( 'preservation_filter_theme_plugins', 'preservation_cf7_theme_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'preservation_cf7_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('preservation_filter_tgmpa_required_plugins',	'preservation_cf7_tgmpa_required_plugins');
	function preservation_cf7_tgmpa_required_plugins( $list = array() ) {
		if ( preservation_storage_isset( 'required_plugins', 'contact-form-7' ) && preservation_storage_get_array( 'required_plugins', 'contact-form-7', 'install' ) !== false ) {
			// CF7 plugin
			$list[] = array(
				'name'     => preservation_storage_get_array( 'required_plugins', 'contact-form-7', 'title' ),
				'slug'     => 'contact-form-7',
				'required' => false,
			);
		}
		return $list;
	}
}

// Filter theme-supported plugins list
if ( ! function_exists( 'preservation_cf7_theme_plugins' ) ) {
	//Handler of the add_filter( 'preservation_filter_theme_plugins', 'preservation_cf7_theme_plugins' );
	function preservation_cf7_theme_plugins( $list = array() ) {
		return preservation_add_group_and_logo_to_slave( $list, 'contact-form-7', 'contact-form-7-' );
	}
}



// Check if cf7 installed and activated
if ( ! function_exists( 'preservation_exists_cf7' ) ) {
	function preservation_exists_cf7() {
		return class_exists( 'WPCF7' );
	}
}

// Enqueue custom scripts
if ( ! function_exists( 'preservation_cf7_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'preservation_cf7_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_cf7', 'preservation_cf7_frontend_scripts', 10, 1 );
	function preservation_cf7_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && preservation_need_frontend_scripts( 'cf7' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$preservation_url = preservation_get_file_url( 'plugins/contact-form-7/contact-form-7.css' );
			if ( '' != $preservation_url ) {
				wp_enqueue_style( 'preservation-contact-form-7', $preservation_url, array(), null );
			}
			$preservation_url = preservation_get_file_url( 'plugins/contact-form-7/contact-form-7.js' );
			if ( '' != $preservation_url ) {
				wp_enqueue_script( 'preservation-contact-form-7', $preservation_url, array( 'jquery' ), null, true );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'preservation_cf7_merge_styles' ) ) {
	//Handler of the add_filter('preservation_filter_merge_styles', 'preservation_cf7_merge_styles');
	function preservation_cf7_merge_styles( $list ) {
		$list[ 'plugins/contact-form-7/contact-form-7.css' ] = false;
		return $list;
	}
}

// Merge custom scripts
if ( ! function_exists( 'preservation_cf7_merge_scripts' ) ) {
	//Handler of the add_filter('preservation_filter_merge_scripts', 'preservation_cf7_merge_scripts');
	function preservation_cf7_merge_scripts( $list ) {
		$list[ 'plugins/contact-form-7/contact-form-7.js' ] = false;
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if ( preservation_exists_cf7() ) {
	$preservation_fdir = preservation_get_file_dir( 'plugins/contact-form-7/contact-form-7-style.php' );
	if ( ! empty( $preservation_fdir ) ) {
		require_once $preservation_fdir;
	}
}
