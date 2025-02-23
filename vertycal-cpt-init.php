<?php 
/** 
 * VertyCal vertical-cpt-init
 * 
 * @since 1.0.0
 * Custom Post Types
 * vertycal            Primary name - rewrite = scheduled
 * vertycal_category   Primary Taxonomy
 */ 
function vertycal_custom_post_type_schedule() {
  $labels = array(
    'name'               => _x( 'Schedule', 'post type general name', 'vertycal' ),
    'singular_name'      => _x( 'Schedule', 'post type singular name', 'vertycal' ),
    'add_new'            => _x( 'Add New', 'book', 'vertycal' ),
    'add_new_item'       => __( 'Add New Schedule', 'vertycal' ),
    'edit_item'          => __( 'Edit Schedule', 'vertycal' ),
    'new_item'           => __( 'New Schedule', 'vertycal' ),
    'all_items'          => __( 'All Schedules', 'vertycal' ),
    'view_item'          => __( 'View Schedule', 'vertycal' ),
    'search_items'       => __( 'Search Schedules', 'vertycal' ),
    'not_found'          => __( 'No schedules found', 'vertycal' ),
    'not_found_in_trash' => __( 'No schedules found in the Trash', 'vertycal' ),
    'parent_item_colon'  => '',
    'menu_name'          => 'Scheduler'
  );
  $args = array(
    'labels'        => $labels,
    'description'    => __( 'Holds the Schedules and Schedule specific data', 'vertycal' ),
    'rewrite'         => array( 'slug' => 'vertycal', 'with_front' => false ),
    'has_archive'      => true,
    'public'            => true,
    'query_var'      => true,
    'menu_position'    => 5,
    'menu-icon'       => 'dashicons-calendar',
    'supports'       => array( 'title', 'excerpt', 'comments', 'editor' ),
    'has_archive'   => true,
  );
  register_post_type( 'vertycal', $args ); 
}

// taxonomies schedule aka vertycal
function vertycal_taxonomies_forcpt_vertycal() 
{
    $labels = array(
      'name'              => _x( 'Schedules Categories', 'taxonomy general name', 'vertycal' ),
      'singular_name'     => _x( 'Schedule Category', 'taxonomy singular name', 'vertycal' ),
      'search_items'      => __( 'Search Schedule Categories', 'vertycal' ),
      'all_items'         => __( 'All Schedule Categories', 'vertycal' ),
      'parent_item'       => __( 'Parent Schedule Category', 'vertycal' ),
      'parent_item_colon' => __( 'Parent Schedule Category:', 'vertycal' ),
      'edit_item'         => __( 'Edit Schedule Category', 'vertycal' ), 
      'update_item'       => __( 'Update Schedule Category', 'vertycal' ),
      'add_new_item'      => __( 'Add New Schedule Category', 'vertycal' ),
      'new_item_name'     => __( 'New Schedule Category', 'vertycal' ),
      'menu_name'         => __( 'Schedule Categories', 'vertycal' ),
    );
    $args = array(
      'labels' => $labels,
      'hierarchical' => true,
      //'show_in_rest' => true,
    );
    register_taxonomy( 'vertycal_category', 'vertycal', $args );
}
function vertycal_cpt_updated_messages( $messages ) 
{
    global $post, $post_ID;
    $messages['vertycal'] = array(
         0 => '', // Unused. Messages start at index 1.
         1 => sprintf( __( 'Schedule updated. <a href="%s">View Schedule</a>', 'vertycal' ), 
                            esc_url( get_permalink( $post_ID ) ) ),
         2 => '',
         3 => '',
         4 => __( 'Schedule updated.', 'vertycal' ),
         5 => isset( $_GET['revision'] ) ? sprintf( __( 'Schedule restored to revision from %s', 'vertycal' ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                            wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,               // phpcs:ignore WordPress.Security.NonceVerification.Recommended
         6 => sprintf( __( 'Schedule published. <a href="%s">View schedule</a>', 'vertycal' ), esc_url( get_permalink( $post_ID ) ) ),
         7 => __( 'Schedule saved.', 'vertycal' ),
         8 => sprintf( __( 'Schedule submitted. <a target="_blank" href="%s">Preview Schedule</a>', 'vertycal' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
         9 => sprintf( __( 'Schedule to be published: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview </a>', 'vertycal' ), date_i18n( __( 'M j, Y @ G:i', 
                'vertycal' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
        10 => sprintf( __( 'Schedule draft updated. <a target="_blank" href="%s">Preview Schedule</a>',
         'vertycal' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
    );
    return $messages;
}
function vertycal_admin_contextual_help( $contextual_help, $screen_id, $screen ) 
{
    global $contextual_help, $screen_id, $screen;
    $screen = get_current_screen();
    $frst_li =  sprintf( '%1$s <em>&#39;%2$s&#39; </em>%3$s <b>%4$s</b> %5$s',
        __( 'Save uploaded files as ', 'vertycal' ),
        __( 'Media File', 'vertycal' ),
        __( 'will ', 'vertycal' ),
        __( 'open the file to desktop', 'vertycal' ),
        __( 'when viewed. ', 'vertycal' )
        );
    $scnd_li = sprintf( '%1$s &#39;<em>%2$s</em>&#39; %3$s <b>%4$s </b> %5$s',
        __( 'Save uploaded files as ', 'vertycal' ),
        __( 'Link to Attachment Page', 'vertycal' ),
        __( 'will ', 'vertycal' ),
        __( 'open that file within the Browser page', 'vertycal' ),
        __( 'when viewed.', 'vertycal' )
        );
    if ( 'vertycal' == $screen->id ) 
    {
  
      $contextual_help = '
      <h4>' . esc_html__( "Overview of How Scheduled Items Show", "vertycal" ) . '</h4>
      <p>' . esc_html__( "Schedules show the details of the items that you add on the website. You can see a list of them on this page in reverse chronological order - the latest one we added is first.", "vertycal" ) . '</p> 
      <p>' . esc_html__( "You can view/edit the details of each scheduled item by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items.", "vertycal" ) . '</p>
      <h4>' . esc_html__( "Overview of Editing and Adding Files", "vertycal" ) . '</h4>
      <dl>
      <dt><span>' . esc_html__( "Attachment Display Settings notes: ", "vertycal" ) . '</span></dt>
      <dd>' . $frst_li . '</dd>
      <dd>' . $scnd_li . '</dd>
      </dl>';

    } elseif ( 'edit-vertycal' == $screen->id ) 
    {
  
      $contextual_help = '<h2>' . esc_html__( "Editing", "vertycal" ) . '</h2>
      <p>' . esc_html__( "This page allows you to view/modify editing details. Please make sure to fill out the available boxes with the appropriate details (Schedule image, price, brand) and <strong>not</strong> add these details to the description.", "vertycal" ) . '</p>';
  
    } else 
    { 
        $contexual_help = null;
    }
    return $contextual_help;
} 
