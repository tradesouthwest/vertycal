<?php
/**
 * @package vertycal
 * @subpackage admin/vertycal-metaboxes
 * 
 * @since 1.0.0
 */ 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Add meta box to editor
 *
 * @strings $id, $title, $callback, $screen, $context, $priority, $args
 * function's action_added in register cpt
 */
function vertycal_date_time_meta_box()
{
    add_meta_box(
        'vertycal_date_time_meta',
        __( 'Pick Date Time', 'vertycal' ),
        'vertycal_date_time_meta_box_cb', //callback
        'vertycal',                      // post type screen
        'side',
        'high'
    );
    add_meta_box(
        'vertycal_just_time_meta',
        __( 'Pick Time', 'vertycal' ),
        'vertycal_just_time_meta_box_cb', //callback
        'vertycal',                      // post type screen
        'side',
        'high'
    );
    add_meta_box(
        'vertycal_mark_done_meta',
        __( 'Mark As', 'vertycal' ),
        'vertycal_mark_done_meta_box_cb', //callback
        'vertycal',                      // post type screen
        'side',
        'high'
    );
    add_meta_box(
        'vertycal_location_meta',
        __( 'Location Address', 'vertycal' ),
        'vertycal_location_meta_box_cb', //callback
        'vertycal',                      // post type screen
        'normal',
        'high'
    );
    add_meta_box(
        'vertycal_telephone_meta',
        __( 'Telephone Dial', 'vertycal' ),
        'vertycal_telephone_meta_box_cb', //callback
        'vertycal',                      // post type screen
        'normal',
        'high'
    );
}
/**
 * Output the HTML for the metabox.
 *
 * @since 1.0.0
 *
 * @param string $vertycal_date_time  Metadata for vertycal post
 * @param string $vertycal_just_time  Metadata for vertycal post
 */
function vertycal_date_time_meta_box_cb($post)
{

    global $post;
    $vertycal_date_time = '';

    // Output the field
	$vertycal_date_time = get_post_meta( $post->ID, 'vertycal_date_time_meta', true );
    $html = '';
    $html .= '<input type="date" id="VrtclDateTime" name="vertycal_date_time_meta"
    value="' . esc_attr( $vertycal_date_time ) . '" class="text_field">';

    $html .= wp_nonce_field( 'vertycal_date_time', 'vertycal_date_time' );
    echo $html;                       // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
/**
 * Output the HTML for the metabox.
 */
function vertycal_just_time_meta_box_cb($post)
{

    global $post;
    $vertycal_just_time = '';

    // Output the field
	$vertycal_just_time = get_post_meta( $post->ID, 'vertycal_just_time_meta', true );
    $html = '';
    $html .= '<input type="time" id="VrtclJustTime" name="vertycal_just_time_meta"
    value="' . esc_attr( $vertycal_just_time ) . '" class="text_field">';

    $html .= wp_nonce_field( 'vertycal_just_time', 'vertycal_just_time' );
    echo $html;                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
/**
 * Output the HTML for the metabox.
 */
function vertycal_location_meta_box_cb($post)
{

    global $post;
    $vertycal_location = '';

    // Output the field
    $vertycal_location = get_post_meta( $post->ID, 'vertycal_location_meta', true );
    $plc = __( 'Optional', 'vertycal' );
    $html = '';
    $html .= '<input id="VrtclLocation" class="text_field location" type="text"
     name="vertycal_location_meta" value="' . esc_attr( $vertycal_location ) . '"
     placeholder="' . esc_attr( $plc ) . '">';

    $html .= wp_nonce_field( 'vertycal_location', 'vertycal_location' );
    echo $html;                             // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
/**
 * Output the HTML for the metabox.
 */
function vertycal_telephone_meta_box_cb($post)
{

    global $post;
    $vertycal_telephone = '';

    // Output the field
    $vertycal_telephone = get_post_meta( $post->ID, 'vertycal_telephone_meta', true );
    $plc = __( 'Optional', 'vertycal' );
    $html = '';
    $html .= '<input id="VrtclTelephone" class="text_field location" type="text"
     name="vertycal_telephone_meta" value="' . esc_attr( $vertycal_telephone ) . '"
     placeholder="' . esc_attr( $plc ) . '">';

    $html .= wp_nonce_field( 'vertycal_telephone', 'vertycal_telephone' );
    echo $html;                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
/**
 * Output the HTML for the metabox.
 */
function vertycal_mark_done_meta_box_cb($post)
{

    global $post;
    //maybe options
    $opta = __( 'Scheduled', 'vertycal' );
    $optb = __( 'Pending',   'vertycal' );
    $optc = __( 'On Board',  'vertycal' );
    $optd = __( 'Fulfilled', 'vertycal' );

    $vertycal_mark_done = '';
    $vertycal_mark_done = get_post_meta( $post->ID, 'vertycal_mark_done_meta', true );
    
    ob_start();
    $vtcl_option = array(
        'scheduled' => $opta,
        'pending'   => $optb,
        'progress'  => $optc,
        'complete'  => $optd
    );
        // Output the field
        printf( '<select id="%1$s" name="%1$s">', 'vertycal_mark_done_meta' );

        foreach( $vtcl_option as $key => $value )
        {
echo '<option value="' . esc_attr( $key ) . '"' . selected( $vertycal_mark_done, $key ) . '>' . esc_html( $value ) . '</option>';
        }
        print( '</select>' );
        wp_nonce_field( 'vertycal_mark_done', 'vertycal_mark_done' );

    $output = ob_get_clean();
    echo $output;           // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
/**
 * Save meta box content.
 * https://metabox.io/how-to-create-custom-meta-boxes-custom-fields-in-wordpress/
 * @param int $post_id Post ID
 */
function vertycal_update_date_time_meta( $post_id )
{
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }
    $fields = [
        'vertycal_date_time_meta',
        'vertycal_just_time_meta',
        'vertycal_mark_done_meta',
        'vertycal_location_meta',
        'vertycal_telephone_meta'
    ];
    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {                 // phpcs:ignore WordPress.Security.NonceVerification.Missing
            update_post_meta( $post_id, 
                                $field,                     // phpcs:ignore WordPress.Security.NonceVerification.Missing
                                wp_unslash( sanitize_key( $_POST[$field] ) ) 
            );
        }
     }
}
/**
 * Attachment Display Settings
 * Removed `<i> &#x25BE; </i>` form last $htm line before </li
 * Row is not collapsable due to custom notation
 */ 
function vertycal_admin_inline_customize( $pagenow )
{
    global $post, $pagenow;
    if( 'post.php' == $pagenow && ( get_post_type( get_the_ID() ) == 'vertycal' ) ) 
    {

    $notes  = '';
    $notes .= '<ul><li>' . esc_attr__( 'Working notes are private and will not display on &#39;Scheduler&#39; but will show on &#39;Scheduled&#39; full page. ', 'vertycal' ) . ' <em>' . esc_html__( 'view Help^ above for more notes', 'vertycal' ) . '</em></li></ul>';

    if ( current_user_can( 'manage_options' ) || current_user_can( 'edit_posts' ) ) 
    {
    $htm  = '<div class="vrtcl-editor_text"><span>'. $notes .'</span>';
    $htm .= '<span class="screen-reader-text">'. esc_attr__( 'No Toggle No sort Working Notes', 'vertycal' ) .'</span>';
    $htm .= '<span class="normal-sortables not-sortable"><h4 class="hndle ui-sortable-handle">' . esc_html__( 'Working Notes', 'vertycal' ) . '</h4></span></div>';
    }

    echo $htm; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
    return false;
}
