<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0
 */

if ( preservation_sidebar_present() ) {
	
	$preservation_sidebar_type = preservation_get_theme_option( 'sidebar_type' );
	if ( 'custom' == $preservation_sidebar_type && ! preservation_is_layouts_available() ) {
		$preservation_sidebar_type = 'default';
	}
	
	// Catch output to the buffer
	ob_start();
	if ( 'default' == $preservation_sidebar_type ) {
		// Default sidebar with widgets
		$preservation_sidebar_name = preservation_get_theme_option( 'sidebar_widgets' );
		preservation_storage_set( 'current_sidebar', 'sidebar' );
		if ( is_active_sidebar( $preservation_sidebar_name ) ) {
			dynamic_sidebar( $preservation_sidebar_name );
		}
	} else {
		// Custom sidebar from Layouts Builder
		$preservation_sidebar_id = preservation_get_custom_sidebar_id();
		do_action( 'preservation_action_show_layout', $preservation_sidebar_id );
	}
	$preservation_out = trim( ob_get_contents() );
	ob_end_clean();
	
	// If any html is present - display it
	if ( ! empty( $preservation_out ) ) {
		$preservation_sidebar_position    = preservation_get_theme_option( 'sidebar_position' );
		$preservation_sidebar_position_ss = preservation_get_theme_option( 'sidebar_position_ss' );
		?>
		<div class="sidebar widget_area
			<?php
			echo ' ' . esc_attr( $preservation_sidebar_position );
			echo ' sidebar_' . esc_attr( $preservation_sidebar_position_ss );
			echo ' sidebar_' . esc_attr( $preservation_sidebar_type );

			$preservation_sidebar_scheme = apply_filters( 'preservation_filter_sidebar_scheme', preservation_get_theme_option( 'sidebar_scheme' ) );
			if ( ! empty( $preservation_sidebar_scheme ) && ! preservation_is_inherit( $preservation_sidebar_scheme ) && 'custom' != $preservation_sidebar_type ) {
				echo ' scheme_' . esc_attr( $preservation_sidebar_scheme );
			}
			?>
		" role="complementary">
			<?php

			// Skip link anchor to fast access to the sidebar from keyboard
			?>
			<a id="sidebar_skip_link_anchor" class="preservation_skip_link_anchor" href="#"></a>
			<?php

			do_action( 'preservation_action_before_sidebar_wrap', 'sidebar' );

			// Button to show/hide sidebar on mobile
			if ( in_array( $preservation_sidebar_position_ss, array( 'above', 'float' ) ) ) {
				$preservation_title = apply_filters( 'preservation_filter_sidebar_control_title', 'float' == $preservation_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'preservation' ) : '' );
				$preservation_text  = apply_filters( 'preservation_filter_sidebar_control_text', 'above' == $preservation_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'preservation' ) : '' );
				?>
				<a href="#" class="sidebar_control" title="<?php echo esc_attr( $preservation_title ); ?>"><?php echo esc_html( $preservation_text ); ?></a>
				<?php
			}
			?>
			<div class="sidebar_inner">
				<?php
				do_action( 'preservation_action_before_sidebar', 'sidebar' );
				preservation_show_layout( preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $preservation_out ) );
				do_action( 'preservation_action_after_sidebar', 'sidebar' );
				?>
			</div>
			<?php

			do_action( 'preservation_action_after_sidebar_wrap', 'sidebar' );

			?>
		</div>
		<div class="clearfix"></div>
		<?php
	}
}
