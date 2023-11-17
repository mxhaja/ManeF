<?php
/**
 * The Header: Logo and main menu
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js<?php
	// Class scheme_xxx need in the <html> as context for the <body>!
	echo ' scheme_' . esc_attr( preservation_get_theme_option( 'color_scheme' ) );
?>">

<head>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
	do_action( 'preservation_action_before_body' );
	?>

	<div class="<?php echo esc_attr( apply_filters( 'preservation_filter_body_wrap_class', 'body_wrap' ) ); ?>" <?php do_action('preservation_action_body_wrap_attributes'); ?>>

		<?php do_action( 'preservation_action_before_page_wrap' ); ?>

		<div class="<?php echo esc_attr( apply_filters( 'preservation_filter_page_wrap_class', 'page_wrap' ) ); ?>" <?php do_action('preservation_action_page_wrap_attributes'); ?>>

			<?php do_action( 'preservation_action_page_wrap_start' ); ?>

			<?php
			$preservation_full_post_loading = ( preservation_is_singular( 'post' ) || preservation_is_singular( 'attachment' ) ) && preservation_get_value_gp( 'action' ) == 'full_post_loading';
			$preservation_prev_post_loading = ( preservation_is_singular( 'post' ) || preservation_is_singular( 'attachment' ) ) && preservation_get_value_gp( 'action' ) == 'prev_post_loading';

			// Don't display the header elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ! $preservation_full_post_loading && ! $preservation_prev_post_loading ) {

				// Short links to fast access to the content, sidebar and footer from the keyboard
				?>
				<a class="preservation_skip_link skip_to_content_link" href="#content_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'preservation_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to content", 'preservation' ); ?></a>
				<?php if ( preservation_sidebar_present() ) { ?>
				<a class="preservation_skip_link skip_to_sidebar_link" href="#sidebar_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'preservation_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to sidebar", 'preservation' ); ?></a>
				<?php } ?>
				<a class="preservation_skip_link skip_to_footer_link" href="#footer_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'preservation_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to footer", 'preservation' ); ?></a>

				<?php
				do_action( 'preservation_action_before_header' );

				// Header
				$preservation_header_type = preservation_get_theme_option( 'header_type' );
				if ( 'custom' == $preservation_header_type && ! preservation_is_layouts_available() ) {
					$preservation_header_type = 'default';
				}
				get_template_part( apply_filters( 'preservation_filter_get_template_part', "templates/header-" . sanitize_file_name( $preservation_header_type ) ) );

				// Side menu
				if ( in_array( preservation_get_theme_option( 'menu_side' ), array( 'left', 'right' ) ) ) {
					get_template_part( apply_filters( 'preservation_filter_get_template_part', 'templates/header-navi-side' ) );
				}

				// Mobile menu
				get_template_part( apply_filters( 'preservation_filter_get_template_part', 'templates/header-navi-mobile' ) );

				do_action( 'preservation_action_after_header' );

			}
			?>

			<?php do_action( 'preservation_action_before_page_content_wrap' ); ?>

			<div class="page_content_wrap<?php
				if ( preservation_is_off( preservation_get_theme_option( 'remove_margins' ) ) ) {
					if ( empty( $preservation_header_type ) ) {
						$preservation_header_type = preservation_get_theme_option( 'header_type' );
					}
					if ( 'custom' == $preservation_header_type && preservation_is_layouts_available() ) {
						$preservation_header_id = preservation_get_custom_header_id();
						if ( $preservation_header_id > 0 ) {
							$preservation_header_meta = preservation_get_custom_layout_meta( $preservation_header_id );
							if ( ! empty( $preservation_header_meta['margin'] ) ) {
								?> page_content_wrap_custom_header_margin<?php
							}
						}
					}
					$preservation_footer_type = preservation_get_theme_option( 'footer_type' );
					if ( 'custom' == $preservation_footer_type && preservation_is_layouts_available() ) {
						$preservation_footer_id = preservation_get_custom_footer_id();
						if ( $preservation_footer_id ) {
							$preservation_footer_meta = preservation_get_custom_layout_meta( $preservation_footer_id );
							if ( ! empty( $preservation_footer_meta['margin'] ) ) {
								?> page_content_wrap_custom_footer_margin<?php
							}
						}
					}
				}
				do_action( 'preservation_action_page_content_wrap_class', $preservation_prev_post_loading );
				?>"<?php
				if ( apply_filters( 'preservation_filter_is_prev_post_loading', $preservation_prev_post_loading ) ) {
					?> data-single-style="<?php echo esc_attr( preservation_get_theme_option( 'single_style' ) ); ?>"<?php
				}
				do_action( 'preservation_action_page_content_wrap_data', $preservation_prev_post_loading );
			?>>
				<?php
				do_action( 'preservation_action_page_content_wrap', $preservation_full_post_loading || $preservation_prev_post_loading );

				// Single posts banner
				if ( apply_filters( 'preservation_filter_single_post_header', preservation_is_singular( 'post' ) || preservation_is_singular( 'attachment' ) ) ) {
					if ( $preservation_prev_post_loading ) {
						if ( preservation_get_theme_option( 'posts_navigation_scroll_which_block' ) != 'article' ) {
							do_action( 'preservation_action_between_posts' );
						}
					}
					// Single post thumbnail and title
					$preservation_path = apply_filters( 'preservation_filter_get_template_part', 'templates/single-styles/' . preservation_get_theme_option( 'single_style' ) );
					if ( preservation_get_file_dir( $preservation_path . '.php' ) != '' ) {
						get_template_part( $preservation_path );
					}
				}

				// Widgets area above page
				$preservation_body_style   = preservation_get_theme_option( 'body_style' );
				$preservation_widgets_name = preservation_get_theme_option( 'widgets_above_page' );
				$preservation_show_widgets = ! preservation_is_off( $preservation_widgets_name ) && is_active_sidebar( $preservation_widgets_name );
				if ( $preservation_show_widgets ) {
					if ( 'fullscreen' != $preservation_body_style ) {
						?>
						<div class="content_wrap">
							<?php
					}
					preservation_create_widgets_area( 'widgets_above_page' );
					if ( 'fullscreen' != $preservation_body_style ) {
						?>
						</div>
						<?php
					}
				}

				// Content area
				do_action( 'preservation_action_before_content_wrap' );
				?>
				<div class="content_wrap<?php echo 'fullscreen' == $preservation_body_style ? '_fullscreen' : ''; ?>">

					<?php do_action( 'preservation_action_content_wrap_start' ); ?>

					<div class="content">
						<?php
						do_action( 'preservation_action_page_content_start' );

						// Skip link anchor to fast access to the content from keyboard
						?>
						<a id="content_skip_link_anchor" class="preservation_skip_link_anchor" href="#"></a>
						<?php
						// Single posts banner between prev/next posts
						if ( ( preservation_is_singular( 'post' ) || preservation_is_singular( 'attachment' ) )
							&& $preservation_prev_post_loading 
							&& preservation_get_theme_option( 'posts_navigation_scroll_which_block' ) == 'article'
						) {
							do_action( 'preservation_action_between_posts' );
						}

						// Widgets area above content
						preservation_create_widgets_area( 'widgets_above_content' );

						do_action( 'preservation_action_page_content_start_text' );
