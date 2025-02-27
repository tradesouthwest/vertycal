<?php
/**
 * Start cpt loop where cpt = vertycal
 * @subpackage vertycal/templates/vertycal-loop-table
 * 
 * @since 1.0.0 Loads in templates/scheduler-content.php
 * @param $_date_time string metadata or cpt
 * @param $arg string Custom post type query arguments to loop
 *
 */
$showyear = ( true === vertycal_state_checkbox_showyear() ) ? 5 : 0;
$digitz = absint( $showyear ); // strips year from date
$pageds  = 1;
if ( get_query_var('paged') ) $pageds = get_query_var('paged');
if ( get_query_var('page') ) $pageds = get_query_var('page');

//build arguments for query
$args   = array(
    'post_type'     => 'vertycal',
    'post_status'  => 'publish',
    'paged'       => esc_attr( $pageds),
    'orderby'    => 'meta_value',
    'meta_key'  => 'vertycal_date_time_meta',   // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
); 
?>
