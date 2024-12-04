<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package vertycal
 * @subpackage vertycal/templates/single-vertycal
 * @since 1.0.0
 */
get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<section class="vrtcl_single">

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();
			//$postid = get_the_ID();

	    include VERTYCAL_PLUGIN_PATH . 'templates/single-vertycal_content.php'; ?>
		
		<br>
		<div class="vrtcl-comments">
			<?php
			// If comments are open or at least one comment.
			if ( comments_open() || get_comments_number() ) {

				include VERTYCAL_PLUGIN_PATH . 'templates/comments.php';
			} ?>
		</div>
		<div class="footer-nav">
		<?php
		the_post_navigation(
			array(
				'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'vertycal' ) . '</span> ' .
					'<span class="screen-reader-text">' . __( 'Next post:', 'vertycal' ) . '</span> ' .
					'<span class="post-title">%title</span>',
				'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'vertycal' ) . '</span> ' .
					'<span class="screen-reader-text">' . __( 'Previous post:', 'vertycal' ) . '</span> ' .
					'<span class="post-title">%title</span>',
			)
		); ?>
		</div>
		<?php endwhile; ?>
		<?php
		wp_link_pages(
			array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'vertycal' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'vertycal' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			)
		);
		?>
		
		</section>
	</main><!-- .site-main -->

    <?php //for twenty-sixteen theme
    if ( is_active_sidebar( 'content-bottom' ) ) :
        get_sidebar( 'content-bottom' );
    endif;
    ?>

</div><!-- .content-area -->

<?php
if ( function_exists( 'register_sidebar' ) ) {
	get_sidebar();
} ?>
<?php get_footer(); ?>