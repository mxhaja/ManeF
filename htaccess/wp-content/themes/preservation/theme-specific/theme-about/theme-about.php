<?php
/**
 * Information about this theme
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0.30
 */


if ( ! function_exists( 'preservation_about_after_switch_theme' ) ) {
	add_action( 'after_switch_theme', 'preservation_about_after_switch_theme', 1000 );
	/**
	 * Update option 'preservation_about_page' after switch a theme to redirect to the page 'About Theme' on next page load.
	 *
	 * Hooks: add_action( 'after_switch_theme', 'preservation_about_after_switch_theme', 1000 );
	 */
	function preservation_about_after_switch_theme() {
		update_option( 'preservation_about_page', 1 );
	}
}

if ( ! function_exists( 'preservation_about_after_setup_theme' ) ) {
	add_action( 'init', 'preservation_about_after_setup_theme', 1000 );
	/**
	 * Redirect to the page 'About Theme' after switch a theme.
	 *
	 * Hooks: add_action( 'init', 'preservation_about_after_setup_theme', 1000 );
	 */
	function preservation_about_after_setup_theme() {
		if ( ! defined( 'WP_CLI' ) && get_option( 'preservation_about_page' ) == 1 ) {
			update_option( 'preservation_about_page', 0 );
			wp_safe_redirect( admin_url() . 'themes.php?page=preservation_about' );
			exit();
		} else {
			if ( preservation_get_value_gp( 'page' ) == 'preservation_about' && preservation_exists_trx_addons() ) {
				wp_safe_redirect( admin_url() . 'admin.php?page=trx_addons_theme_panel' );
				exit();
			}
		}
	}
}

if ( ! function_exists( 'preservation_about_add_menu_items' ) ) {
	add_action( 'admin_menu', 'preservation_about_add_menu_items' );
	/**
	 * Add the item 'About Theme' to the admin menu 'Appearance'.
	 *
	 * Hooks: add_action( 'admin_menu', 'preservation_about_add_menu_items' );
	 */
	function preservation_about_add_menu_items() {
		if ( ! preservation_exists_trx_addons() ) {
			$theme_slug  = get_template();
			$theme_name  = wp_get_theme( $theme_slug )->get( 'Name' ) . ( PRESERVATION_THEME_FREE ? ' ' . esc_html__( 'Free', 'preservation' ) : '' );
			add_theme_page(
				// Translators: Add theme name to the page title
				sprintf( esc_html__( 'About %s', 'preservation' ), $theme_name ),    //page_title
				// Translators: Add theme name to the menu title
				sprintf( esc_html__( 'About %s', 'preservation' ), $theme_name ),    //menu_title
				'manage_options',                                               //capability
				'preservation_about',                                                //menu_slug
				'preservation_about_page_builder'                                    //callback
			);
		}
	}
}

if ( ! function_exists( 'preservation_about_enqueue_scripts' ) ) {
	add_action( 'admin_enqueue_scripts', 'preservation_about_enqueue_scripts' );
	/**
	 * Load a page-specific scripts and styles for the page 'About'
	 *
	 * Hooks: add_action( 'admin_enqueue_scripts', 'preservation_about_enqueue_scripts' );
	 */
	function preservation_about_enqueue_scripts() {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;
		if ( ! empty( $screen->id ) && false !== strpos( $screen->id, '_page_preservation_about' ) ) {
			// Scripts
			if ( ! preservation_exists_trx_addons() && function_exists( 'preservation_plugins_installer_enqueue_scripts' ) ) {
				preservation_plugins_installer_enqueue_scripts();
			}
			// Styles
			$fdir = preservation_get_file_url( 'theme-specific/theme-about/theme-about.css' );
			if ( '' != $fdir ) {
				wp_enqueue_style( 'preservation-about', $fdir, array(), null );
			}
		}
	}
}

if ( ! function_exists( 'preservation_about_page_builder' ) ) {
	/**
	 * Build the page 'About Theme'
	 */
	function preservation_about_page_builder() {
		$theme_slug = get_template();
		$theme      = wp_get_theme( $theme_slug );
		?>
		<div class="preservation_about">

			<?php do_action( 'preservation_action_theme_about_start', $theme ); ?>

			<?php do_action( 'preservation_action_theme_about_before_logo', $theme ); ?>

			<div class="preservation_about_logo">
				<?php
				$logo = preservation_get_file_url( 'theme-specific/theme-about/icon.jpg' );
				if ( empty( $logo ) ) {
					$logo = preservation_get_file_url( 'screenshot.jpg' );
				}
				if ( ! empty( $logo ) ) {
					?>
					<img src="<?php echo esc_url( $logo ); ?>">
					<?php
				}
				?>
			</div>

			<?php do_action( 'preservation_action_theme_about_before_title', $theme ); ?>

			<h1 class="preservation_about_title">
			<?php
				echo esc_html(
					sprintf(
						// Translators: Add theme name and version to the 'Welcome' message
						__( 'Welcome to %1$s %2$s v.%3$s', 'preservation' ),
						$theme->get( 'Name' ),
						PRESERVATION_THEME_FREE ? __( 'Free', 'preservation' ) : '',
						$theme->get( 'Version' )
					)
				);
			?>
			</h1>

			<?php do_action( 'preservation_action_theme_about_before_description', $theme ); ?>

			<div class="preservation_about_description">
				<p>
					<?php
					echo wp_kses_data( __( 'In order to continue, please install and activate <b>ThemeREX Addons plugin</b>.', 'preservation' ) );
					?>
					<sup>*</sup>
				</p>
			</div>

			<?php do_action( 'preservation_action_theme_about_before_buttons', $theme ); ?>

			<div class="preservation_about_buttons">
				<?php preservation_plugins_installer_get_button_html( 'trx_addons' ); ?>
			</div>

			<?php do_action( 'preservation_action_theme_about_before_buttons', $theme ); ?>

			<div class="preservation_about_notes">
				<p>
					<sup>*</sup>
					<?php
					echo wp_kses_data( __( "<i>ThemeREX Addons plugin</i> will allow you to install recommended plugins, demo content, and improve the theme's functionality overall with multiple theme options.", 'preservation' ) );
					?>
				</p>
			</div>

			<?php do_action( 'preservation_action_theme_about_end', $theme ); ?>

		</div>
		<?php
	}
}

if ( ! function_exists( 'preservation_about_page_disable_tgmpa_notice' ) ) {
	add_filter( 'tgmpa_show_admin_notice_capability', 'preservation_about_page_disable_tgmpa_notice' );
	/**
	 * Hide a TGMPA notice on the page 'About Theme'
	 *
	 * @param $cap  Capability of the current page.
	 *
	 * @return string  A filtered capability.
	 */
	function preservation_about_page_disable_tgmpa_notice($cap) {
		if ( preservation_get_value_gp( 'page' ) == 'preservation_about' ) {
			$cap = 'unfiltered_upload';
		}
		return $cap;
	}
}

require_once PRESERVATION_THEME_DIR . 'includes/plugins-installer/plugins-installer.php';
