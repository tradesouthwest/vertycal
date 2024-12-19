<?php 
/**
 * @package VertyCal
 * @subpackage vertycal/inc/vertycal-helpers
 * @since 1.1.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Path helpers for urls
 * TODO Check how to use placeholders in `get_var()`
 * @since 1.0.0
 */
function vertycal_get_ID_by_slug( $page_name ) 
{

    global $wpdb, $page_name;
    $page_name_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts 
                                    WHERE post_name = '".$page_name."'");  // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        return absint( $page_name_id );
} 

function vertycal_get_page_by_slug( $slug ) 
{

    $page_url_id   = get_page_by_path( $slug );
    $page_url_link = get_permalink( $page_url_id );
        
        return esc_url( $page_url_link );
} 

function vertycal_get_parent_bycat( $post )
{
    
    global $post;
    if ( $post->post_parent )	
    {
        $ancestors = get_post_ancestors( $post->ID );
        $root     = count($ancestors)-1;
        $parent  = $ancestors[$root];
    } else {
        $parent = $post->ID;
    }
        
        return absint( $parent );
}

/**
 * @param $vertycal_options
 * @uses get_option 
 * Adds styles to footer. Text color & Single Page Margin
 */
function vertycal_inline_public_styles()
{

    $options        = get_option( 'vertycal_options' ); 
    $vrtcl_fontsize = ( empty( $options['vertycal_font_field_1'] ) ) ? 
                      '100' :  $options['vertycal_font_field_1'];
    $vertycal_entry = ( empty( $options['vertycal_text_field_2'] ) ) ? 
                       '15' :  $options['vertycal_text_field_2'];
    $color_h3       = ( empty( $options['vertycal_color_field_1'] ) ) ? 
                   '#3377dd' : $options['vertycal_color_field_1'];
    $color_bkg      = ( empty( $options['vertycal_color_field_3'] ) ) ? 
                   '#e9e9e9' : $options['vertycal_color_field_3'];

    $htm = '';
    $htm .= 'table.calendar-main td{font-size:'. esc_attr($vrtcl_fontsize) .'%;}
    .vrtcl-main {margin-bottom: '. esc_attr($vertycal_entry) .'px;}
    .inner-date-time,.inner-date-time .vrtcl-daytime > strong, .author-is, .author-single, 
    .inner-link a, .inner-link.btn_add a{color:'. sanitize_hex_color($color_h3) .';}
    .vrtcl-form-wrapper input:focus,
    .vrtcl-form-wrapper textarea:focus{border-color:'. sanitize_hex_color($color_h3) .';}
    #tab1.tab {background-color:'. sanitize_hex_color($color_bkg) .'}';

    wp_register_style( 'vertycal-entry-set', false );
    wp_enqueue_style(   'vertycal-entry-set' );
    wp_add_inline_style( 'vertycal-entry-set', $htm );
}

/**
 * Retrieve various user meta datas.
 * 
 * @param string $current_user WP _get_current_user(data)
 * @return string              of switch case
 */
function vertycal_get_current_user( $user_data )
{

    $current_user = wp_get_current_user();

    if ( !$current_user->exists() ) return;
    
        switch( $user_data ) {
            case 'user_firstname': 
            $user_data = '<p>' . esc_html( $current_user->user_firstname ) . '</p>';
            break;
            case 'user_lastname': 
            $user_data = '<p>' . esc_html( $current_user->user_lastname ) . '</p>';
            break;
            case 'user_nickname': 
            $user_data = esc_html( $current_user->display_name );
            break;
            case 'user_ID': 
            $user_data = absint( $current_user->ID );
            break;
                
            default:
            $user_data = '';
            break;
        }
        echo esc_attr( $user_data );
} 

/**
 * Count cpts - shows at bottom of scheduler table
 * 
 * @return Absint
 */
function vertycal_count_published()
{

    $count_posts = wp_count_posts( 'vertycal' )->publish;

    ob_start();
    echo esc_html__( 'Published: ', 'vertycal' ) . absint( $count_posts );
    $counts = ob_get_clean();

        return $counts;
}

/**
 * Get metadata `vertycal_mark_done_meta`
 */
function vertycal_the_item_status( $post_id )
{

	$post_id         = get_the_ID();
    $vertycal_status = ( empty( get_post_meta( $post_id, 'vertycal_mark_done_meta', true) 
               ) ) ? 'opened' : get_post_meta( $post_id, 'vertycal_mark_done_meta', true);
    
        return sanitize_text_field( $vertycal_status );
}
/**
 * Get metadat Address/Location
 */
function vertycal_get_address( $post_id )
{

	$post_id           = get_the_ID();
    $vertycal_location = ( empty( get_post_meta( $post_id, 'vertycal_location_meta', true)
                       ) ) ? '' : get_post_meta( $post_id, 'vertycal_location_meta', true);

		return sanitize_text_field( $vertycal_location );
}
/**
 * Get metadata Telephone
 */
function vertycal_get_telephone( $post_id )
{

	$post_id            = get_the_ID();
    $vertycal_telephone = ( empty( get_post_meta( $post_id, 'vertycal_telephone_meta', true)
                        ) ) ? '' : get_post_meta( $post_id, 'vertycal_telephone_meta', true);

		return sanitize_text_field( $vertycal_telephone );
}
/**
 * get state of vertycal_checkbox_markdone
 *
 * @return 1/0 (Bool)
 */
function vertycal_state_checkbox_markdone()
{
	$optionx = get_option('vertycal_options')['vertycal_checkbox_markdone'];
	$state   = ( empty( $optionx ) ) ? 0 : $optionx;
		if( $state == '1')
			return true;
} 

/**
 * get state of vertycal_checkbox_showyear
 *
 * @return 1/0 (Bool)
 */
function vertycal_state_checkbox_showyear()
{
$optionx = '';
	$optionx = 1; //get_option('vertycal_options')['vertycal_checkbox_showyear'];
	$state   = ( empty( $optionx ) ) ? 0 : $optionx;
		if( $state == '1')
            
            return true;
} 
