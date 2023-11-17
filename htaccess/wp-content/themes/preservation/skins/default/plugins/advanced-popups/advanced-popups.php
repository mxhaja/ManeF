<?php

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'preservation_advanced_popups_theme_setup9' ) ) {
    add_action( 'after_setup_theme', 'preservation_advanced_popups_theme_setup9', 9 );
    function preservation_advanced_popups_theme_setup9() {
        if ( is_admin() ) {
            add_filter( 'preservation_filter_tgmpa_required_plugins', 'preservation_advanced_popups_tgmpa_required_plugins' );
        }
    }
}

// Filter to add in the required plugins list
if ( ! function_exists( 'preservation_advanced_popups_tgmpa_required_plugins' ) ) {    
    function preservation_advanced_popups_tgmpa_required_plugins( $list = array() ) {
        if ( preservation_storage_isset( 'required_plugins', 'advanced-popups' ) && preservation_storage_get_array( 'required_plugins', 'advanced-popups', 'install' ) !== false ) {
            $list[] = array(
                'name'     => preservation_storage_get_array( 'required_plugins', 'advanced-popups', 'title' ),
                'slug'     => 'advanced-popups',
                'required' => false,
            );
        }
        return $list;
    }
}

// Check if plugin installed and activated
if ( ! function_exists( 'preservation_exists_advanced_popups' ) ) {
    function preservation_exists_advanced_popups() {
        return function_exists('adp_init');
    }
}
