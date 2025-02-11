<?php
/**
* @package VertyCal 
* @subpackage templates/category-vertycal_category.php
*/
get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<section class="vrtcl_single vertycal_category_page">
            <header class="entry-header">
                <ul class="vrtcl-inline-ul">
                <?php 
                wp_list_categories( array(
                    'taxonomy'   => 'vertycal_category',
                    'show_count' => true,
                    'hide_empty' => true
                ) ); 
                ?>
                </ul>
            </header>
<?php
    // $order_by = 'meta_value';
    $vertycal_date_time = get_post_meta(get_the_ID(),'vertycal_date_time_meta',true);
    $vertycal_just_time = get_post_meta(get_the_ID(),'vertycal_just_time_meta',true);
?>
    <h2><?php echo esc_html( get_queried_object()->name ); ?></h2>
<?php
    
    $vrtcl_tax_post_args = array(
        'post_type' => 'vertycal', // Post type
        'order_by'  => 'meta_value',
        'order'     => 'ASC',
        'tax_query' => array(       // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
            array(
                'taxonomy' => 'vertycal_category',
                'field'   => 'slug',
                'terms'  => get_queried_object()->slug,
            ),
        ),
            'meta_query' => array(    // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                array(
                    'key' => 'vertycal_date_time_meta',
                    'value' => 'meta_value_num',
                    'compare' => '!=' 
                ),
            ),
    );
    $vrtcl_tax_post_qry = new WP_Query($vrtcl_tax_post_args);

    if($vrtcl_tax_post_qry->have_posts()) :
        while($vrtcl_tax_post_qry->have_posts()) :
              $vrtcl_tax_post_qry->the_post(); ?>
     
        <article>
            <h4 class="entry-title" itemprop="headline">
            <a href="<?php the_permalink(); ?>" class="entry-title-link" 
            title="<?php the_archive_title(); ?>"><?php the_title(); ?></a></h4>

            <span class="inner-date-time">

                <?php printf( '<span class="vrtcl-daytime"><strong>%s </strong> %s</span>',
                        esc_html( $vertycal_date_time ),
                        esc_html( $vertycal_just_time ) 
                        ); ?>
            </span>
            
            <div class="vrtcl_excerpt">
                <div class="small_text">
                    <?php trim( wp_strip_all_tags( the_excerpt() ) ); ?>
                </div>
            </div>
            <hr>
        </article>

        <?php
        endwhile;
        endif; ?>

		</section>
	</main> 
</div> 

<?php  // Only get sidebar if theme has one
if ( function_exists( 'register_sidebar' ) ) { get_sidebar(); } ?>
<?php get_footer(); ?>