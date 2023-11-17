<?php
/**
 * The template to display the widgets area in the header
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0
 */

// Header sidebar
$preservation_header_name    = preservation_get_theme_option( 'header_widgets' );
$preservation_header_present = ! preservation_is_off( $preservation_header_name ) && is_active_sidebar( $preservation_header_name );
if ( $preservation_header_present ) {
	preservation_storage_set( 'current_sidebar', 'header' );
	$preservation_header_wide = preservation_get_theme_option( 'header_wide' );
	ob_start();
	if ( is_active_sidebar( $preservation_header_name ) ) {
		dynamic_sidebar( $preservation_header_name );
	}
	$preservation_widgets_output = ob_get_contents();
	ob_end_clean();
	if ( ! empty( $preservation_widgets_output ) ) {
		$preservation_widgets_output = preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $preservation_widgets_output );
		$preservation_need_columns   = strpos( $preservation_widgets_output, 'columns_wrap' ) === false;
		if ( $preservation_need_columns ) {
			$preservation_columns = max( 0, (int) preservation_get_theme_option( 'header_columns' ) );
			if ( 0 == $preservation_columns ) {
				$preservation_columns = min( 6, max( 1, preservation_tags_count( $preservation_widgets_output, 'aside' ) ) );
			}
			if ( $preservation_columns > 1 ) {
				$preservation_widgets_output = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $preservation_columns ) . ' widget', $preservation_widgets_output );
			} else {
				$preservation_need_columns = false;
			}
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo ! empty( $preservation_header_wide ) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<?php do_action( 'preservation_action_before_sidebar_wrap', 'header' ); ?>
			<div class="header_widgets_inner widget_area_inner">
				<?php
				if ( ! $preservation_header_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $preservation_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'preservation_action_before_sidebar', 'header' );
				preservation_show_layout( $preservation_widgets_output );
				do_action( 'preservation_action_after_sidebar', 'header' );
				if ( $preservation_need_columns ) {
					?>
					</div>	<!-- /.columns_wrap -->
					<?php
				}
				if ( ! $preservation_header_wide ) {
					?>
					</div>	<!-- /.content_wrap -->
					<?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
			<?php do_action( 'preservation_action_after_sidebar_wrap', 'header' ); ?>
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
