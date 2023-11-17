<?php
/**
 * Required plugins
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.76.0
 */

// THEME-SUPPORTED PLUGINS
// If plugin not need - remove its settings from next array
//----------------------------------------------------------
$preservation_theme_required_plugins_groups = array(
	'core'          => esc_html__( 'Core', 'preservation' ),
	'page_builders' => esc_html__( 'Page Builders', 'preservation' ),
	'ecommerce'     => esc_html__( 'E-Commerce & Donations', 'preservation' ),
	'socials'       => esc_html__( 'Socials and Communities', 'preservation' ),
	'events'        => esc_html__( 'Events and Appointments', 'preservation' ),
	'content'       => esc_html__( 'Content', 'preservation' ),
	'other'         => esc_html__( 'Other', 'preservation' ),
);
$preservation_theme_required_plugins        = array(
	'trx_addons'                 => array(
		'title'       => esc_html__( 'ThemeREX Addons', 'preservation' ),
		'description' => esc_html__( "Will allow you to install recommended plugins, demo content, and improve the theme's functionality overall with multiple theme options", 'preservation' ),
		'required'    => true,
		'logo'        => 'trx_addons.png',
		'group'       => $preservation_theme_required_plugins_groups['core'],
	),
	'elementor'                  => array(
		'title'       => esc_html__( 'Elementor', 'preservation' ),
		'description' => esc_html__( "Is a beautiful PageBuilder, even the free version of which allows you to create great pages using a variety of modules.", 'preservation' ),
		'required'    => false,
		'logo'        => 'elementor.png',
		'group'       => $preservation_theme_required_plugins_groups['page_builders'],
	),
	'gutenberg'                  => array(
		'title'       => esc_html__( 'Gutenberg', 'preservation' ),
		'description' => esc_html__( "It's a posts editor coming in place of the classic TinyMCE. Can be installed and used in parallel with Elementor", 'preservation' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'gutenberg.png',
		'group'       => $preservation_theme_required_plugins_groups['page_builders'],
	),
	'js_composer'                => array(
		'title'       => esc_html__( 'WPBakery PageBuilder', 'preservation' ),
		'description' => esc_html__( "Popular PageBuilder which allows you to create excellent pages", 'preservation' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'js_composer.jpg',
		'group'       => $preservation_theme_required_plugins_groups['page_builders'],
	),
	'woocommerce'                => array(
		'title'       => esc_html__( 'WooCommerce', 'preservation' ),
		'description' => esc_html__( "Connect the store to your website and start selling now", 'preservation' ),
		'required'    => false,
		'logo'        => 'woocommerce.png',
		'group'       => $preservation_theme_required_plugins_groups['ecommerce'],
	),
	'elegro-payment'             => array(
		'title'       => esc_html__( 'Elegro Crypto Payment', 'preservation' ),
		'description' => esc_html__( "Extends WooCommerce Payment Gateways with an elegro Crypto Payment", 'preservation' ),
		'required'    => false,
		'logo'        => 'elegro-payment.png',
		'group'       => $preservation_theme_required_plugins_groups['ecommerce'],
	),
	'give'                       => array(
		'title'       => esc_html__( 'Give', 'preservation' ),
		'description' => '',
        	'required'    => false,
        	'logo'        => preservation_get_file_url( 'plugins/give/give.png' ),
        	'group'       => $preservation_theme_required_plugins_groups['ecommerce'],
    	),
	'instagram-feed'             => array(
		'title'       => esc_html__( 'Instagram Feed', 'preservation' ),
		'description' => esc_html__( "Displays the latest photos from your profile on Instagram", 'preservation' ),
		'required'    => false,
		'logo'        => 'instagram-feed.png',
		'group'       => $preservation_theme_required_plugins_groups['socials'],
	),
	'mailchimp-for-wp'           => array(
		'title'       => esc_html__( 'MailChimp for WP', 'preservation' ),
		'description' => esc_html__( "Allows visitors to subscribe to newsletters", 'preservation' ),
		'required'    => false,
		'logo'        => 'mailchimp-for-wp.png',
		'group'       => $preservation_theme_required_plugins_groups['socials'],
	),
	'booked'                     => array(
		'title'       => esc_html__( 'Booked Appointments', 'preservation' ),
		'description' => '',
		'required'    => false,
        	'install'     => false,
		'logo'        => 'booked.png',
		'group'       => $preservation_theme_required_plugins_groups['events'],
	),
	'the-events-calendar'        => array(
		'title'       => esc_html__( 'The Events Calendar', 'preservation' ),
		'description' => '',
		'required'    => false,
        	'install'     => false,
		'logo'        => 'the-events-calendar.png',
		'group'       => $preservation_theme_required_plugins_groups['events'],
	),
	'contact-form-7'             => array(
		'title'       => esc_html__( 'Contact Form 7', 'preservation' ),
		'description' => esc_html__( "CF7 allows you to create an unlimited number of contact forms", 'preservation' ),
		'required'    => false,
		'logo'        => 'contact-form-7.png',
		'group'       => $preservation_theme_required_plugins_groups['content'],
	),

	'latepoint'                  => array(
		'title'       => esc_html__( 'LatePoint', 'preservation' ),
		'description' => '',
		'required'    => false,
        	'install'     => false,
		'logo'        => preservation_get_file_url( 'plugins/latepoint/latepoint.png' ),
		'group'       => $preservation_theme_required_plugins_groups['events'],
	),
	'advanced-popups'                  => array(
		'title'       => esc_html__( 'Advanced Popups', 'preservation' ),
		'description' => '',
		'required'    => false,
		'logo'        => preservation_get_file_url( 'plugins/advanced-popups/advanced-popups.jpg' ),
		'group'       => $preservation_theme_required_plugins_groups['content'],
	),
	'devvn-image-hotspot'                  => array(
		'title'       => esc_html__( 'Image Hotspot by DevVN', 'preservation' ),
		'description' => '',
		'required'    => false,
        	'install'     => false,
		'logo'        => preservation_get_file_url( 'plugins/devvn-image-hotspot/devvn-image-hotspot.png' ),
		'group'       => $preservation_theme_required_plugins_groups['content'],
	),
	'ti-woocommerce-wishlist'                  => array(
		'title'       => esc_html__( 'TI WooCommerce Wishlist', 'preservation' ),
		'description' => '',
		'required'    => false,
		'logo'        => preservation_get_file_url( 'plugins/ti-woocommerce-wishlist/ti-woocommerce-wishlist.png' ),
		'group'       => $preservation_theme_required_plugins_groups['ecommerce'],
	),
	'woo-smart-quick-view'                  => array(
		'title'       => esc_html__( 'WPC Smart Quick View for WooCommerce', 'preservation' ),
		'description' => '',
		'required'    => false,
        	'install'     => false,
		'logo'        => preservation_get_file_url( 'plugins/woo-smart-quick-view/woo-smart-quick-view.png' ),
		'group'       => $preservation_theme_required_plugins_groups['ecommerce'],
	),
	'twenty20'                  => array(
		'title'       => esc_html__( 'Twenty20 Image Before-After', 'preservation' ),
		'description' => '',
		'required'    => false,
        	'install'     => false,
		'logo'        => preservation_get_file_url( 'plugins/twenty20/twenty20.png' ),
		'group'       => $preservation_theme_required_plugins_groups['content'],
	),
	'essential-grid'             => array(
		'title'       => esc_html__( 'Essential Grid', 'preservation' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'essential-grid.png',
		'group'       => $preservation_theme_required_plugins_groups['content'],
	),
	'revslider'                  => array(
		'title'       => esc_html__( 'Revolution Slider', 'preservation' ),
		'description' => '',
		'required'    => false,
		'logo'        => 'revslider.png',
		'group'       => $preservation_theme_required_plugins_groups['content'],
	),
	'sitepress-multilingual-cms' => array(
		'title'       => esc_html__( 'WPML - Sitepress Multilingual CMS', 'preservation' ),
		'description' => esc_html__( "Allows you to make your website multilingual", 'preservation' ),
		'required'    => false,
		'install'     => false,      // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'sitepress-multilingual-cms.png',
		'group'       => $preservation_theme_required_plugins_groups['content'],
	),
	'wp-gdpr-compliance'         => array(
		'title'       => esc_html__( 'Cookie Information', 'preservation' ),
		'description' => esc_html__( "Allow visitors to decide for themselves what personal data they want to store on your site", 'preservation' ),
		'required'    => false,
		'logo'        => 'wp-gdpr-compliance.png',
		'group'       => $preservation_theme_required_plugins_groups['other'],
	),
	'trx_updater'                => array(
		'title'       => esc_html__( 'ThemeREX Updater', 'preservation' ),
		'description' => esc_html__( "Update theme and theme-specific plugins from developer's upgrade server.", 'preservation' ),
		'required'    => false,
		'logo'        => 'trx_updater.png',
		'group'       => $preservation_theme_required_plugins_groups['other'],
	),
);

if ( PRESERVATION_THEME_FREE ) {
	unset( $preservation_theme_required_plugins['js_composer'] );
	unset( $preservation_theme_required_plugins['booked'] );
	unset( $preservation_theme_required_plugins['the-events-calendar'] );
	unset( $preservation_theme_required_plugins['calculated-fields-form'] );
	unset( $preservation_theme_required_plugins['essential-grid'] );
	unset( $preservation_theme_required_plugins['revslider'] );
	unset( $preservation_theme_required_plugins['sitepress-multilingual-cms'] );
	unset( $preservation_theme_required_plugins['trx_updater'] );
	unset( $preservation_theme_required_plugins['trx_popup'] );
}

// Add plugins list to the global storage
preservation_storage_set( 'required_plugins', $preservation_theme_required_plugins );
