<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 * @package PRESERVATION
 * @since PRESERVATION 1.0
 */

$preservation_template_args = get_query_var( 'preservation_template_args' );
$preservation_columns = 1;
if ( is_array( $preservation_template_args ) ) {
	$preservation_columns    = empty( $preservation_template_args['columns'] ) ? 1 : max( 1, $preservation_template_args['columns'] );
	$preservation_blog_style = array( $preservation_template_args['type'], $preservation_columns );
	if ( ! empty( $preservation_template_args['slider'] ) ) {
		?><div class="slider-slide swiper-slide">
		<?php
	} elseif ( $preservation_columns > 1 ) {
	    $preservation_columns_class = preservation_get_column_class( 1, $preservation_columns, ! empty( $preservation_template_args['columns_tablet']) ? $preservation_template_args['columns_tablet'] : '', ! empty($preservation_template_args['columns_mobile']) ? $preservation_template_args['columns_mobile'] : '' );
		?>
		<div class="<?php echo esc_attr( $preservation_columns_class ); ?>">
		<?php
	}
}
$preservation_expanded    = ! preservation_sidebar_present() && preservation_get_theme_option( 'expand_content' ) == 'expand';
$preservation_post_format = get_post_format();
$preservation_post_format = empty( $preservation_post_format ) ? 'standard' : str_replace( 'post-format-', '', $preservation_post_format );
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class( 'post_item post_item_container post_layout_excerpt post_format_' . esc_attr( $preservation_post_format ) );
	preservation_add_blog_animation( $preservation_template_args );
	?>
>
	<?php

	// Sticky label
	if ( is_sticky() && ! is_paged() ) {
		?>
		<span class="post_label label_sticky"></span>
		<?php
	}

	// Featured image
	$preservation_hover      = ! empty( $preservation_template_args['hover'] ) && ! preservation_is_inherit( $preservation_template_args['hover'] )
							? $preservation_template_args['hover']
							: preservation_get_theme_option( 'image_hover' );
	$preservation_components = ! empty( $preservation_template_args['meta_parts'] )
							? ( is_array( $preservation_template_args['meta_parts'] )
								? $preservation_template_args['meta_parts']
								: array_map( 'trim', explode( ',', $preservation_template_args['meta_parts'] ) )
								)
							: preservation_array_get_keys_by_value( preservation_get_theme_option( 'meta_parts' ) );
	preservation_show_post_featured( apply_filters( 'preservation_filter_args_featured',
		array(
			'no_links'   => ! empty( $preservation_template_args['no_links'] ),
			'hover'      => $preservation_hover,
			'meta_parts' => $preservation_components,
			'thumb_size' => ! empty( $preservation_template_args['thumb_size'] )
							? $preservation_template_args['thumb_size']
							: preservation_get_thumb_size( strpos( preservation_get_theme_option( 'body_style' ), 'full' ) !== false
								? 'full'
								: ( $preservation_expanded 
									? 'huge' 
									: 'big' 
									)
								),
		),
		'content-excerpt',
		$preservation_template_args
	) );

	// Title and post meta
	$preservation_show_title = get_the_title() != '';
	$preservation_show_meta  = count( $preservation_components ) > 0 && ! in_array( $preservation_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $preservation_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			if ( apply_filters( 'preservation_filter_show_blog_title', true, 'excerpt' ) ) {
				do_action( 'preservation_action_before_post_title' );
				if ( empty( $preservation_template_args['no_links'] ) ) {
					the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
				} else {
					the_title( '<h3 class="post_title entry-title">', '</h3>' );
				}
				do_action( 'preservation_action_after_post_title' );
			}
			?>
		</div><!-- .post_header -->
		<?php
	}

	// Post content
	if ( apply_filters( 'preservation_filter_show_blog_excerpt', empty( $preservation_template_args['hide_excerpt'] ) && preservation_get_theme_option( 'excerpt_length' ) > 0, 'excerpt' ) ) {
		?>
		<div class="post_content entry-content">
			<?php

			// Post meta
			if ( apply_filters( 'preservation_filter_show_blog_meta', $preservation_show_meta, $preservation_components, 'excerpt' ) ) {
				if ( count( $preservation_components ) > 0 ) {
					do_action( 'preservation_action_before_post_meta' );
					preservation_show_post_meta(
						apply_filters(
							'preservation_filter_post_meta_args', array(
								'components' => join( ',', $preservation_components ),
								'seo'        => false,
								'echo'       => true,
							), 'excerpt', 1
						)
					);
					do_action( 'preservation_action_after_post_meta' );
				}
			}

			if ( preservation_get_theme_option( 'blog_content' ) == 'fullpost' ) {
				// Post content area
				?>
				<div class="post_content_inner">
					<?php
					do_action( 'preservation_action_before_full_post_content' );
					the_content( '' );
					do_action( 'preservation_action_after_full_post_content' );
					?>
				</div>
				<?php
				// Inner pages
				wp_link_pages(
					array(
						'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'preservation' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'preservation' ) . ' </span>%',
						'separator'   => '<span class="screen-reader-text">, </span>',
					)
				);
			} else {
				// Post content area
				preservation_show_post_content( $preservation_template_args, '<div class="post_content_inner">', '</div>' );
			}

			// More button
			if ( apply_filters( 'preservation_filter_show_blog_readmore',  ! isset( $preservation_template_args['more_button'] ) || ! empty( $preservation_template_args['more_button'] ), 'excerpt' ) ) {
				if ( empty( $preservation_template_args['no_links'] ) ) {
					do_action( 'preservation_action_before_post_readmore' );
					if ( preservation_get_theme_option( 'blog_content' ) != 'fullpost' ) {
						preservation_show_post_more_link( $preservation_template_args, '<p>', '</p>' );
					} else {
						preservation_show_post_comments_link( $preservation_template_args, '<p>', '</p>' );
					}
					do_action( 'preservation_action_after_post_readmore' );
				}
			}

			?>
		</div><!-- .entry-content -->
		<?php
	}
	?>
</article>
<?php

if ( is_array( $preservation_template_args ) ) {
	if ( ! empty( $preservation_template_args['slider'] ) || $preservation_columns > 1 ) {
		?>
		</div>
		<?php
	}
}
