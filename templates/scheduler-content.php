<?php
/**
 * The template part for displaying VertyCal scheduled content
 *
 * @package VertyCal
 * @subpackage templates/scheduler-content
 * @since 1.1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <table id="Vrtcl" class="calendar-main" role="listbox">

    <?php /* Loop is customized inside file "vertycal-loop-table.php"
        */ 
        include_once ( plugin_dir_path( __FILE__ ) . 'vertycal-loop-table.php' ); 
    ?>

    <?php $wp_query = new WP_Query($args); ?>
    
    <?php /* Only run query if we have post_type= vertycal
        */ 
    if ( $wp_query->have_posts() ) : 
    ?>

    <thead>
    <tr><th class="scheduled-item-prime"><small>
            <?php esc_html_e( 'Date / Time', 'vertycal' ); ?></small></th>
        <th class="scheduled-item-first"><small>
            <?php print( vertycal_display_thead() ); ?></small></th>
        <th class="scheduled-item-last"><small>
            <?php esc_html_e( 'View / Who / Status', 'vertycal' ); ?></small></th>
    </tr>
    </thead>
    <tbody>
        
        <?php
        while ( $wp_query->have_posts() ) : $wp_query->the_post();

            $post_id  = get_the_ID();

        $vertycal_date_time = get_post_meta(get_the_ID(),'vertycal_date_time_meta',true);
        $vertycal_date_time = substr( $vertycal_date_time, $digitz );
        $vertycal_just_time = get_post_meta(get_the_ID(),'vertycal_just_time_meta',true);
                ?>

            <tr class="scheduled-item">
                <td><span class="inner-date-time">
                    <?php printf( '<span class="vrtcl-daytime"><strong>%s </strong>
                    <span class="vrtcl-tm"> %s</span></span>',
                        esc_html( $vertycal_date_time ),
                        esc_html( $vertycal_just_time ) 
                    ); ?>
                </span>
                </td>
                <td class="cell-inner-excerpt">
                <a href ="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <div class="vrtcl-inner">
                    <span class="inner-title"> <?php the_title(); ?></span></a>
 
                    <div class="vrtcl_excerpt">
                        <a class="xoox" href="#popup<?php print($post_id) ?>">
                <span class="disc"></span><?php trim( wp_strip_all_tags( the_excerpt() ) ); ?></a>
                    </div>

                    <div id="popup<?php print($post_id) ?>" class="overlay">
	                    <div class="popup">
                            <span class="vrtclcontent">
                            
                                <?php trim( wp_strip_all_tags( the_excerpt() ) ); ?>

                            <a class="close" href="">&times;</a></span>
                        </div>
                    </div>

                </div>
                </td>
                <td>
        <div class="inner-link">
            <a href="<?php the_permalink(); ?>" 
               title="<?php the_title(); ?>"><?php echo esc_html( $vertycal_just_time ); ?></a>
            <p class="author-is"><span><?php print(vertycal_return_author_initials()); ?> </span>
          <span>|</span> <b><?php echo esc_attr( vertycal_the_item_status(get_the_ID() ) ); ?></b></p> 
        </div>
                </td>
            </tr>

        <?php endwhile; ?>

            <tr id="footMenu">
                <td></td>
                <td><a href="#" title="top"><?php esc_html_e( 'Top^', 'vertycal' ); ?></a></td>
                <td><?php //esc_html_e( 'View', 'vertycal' ); ?></td>    
            </tr>
            </tbody></table>

            <table id="Paginate" class="calendar-main"><tbody>
                <tr>
                    <td class="td-pagination">

                        <?php echo vertycal_pagination_schedule(); ?>
                        
                    </td>
                </tr>
            </tbody></table>  
            <div class="vtrclclearfix"></div>

            <?php 
            wp_reset_postdata();
            $post_type = null; 
            ?>

        <?php else: ?>

            <tr><td colspan="2">
            <h3><?php esc_html_e( 'No entries yet. Visit the form page to enter a new entry.', 
                'vertycal' ); ?></h3>
            </td>
            </tr>
            <tr id="footMenu"><td><a href="#" title="top">Top^</a></td>
                <td><?php //esc_html_e( 'View', 'vertycal' ); ?></td></tr>
            </tbody></table>

    <?php endif; ?>

</article> 