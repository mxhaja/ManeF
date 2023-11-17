<?php
/**
 * The template to display Admin notices
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0.64
 */

$preservation_skins_url  = get_admin_url( null, 'admin.php?page=trx_addons_theme_panel#trx_addons_theme_panel_section_skins' );
$preservation_skins_args = get_query_var( 'preservation_skins_notice_args' );
?>
<div class="preservation_admin_notice preservation_skins_notice notice notice-info is-dismissible" data-notice="skins">
	<?php
	// Theme image
	$preservation_theme_img = preservation_get_file_url( 'screenshot.jpg' );
	if ( '' != $preservation_theme_img ) {
		?>
		<div class="preservation_notice_image"><img src="<?php echo esc_url( $preservation_theme_img ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', 'preservation' ); ?>"></div>
		<?php
	}

	// Title
	?>
	<h3 class="preservation_notice_title">
		<?php esc_html_e( 'New skins available', 'preservation' ); ?>
	</h3>
	<?php

	// Description
	$preservation_total      = $preservation_skins_args['update'];	// Store value to the separate variable to avoid warnings from ThemeCheck plugin!
	$preservation_skins_msg  = $preservation_total > 0
							// Translators: Add new skins number
							? '<strong>' . sprintf( _n( '%d new version', '%d new versions', $preservation_total, 'preservation' ), $preservation_total ) . '</strong>'
							: '';
	$preservation_total      = $preservation_skins_args['free'];
	$preservation_skins_msg .= $preservation_total > 0
							? ( ! empty( $preservation_skins_msg ) ? ' ' . esc_html__( 'and', 'preservation' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d free skin', '%d free skins', $preservation_total, 'preservation' ), $preservation_total ) . '</strong>'
							: '';
	$preservation_total      = $preservation_skins_args['pay'];
	$preservation_skins_msg .= $preservation_skins_args['pay'] > 0
							? ( ! empty( $preservation_skins_msg ) ? ' ' . esc_html__( 'and', 'preservation' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d paid skin', '%d paid skins', $preservation_total, 'preservation' ), $preservation_total ) . '</strong>'
							: '';
	?>
	<div class="preservation_notice_text">
		<p>
			<?php
			// Translators: Add new skins info
			echo wp_kses_data( sprintf( __( "We are pleased to announce that %s are available for your theme", 'preservation' ), $preservation_skins_msg ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="preservation_notice_buttons">
		<?php
		// Link to the theme dashboard page
		?>
		<a href="<?php echo esc_url( $preservation_skins_url ); ?>" class="button button-primary"><i class="dashicons dashicons-update"></i> 
			<?php
			// Translators: Add theme name
			esc_html_e( 'Go to Skins manager', 'preservation' );
			?>
		</a>
	</div>
</div>
