<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: //codex.wordpress.org/Template_Hierarchy
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0
 */

$preservation_template = apply_filters( 'preservation_filter_get_template_part', preservation_blog_archive_get_template() );

if ( ! empty( $preservation_template ) && 'index' != $preservation_template ) {

	get_template_part( $preservation_template );

} else {

	preservation_storage_set( 'blog_archive', true );

	get_header();

	if ( have_posts() ) {

		// Query params
		$preservation_stickies   = is_home()
								|| ( in_array( preservation_get_theme_option( 'post_type' ), array( '', 'post' ) )
									&& (int) preservation_get_theme_option( 'parent_cat' ) == 0
									)
										? get_option( 'sticky_posts' )
										: false;
		$preservation_post_type  = preservation_get_theme_option( 'post_type' );
		$preservation_args       = array(
								'blog_style'     => preservation_get_theme_option( 'blog_style' ),
								'post_type'      => $preservation_post_type,
								'taxonomy'       => preservation_get_post_type_taxonomy( $preservation_post_type ),
								'parent_cat'     => preservation_get_theme_option( 'parent_cat' ),
								'posts_per_page' => preservation_get_theme_option( 'posts_per_page' ),
								'sticky'         => preservation_get_theme_option( 'sticky_style' ) == 'columns'
															&& is_array( $preservation_stickies )
															&& count( $preservation_stickies ) > 0
															&& get_query_var( 'paged' ) < 1
								);

		preservation_blog_archive_start();

		do_action( 'preservation_action_blog_archive_start' );

		if ( is_author() ) {
			do_action( 'preservation_action_before_page_author' );
			get_template_part( apply_filters( 'preservation_filter_get_template_part', 'templates/author-page' ) );
			do_action( 'preservation_action_after_page_author' );
		}

		if ( preservation_get_theme_option( 'show_filters' ) ) {
			do_action( 'preservation_action_before_page_filters' );
			preservation_show_filters( $preservation_args );
			do_action( 'preservation_action_after_page_filters' );
		} else {
			do_action( 'preservation_action_before_page_posts' );
			preservation_show_posts( array_merge( $preservation_args, array( 'cat' => $preservation_args['parent_cat'] ) ) );
			do_action( 'preservation_action_after_page_posts' );
		}

		do_action( 'preservation_action_blog_archive_end' );

		preservation_blog_archive_end();

	} else {

		if ( is_search() ) {
			get_template_part( apply_filters( 'preservation_filter_get_template_part', 'templates/content', 'none-search' ), 'none-search' );
		} else {
			get_template_part( apply_filters( 'preservation_filter_get_template_part', 'templates/content', 'none-archive' ), 'none-archive' );
		}
	}

	get_footer();
}
