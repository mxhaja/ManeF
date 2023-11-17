<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'preservation_mailchimp_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'preservation_mailchimp_theme_setup9', 9 );
	function preservation_mailchimp_theme_setup9() {
		if ( preservation_exists_mailchimp() ) {
			add_action( 'wp_enqueue_scripts', 'preservation_mailchimp_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_mailchimp', 'preservation_mailchimp_frontend_scripts', 10, 1 );
			add_filter( 'preservation_filter_merge_styles', 'preservation_mailchimp_merge_styles' );
		}
		if ( is_admin() ) {
			add_filter( 'preservation_filter_tgmpa_required_plugins', 'preservation_mailchimp_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'preservation_mailchimp_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('preservation_filter_tgmpa_required_plugins',	'preservation_mailchimp_tgmpa_required_plugins');
	function preservation_mailchimp_tgmpa_required_plugins( $list = array() ) {
		if ( preservation_storage_isset( 'required_plugins', 'mailchimp-for-wp' ) && preservation_storage_get_array( 'required_plugins', 'mailchimp-for-wp', 'install' ) !== false ) {
			$list[] = array(
				'name'     => preservation_storage_get_array( 'required_plugins', 'mailchimp-for-wp', 'title' ),
				'slug'     => 'mailchimp-for-wp',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( ! function_exists( 'preservation_exists_mailchimp' ) ) {
	function preservation_exists_mailchimp() {
		return function_exists( '__mc4wp_load_plugin' ) || defined( 'MC4WP_VERSION' );
	}
}



// Custom styles and scripts
//------------------------------------------------------------------------

// Enqueue styles for frontend
if ( ! function_exists( 'preservation_mailchimp_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'preservation_mailchimp_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_mailchimp', 'preservation_mailchimp_frontend_scripts', 10, 1 );
	function preservation_mailchimp_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && preservation_need_frontend_scripts( 'mailchimp' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$preservation_url = preservation_get_file_url( 'plugins/mailchimp-for-wp/mailchimp-for-wp.css' );
			if ( '' != $preservation_url ) {
				wp_enqueue_style( 'preservation-mailchimp-for-wp', $preservation_url, array(), null );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'preservation_mailchimp_merge_styles' ) ) {
	//Handler of the add_filter( 'preservation_filter_merge_styles', 'preservation_mailchimp_merge_styles');
	function preservation_mailchimp_merge_styles( $list ) {
		$list[ 'plugins/mailchimp-for-wp/mailchimp-for-wp.css' ] = false;
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if ( preservation_exists_mailchimp() ) {
	$preservation_fdir = preservation_get_file_dir( 'plugins/mailchimp-for-wp/mailchimp-for-wp-style.php' );
	if ( ! empty( $preservation_fdir ) ) {
		require_once $preservation_fdir;
	}
}

