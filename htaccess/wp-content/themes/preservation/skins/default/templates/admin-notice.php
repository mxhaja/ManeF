<?php
/**
 * The template to display Admin notices
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0.1
 */

$preservation_theme_slug = get_option( 'template' );
$preservation_theme_obj  = wp_get_theme( $preservation_theme_slug );
?>
<div class="preservation_admin_notice preservation_welcome_notice notice notice-info is-dismissible" data-notice="admin">
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
		<?php
		echo esc_html(
			sprintf(
				// Translators: Add theme name and version to the 'Welcome' message
				__( 'Welcome to %1$s v.%2$s', 'preservation' ),
				$preservation_theme_obj->get( 'Name' ) . ( PRESERVATION_THEME_FREE ? ' ' . __( 'Free', 'preservation' ) : '' ),
				$preservation_theme_obj->get( 'Version' )
			)
		);
		?>
	</h3>
	<?php

	// Description
	?>
	<div class="preservation_notice_text">
		<p class="preservation_notice_text_description">
			<?php
			echo str_replace( '. ', '.<br>', wp_kses_data( $preservation_theme_obj->description ) );
			?>
		</p>
		<p class="preservation_notice_text_info">
			<?php
			echo wp_kses_data( __( 'Attention! Plugin "ThemeREX Addons" is required! Please, install and activate it!', 'preservation' ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="preservation_notice_buttons">
		<?php
		// Link to the page 'About Theme'
		?>
		<a href="<?php echo esc_url( admin_url() . 'themes.php?page=preservation_about' ); ?>" class="button button-primary"><i class="dashicons dashicons-nametag"></i> 
			<?php
			echo esc_html__( 'Install plugin "ThemeREX Addons"', 'preservation' );
			?>
		</a>
	</div>
</div>
