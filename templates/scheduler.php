<?php
/**
* Template Name: Scheduler
* @package VertyCal
*/
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <div class="vrtcl-main">
            <div class="vrtcl-before-sheduler">

            <?php 
            /*
             * Outputs list of categories
             * 
             * @arg = content only
             * @since 1.1.0
             */
            do_action( 'vertycal_before_scheduler_list' ); 
            ?>

            </div><div class="vrtclclearfix"></div>
    
            <div class="vrtcl-form_handler">

            <?php 
            if( isset( $_POST['action'] ) && !empty( $_POST['action'] ) &&  $_POST['action'] == 'vertycal_new_post_action' ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
                // Do the wp_insert_post action - nonce verified here.
                vertycal_save_front_form_post();
            }
            ?>

            </div>

        <div id="tabs">

            <?php 
            /*
             * Load tabs handle schedules
             * 
             * @arg = content only
             * @since 1.1.0
             */
            do_action( 'vertycal_before_scheduler_tabs' );
            ?>

        <div id="tab1" class="tab">
            <section id="Schedule">
            <h4><?php 
            /*
             * Title to scheduler tab
             * @uses function vertycal_get_current_user($args)
             */
            sanitize_text_field( vertycal_get_current_user( 'user_nickname' ) ); ?></h4>

            <?php require_once( VERTYCAL_PLUGIN_PATH . 'templates/scheduler-content.php' ); ?>
            
            </section>
        </div>

        <div id="tab2" class="tab">
            <section id="Formpost">
            <h5><?php /* Title New Entry */
                esc_html_e( 'Add New entry', 'vertycal' ); ?></h5>

            <?php require_once( VERTYCAL_PLUGIN_PATH . 'templates/scheduler-new.php' ); ?>

            </section>
        </div>

        <div id="tab3" class="tab">
            <section id="Voptions">
            <h5><?php /* Title to Options */
            esc_html_e( 'Additional Information', 'vertycal' ); ?></h5>

            <?php require_once( VERTYCAL_PLUGIN_PATH . 'templates/scheduler-options.php' ); ?>

            </section>
        </div>

        </div><div class="vrtclclearfix"></div>

        <footer class="entry-footer">
            
            <p><?php echo esc_html( vertycal_count_published() ); ?>
            <span class="vrtcl-foot-right"><a href="#" 
            title="<?php esc_attr_e( 'Top^', 'vertycal' ); ?>">
            <?php esc_attr_e( 'Top^', 'vertycal' ); ?></a></span></p>
            
	    </footer> 
    </div><!--vrtcl-main-->
</main><!-- .site-main -->
</div><?php 
    if ( function_exists( 'vertycal_check_for_sidebar') ) :  
        if ( vertycal_check_for_sidebar() ) {
            if (function_exists('register_sidebar')) { 
                get_sidebar(); 
            }
        }
    endif; ?>
    <?php get_footer(); ?>