<?php
/**
 * Comments template
 * @since 1.1.1
 */
if ( comments_open() && get_comments_number() ) : 
if (post_password_required()) {  // and it doesn't match the cookie
		?>
		<p class="nocomments">
		<?php esc_html_e( 'This post is password protected. Enter the password to view comments.', 
			'vertycal' ); ?></p>
		<?php
} ?>
<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		
		<h2 class="comments-title"><?php get_the_title() ?><h2>
		<ol class="comment-list">
			<?php 
			global $post;
			$commentz = get_comments(array('post_id' => intval($post->ID()), 
											'status' => 'approve'));
				wp_list_comments(
					array(
						'style'       => 'ol',
						'short_ping'  => true
					), $commentz
				);
			?>
		</ol>
		<nav class="vrtcl-pagination">
		<?php the_comments_navigation(); ?>
		</nav>
	<?php endif; ?>
	
	<?php
		comment_form(
			array(
				'title_reply'        => __( 'Add Notes', 'vertycal' ),
				'title_reply_to'     => __( 'Add Notes', 'vertycal' ),
				'title_reply_before' => '<h4 id="reply-title" class="comment-reply-title">',
				'title_reply_after'  => '</h4>',
				'label_submit'       => __( 'Add Notation', 'vertycal' ),
			)
		);
	?>

</div><?php
endif; 