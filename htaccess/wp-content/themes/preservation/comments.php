<?php
/**
 * The template to display the Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

if ( ! function_exists( 'preservation_output_single_comment' ) ) {
	/**
	 * Callback for output a single comment layout in the list of comment
	 * depends of the comment type.
	 *
	 * @param object  $comment  A current comment object.
	 * @param array   $args     Arguments to display layout.
	 * @param int     $depth    A current depth of the comment.
	 */
	function preservation_output_single_comment( $comment, $args, $depth ) {
		switch ( $comment->comment_type ) {
			case 'pingback':
				?>
				<li class="trackback"><?php esc_html_e( 'Trackback:', 'preservation' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'preservation' ), '<span class="edit-link">', '<span>' ); ?>
				<?php
				break;
			case 'trackback':
				?>
				<li class="pingback"><?php esc_html_e( 'Pingback:', 'preservation' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'preservation' ), '<span class="edit-link">', '<span>' ); ?>
				<?php
				break;
			default:
				$author_id   = $comment->user_id;
				$author_link = ! empty( $author_id ) ? get_author_posts_url( $author_id ) : '';
				$author_post = get_the_author_meta( 'ID' ) == $author_id;
				$mult        = preservation_get_retina_multiplier();
				$comment_id  = get_comment_ID();
				?>
				<li id="comment-<?php echo esc_attr( $comment_id ); ?>" <?php comment_class( 'comment_item' ); ?>>
					<div id="comment_body-<?php echo esc_attr( $comment_id ); ?>" class="comment_body">
						<div class="comment_author_avatar"><?php echo get_avatar( $comment, 90 * $mult ); ?></div>
						<div class="comment_content">
							<div class="comment_info">
								<?php if ( $author_post ) { ?>
									<div class="comment_bypostauthor">
										<?php
										esc_html_e( 'Post Author', 'preservation' );
										?>
									</div>
								<?php } ?>
								<h6 class="comment_author">
								<?php
									echo ( ! empty( $author_link ) ? '<a href="' . esc_url( $author_link ) . '">' : '' )
											. esc_html( get_comment_author() )
											. ( ! empty( $author_link ) ? '</a>' : '' );
								?>
								</h6>
								<div class="comment_posted">
									<span class="comment_posted_label"><?php esc_html_e( 'Posted', 'preservation' ); ?></span>
									<span class="comment_date">
									<?php
										echo esc_html( get_comment_date( get_option( 'date_format' ) ) );
									?>
									</span>
									<span class="comment_time_label"><?php esc_html_e( 'at', 'preservation' ); ?></span>
									<span class="comment_time">
									<?php
										echo esc_html( get_comment_date( get_option( 'time_format' ) ) );
									?>
									</span>
								</div>
								<?php
								// Show rating in the comment
								do_action( 'trx_addons_action_post_rating', 'c' . esc_attr( $comment_id ) );
								?>
							</div>
							<div class="comment_text_wrap">
								<?php if ( 0 === $comment->comment_approved ) { ?>
								<div class="comment_not_approved"><?php esc_html_e( 'Your comment is awaiting moderation.', 'preservation' ); ?></div>
								<?php } ?>
								<div class="comment_text"><?php comment_text(); ?></div>
							</div>
							<div class="comment_footer">
								<?php
								if ( 1 == $comment->comment_approved && preservation_exists_trx_addons() ) {
									?>
									<span class="comment_counters"><?php preservation_show_comment_counters('likes,rating'); ?></span>
									<?php
								}
								$args['max_depth'] = apply_filters( 'preservation_filter_comment_depth', $args['max_depth'] );
								if ( $depth < $args['max_depth'] ) {
									?>
									<span class="reply comment_reply">
										<?php
										comment_reply_link(
											array_merge(
												$args, array(
													'add_below' => 'comment_body',
													'depth' => $depth,
													'max_depth' => $args['max_depth'],
												)
											)
										);
										?>
									</span>
									<?php
								}
								?>
							</div>
						</div>
					</div>
				<?php
				break;
		}
	}
}


// Display a list of comments
if ( ! preservation_sc_layouts_showed( 'comments' ) && ( have_comments() || comments_open() ) ) {
	
	preservation_sc_layouts_showed( 'comments', true );

	$preservation_full_post_loading = preservation_get_value_gp( 'action' ) == 'full_post_loading';
	$preservation_posts_navigation  = preservation_get_theme_option( 'posts_navigation' );
	$preservation_comments_number   = get_comments_number();
	$preservation_show_comments     = preservation_get_value_gp( 'show_comments' ) == 1
									|| ( ! $preservation_full_post_loading
											&&
											( 'scroll' != $preservation_posts_navigation
												|| preservation_get_theme_option( 'posts_navigation_scroll_hide_comments' ) == 0
												|| preservation_check_url( '#comments' )
											)
										);
	$preservation_show_button       = ! $preservation_show_comments || preservation_get_theme_option( 'show_comments_button' ) == 1;
	$preservation_open_comments     = preservation_get_value_gp( 'show_comments' ) == 1
									|| ! $preservation_show_button
									|| preservation_get_theme_option( 'show_comments' ) == 'visible'
									|| preservation_check_url( '#comments' );

	$preservation_msg_show          = $preservation_comments_number > 0
									? wp_kses_data( sprintf( _n( 'Show comment', 'Show comments (%d)', $preservation_comments_number, 'preservation' ), $preservation_comments_number ) )
									: wp_kses_data( __( 'Leave a comment', 'preservation' ) );
	$preservation_msg_hide          = wp_kses_data( __( 'Hide comments', 'preservation' ) );

	do_action( 'preservation_action_before_comments' );

	if ( $preservation_show_button ) {
		?>
		<div class="show_comments_single">
			<a href="<?php
				if ( $preservation_show_comments ) {
					echo '#';
				} else {
					echo esc_url( add_query_arg( array( 'show_comments' => 1 ), get_comments_link() ) );
				}
			?>"
			class="<?php
				echo apply_filters( 'preservation_filter_comments_button_class', 'show_comments_button' );
				if ( $preservation_show_comments && $preservation_open_comments ) {
					echo ' opened';
				}
			?>"
			data-show="<?php echo esc_attr( $preservation_msg_show ); ?>"
			data-hide="<?php echo esc_attr( $preservation_msg_hide ); ?>"
			>
				<?php
				if ( $preservation_show_comments && $preservation_open_comments ) {
					echo esc_html( $preservation_msg_hide );
				} else {
					echo esc_html( $preservation_msg_show );
				}
				?>
			</a>
		</div>
		<?php
	}

	if ( $preservation_show_comments ) {
		?>
		<section class="comments_wrap<?php if ( $preservation_open_comments ) { echo ' opened'; } ?>">
			<?php
			if ( have_comments() ) {
				?>
				<div id="comments" class="comments_list_wrap">
					<h3 class="section_title comments_list_title">
					<?php
					$preservation_post_comments = get_comments_number();
					echo esc_html( $preservation_post_comments );
					?>
				<?php echo esc_html( _n( 'Comment', 'Comments', $preservation_post_comments, 'preservation' ) ); ?></h3>
					<ul class="comments_list">
						<?php
						wp_list_comments( array( 'callback' => 'preservation_output_single_comment' ) );
						?>
					</ul>
						<?php
						if ( ! comments_open() && get_comments_number() != 0 && post_type_supports( get_post_type(), 'comments' ) ) {
							?>
						<p class="comments_closed"><?php esc_html_e( 'Comments are closed.', 'preservation' ); ?></p>
							<?php
						}
						if ( get_comment_pages_count() > 1 ) {
							?>
						<div class="comments_pagination"><?php paginate_comments_links(); ?></div>
							<?php
						}
						?>
				</div>
					<?php
			}

			if ( comments_open() ) {
				?>
				<div class="comments_form_wrap">
					<div class="comments_form">
					<?php
					$preservation_form_style = esc_attr( preservation_get_theme_option( 'input_hover' ) );
					if ( empty( $preservation_form_style ) || preservation_is_inherit( $preservation_form_style ) ) {
						$preservation_form_style = 'default';
					}
					$preservation_commenter     = wp_get_current_commenter();
					$preservation_req           = get_option( 'require_name_email' );
					$preservation_comments_args = apply_filters(
						'preservation_filter_comment_form_args', array(
							// class of the 'form' tag
							'class_form'           => 'comment-form ' . ( 'default' != $preservation_form_style ? 'sc_input_hover_' . esc_attr( $preservation_form_style ) : '' ),
							// change the id of send button
							'id_submit'            => 'send_comment',
							// change the title of send button
							'label_submit'         => esc_html__( 'Leave a comment', 'preservation' ),
							// change the title of the reply section
							'title_reply'          => esc_html__( 'Leave a comment', 'preservation' ),
							'title_reply_before'   => '<h3 id="reply-title" class="section_title comments_form_title comment-reply-title">',
							'title_reply_after'    => '</h3>',
							// remove "Logged in as"
							'logged_in_as'         => '',
							// remove text before textarea
							'comment_notes_before' => '',
							// remove text after textarea
							'comment_notes_after'  => '',
							'fields'               => array(
								'author' => preservation_single_comments_field(
									array(
										'form_style'        => $preservation_form_style,
										'field_type'        => 'text',
										'field_req'         => $preservation_req,
										'field_icon'        => 'icon-user',
										'field_value'       => isset( $preservation_commenter['comment_author'] ) ? $preservation_commenter['comment_author'] : '',
										'field_name'        => 'author',
										'field_title'       => esc_html__( 'Name', 'preservation' ),
										'field_placeholder' => esc_attr__( 'Your Name', 'preservation' ),
									)
								),
								'email'  => preservation_single_comments_field(
									array(
										'form_style'        => $preservation_form_style,
										'field_type'        => 'text',
										'field_req'         => $preservation_req,
										'field_icon'        => 'icon-mail',
										'field_value'       => isset( $preservation_commenter['comment_author_email'] ) ? $preservation_commenter['comment_author_email'] : '',
										'field_name'        => 'email',
										'field_title'       => esc_html__( 'E-mail', 'preservation' ),
										'field_placeholder' => esc_attr__( 'Your E-mail', 'preservation' ),
									)
								),
							),
							// redefine your own textarea (the comment body)
							'comment_field'        => preservation_single_comments_field(
								array(
									'form_style'        => $preservation_form_style,
									'field_type'        => 'textarea',
									'field_req'         => true,
									'field_icon'        => 'icon-feather',
									'field_value'       => '',
									'field_name'        => 'comment',
									'field_title'       => esc_html__( 'Comment', 'preservation' ),
									'field_placeholder' => esc_attr__( 'Your comment', 'preservation' ),
								)
							),
						)
					);
					comment_form( $preservation_comments_args );
					?>
					</div>
				</div>
				<?php
			}
			?>
		</section>
		<?php
		do_action( 'preservation_action_after_comments' );
	}
}
