<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0.10
 */

// Footer sidebar
$preservation_footer_name    = preservation_get_theme_option( 'footer_widgets' );
$preservation_footer_present = ! preservation_is_off( $preservation_footer_name ) && is_active_sidebar( $preservation_footer_name );
if ( $preservation_footer_present ) {
	preservation_storage_set( 'current_sidebar', 'footer' );
	$preservation_footer_wide = preservation_get_theme_option( 'footer_wide' );
	ob_start();
	if ( is_active_sidebar( $preservation_footer_name ) ) {
		dynamic_sidebar( $preservation_footer_name );
	}
	$preservation_out = trim( ob_get_contents() );
	ob_end_clean();
	if ( ! empty( $preservation_out ) ) {
		$preservation_out          = preg_replace( "/<\\/aside>[\r\n\s]*<aside/", '</aside><aside', $preservation_out );
		$preservation_need_columns = true;   //or check: strpos($preservation_out, 'columns_wrap')===false;
		if ( $preservation_need_columns ) {
			$preservation_columns = max( 0, (int) preservation_get_theme_option( 'footer_columns' ) );			
			if ( 0 == $preservation_columns ) {
				$preservation_columns = min( 4, max( 1, preservation_tags_count( $preservation_out, 'aside' ) ) );
			}
			if ( $preservation_columns > 1 ) {
				$preservation_out = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $preservation_columns ) . ' widget', $preservation_out );
			} else {
				$preservation_need_columns = false;
			}
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo ! empty( $preservation_footer_wide ) ? ' footer_fullwidth' : ''; ?> sc_layouts_row sc_layouts_row_type_normal">
			<?php do_action( 'preservation_action_before_sidebar_wrap', 'footer' ); ?>
			<div class="footer_widgets_inner widget_area_inner">
				<?php
				if ( ! $preservation_footer_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $preservation_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'preservation_action_before_sidebar', 'footer' );
				preservation_show_layout( $preservation_out );
				do_action( 'preservation_action_after_sidebar', 'footer' );
				if ( $preservation_need_columns ) {
					?>
					</div><!-- /.columns_wrap -->
					<?php
				}
				if ( ! $preservation_footer_wide ) {
					?>
					</div><!-- /.content_wrap -->
					<?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
			<?php do_action( 'preservation_action_after_sidebar_wrap', 'footer' ); ?>
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
