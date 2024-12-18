<?php 
/**
 * @package vertycal/admin/vertycal-manager
 * 
 * @since 1.0.0
 * @param string $user_id and $meta_caps
 * @uses Conditional include is done form Plugin option. 
 *       @see admin/vertycal-settings-page ~ vertycal_users_caps 
 *       This is why all hooks and filters are in this file.
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * This does not actually compare whether the user ID has the actual capability, 
 * just what the  capability or capabilities are. The primitive capabilities 
 * are edit_posts, edit_published_posts, edit_others_posts and edit_private_posts: 
 * you can assign these to user roles. map_meta_cap() checks the author and status 
 * of the post and returns the correct set of primitive capabilities 
 *
 */
add_filter( 'map_meta_cap', 'vertycal_map_meta_cap', 10, 4 ); 
function vertycal_map_meta_cap($caps, $cap, $user_id, $args)
{

    if ( 'edit_vertycal' == $cap || 'delete_vertycal' == $cap 
                                 || 'read_vertycal' == $cap ) 
    {
        $post = get_post( 'vertycal' );
        $post_type = get_post_type_object( $post->post_type );
        $caps = array();
    }

    if ( 'edit_vertycal' == $cap ) 
    {
        if ( $user_id == $post->post_author )
            $caps[] = $post_type->cap->edit_post;
        else
            $caps[] = $post_type->cap->edit_others_post;
    }
    elseif ( 'delete_vertycal' == $cap ) 
    {
        if ( $user_id == $post->post_author )
            $caps[] = $post_type->cap->delete_post;
        else
            $caps[] = $post_type->cap->delete_others_post;
    }
    elseif ( 'read_vertycal' == $cap ) 
    {
        if ( 'private' != $post->post_status )
            $caps[] = 'read';
        elseif ( $user_id == $post->post_author )
            $caps[] = 'read';
        else
            $caps[] = $post_type->cap->read_private_posts;
    }

    return $caps;
}

/**
 * User Manages their Media Only
 * @WP_User to validate
 */
//add_filter( 'ajax_query_attachments_args','vertycal_filter_users_own_attachments', 10, 2 );       
add_action('pre_get_posts','vertycal_filter_users_own_attachments');
function vertycal_filter_users_own_attachments( $wp_query_obj ) 
{

    global $current_user, $pagenow;

    $is_attachment_request = ($wp_query_obj->get('post_type')=='attachment');

    if( !$is_attachment_request )
        return;

    if( !is_a( $current_user, 'WP_User') )
        return;

    if( !in_array( $pagenow, array( 'upload.php', 'admin-ajax.php' ) ) )
        return;

    if( !current_user_can('delete_pages') )
        $wp_query_obj->set('author', $current_user->ID );

    //return;
}
	
/**
 * sets only user posts as available to edit in admin
 * 
 * @param string $user_level Check global is set >= Editor
 * @since 1.0.0
 */
add_filter('pre_get_posts', 'vertycal_get_posts_for_current_author');
function vertycal_get_posts_for_current_author( $query ) 
{

	global $user_level;

    if( $query->is_admin && $user_level >= 3 && 'vertycal' === $query->query['post_type'] ) 
    { 

		global $user_ID;
		$query->set('author',  $user_ID);
		unset($user_ID);
	}
	    unset($user_level);

	        return $query;
}

//filter out wp_posts to only show author's post
add_filter('parse_query', 'vertycal_filter_author_posts_query' );
function vertycal_filter_author_posts_query( $wp_query ) 
{
    if ( isset ( $_SERVER[ 'REQUEST_URI' ] ) ) {
        if ( strpos( esc_url_raw( wp_unslash( $_SERVER[ 'REQUEST_URI' ] ), '/wp-admin/edit.php' ) ) !== false ) 
        {
            if ( !current_user_can( 'update_core' ) ) {
                //global $current_user;
                $current_user = new WP_User(get_current_user());
                $wp_query->set( 'author', $current_user->ID );
            }
        } 
    }
} 
