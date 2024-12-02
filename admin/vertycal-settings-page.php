<?php 
/**
 * Sets all admin options settings
 * @since 1.1.0
 * @package vertycal
 * @subpackage admin/vertycal-settings-page
 */
if ( ! defined( 'ABSPATH' ) ) {	exit; } // exit if file is called directly
	/**
	 * Add sub-level administrative menu
	 *  
	 * @since   1.0.0
	 * string   $parent_slug,
	 * string   $page_title,
	 * string   $menu_title,
	 * string   $capability,
	 * string   $menu_slug,
	 * callable $function = ''
	 */

    function vertycal_add_sublevel_menu() {

        add_submenu_page(
            'edit.php?post_type=vertycal',
            __( 'VertyCal Options Settings' ),
            __( 'VertyCal Settings '),
            'manage_options',
            'vertycal',
            'vertycal_display_settings_page',
            30,
            'dashicons-calendar'
        );
    
    }
    add_action( 'admin_menu', 'vertycal_add_sublevel_menu' );
    add_action( 'admin_init', 'vertycal_register_admin_options' ); 
/** a.) Register new settings
 *  $option_group (page), $option_name, $sanitize_callback
 *  --------
 ** b.) Add sections
 *  $id, $title, $callback, $page
 *  --------
 ** c.) Add fields 
 *  $id, $title, $callback, $page, $section, $args = array() 
 *  --------
 ** d.) Options Form Rendering. action="options.php"
 *
 */
/**
 * Register settings for options page
 *
 * @since    1.0.0
 * 
 * a.) register all settings groups
 * Register Settings $option_group, $option_name, $sanitize_callback 
 */
function vertycal_register_admin_options() 
{
    
    register_setting( 'vertycal_options', 'vertycal_options' );
    register_setting( 'vertycal_docs',    'vertycal_docs' );
    
    //add a section to admin page
    add_settings_section(
        'vertycal_options_settings_section',
        '',
        'vertycal_options_settings_section_callback',
        'vertycal_options'
    );
    //add a section to admin page vertycal_docs_settings_section 
    add_settings_section(
        'vertycal_docs_settings_section',
        '',
        'vertycal_docs_settings_section_callback',
        'vertycal_docs'
    );
    // documents 'field'
    add_settings_field(
        'vertycal_docs_field_0',
        __( 'Instruction | Help', 'vertycal' ),
        'vertycal_docs_field_0_render',
        'vertycal_docs',
        'vertycal_docs_settings_section'
    );

    //$id, $title, $callback, $page, $section, $args = array()
    // c.1.) parameters for title
    add_settings_field(
        'vertycal_text_field_0',
        __( 'Public Heading Title', 'vertycal' ),
        'vertycal_text_field_0_render',
        'vertycal_options',
        'vertycal_options_settings_section',
        array( 
            'type'        => 'text',
            'option_name' => 'vertycal_options', 
            'name'        => 'vertycal_text_field_0',
            'value'       => ( empty( get_option('vertycal_options')['vertycal_text_field_0'] )) 
                       ? 'Schedule' : get_option('vertycal_options')['vertycal_text_field_0'],
            'description' => esc_html__( 'Set title in table header', 'vertycal' ),
            'tip'         => esc_attr__( 'Set title that appears at the top of the Scheduler page form table', 
                                         'vertycal' ),
            'default'     => '',
            'placeholder' => esc_attr__( 'Schedule', 'vertycal' )   
        ) 
    );
    // c.2.) parameters for page name
    add_settings_field(
        'vertycal_text_field_1',
        __( 'Page Name of Scheduler', 'vertycal' ),
        'vertycal_text_field_1_render',
        'vertycal_options',
        'vertycal_options_settings_section',
        array( 
        'type'        => 'text',
        'option_name' => 'vertycal_options', 
        'name'        => 'vertycal_text_field_1',
        'value'       => ( empty( get_option('vertycal_options')['vertycal_text_field_1'] )) 
                  ? 'Scheduler' : get_option('vertycal_options')['vertycal_text_field_1'],
        'description' => esc_html__( 'Template alternate name', 'vertycal' ),
        'tip'         => esc_attr__( 'If you do not use &#39;Scheduler&#39; for your page name please fill it in here.', 'vertycal' ),
        'sub_text'    => esc_html__( 'ONLY change if not using default page!', 'vertycal' ),
        'default'     => 'Scheduler',
        'placeholder' => esc_attr__( 'dedicated page name', 'vertycal' )   
        ) 
    );
    // c.3.) email for notify mark done
    add_settings_field(
        'vertycal_email_field_1',
        __( 'Email to Send Fulfilled', 'vertycal' ),
        'vertycal_email_field_1_render',
        'vertycal_options',
        'vertycal_options_settings_section',
        array( 
            'type'        => 'email',
            'option_name' => 'vertycal_options', 
            'name'        => 'vertycal_email_field_1',
            'value'       => ( empty( get_option('vertycal_options')['vertycal_email_field_1'] )) 
                ? get_option( 'admin_email' ) : get_option('vertycal_options')['vertycal_email_field_1'],
            'description' => esc_html__( 'Email address to send Mark Done notice.', 'vertycal' ),
            'tip'         => esc_attr__( 'Enter email address to where you want to receive the notice that will represent a completed task.', 
                                         'vertycal' ),
            'default'     => get_option( 'admin_email' ),
            'placeholder' => esc_attr__( '', 'vertycal' )   
        ) 
    );     
     // c.4.) margin setting option
    add_settings_field(
        'vertycal_text_field_2',
        __( 'Bottom of Tables Margin', 'vertycal' ), 
        'vertycal_text_field_2_render',
        'vertycal_options',
        'vertycal_options_settings_section',
        array( 
            'type'        => 'number',
            'option_name' => 'vertycal_options', 
            'name'        => 'vertycal_text_field_2',
            'value'       => ( empty( get_option('vertycal_options')['vertycal_text_field_2'] )) 
                       ? '15' : get_option('vertycal_options')['vertycal_text_field_2'],
            'description' => esc_html__( 'Bottom of page spacing', 'vertycal' ),
            'tip'         => esc_attr__( 'Set the number of pixels between the bottom of your single post entry and the footer.', 
                                         'vertycal' ),
            'default'     => '15',
            'placeholder' => ''   
        ) 
    );
    // c.5.) font size
    add_settings_field(
        'vertycal_font_field_1',
        __( 'Scheduler Font Size', 'vertycal' ), 
        'vertycal_font_field_1_render',
        'vertycal_options',
        'vertycal_options_settings_section',
        array( 
            'type'        => 'number',
            'option_name' => 'vertycal_options', 
            'name'        => 'vertycal_font_field_1',
            'value'       => ( empty( get_option('vertycal_options')['vertycal_font_field_1'] )) 
                       ? '100' : get_option('vertycal_options')['vertycal_font_field_1'],
            'description' => esc_html__( 'Font size in percentage', 'vertycal' ),
            'tip'         => esc_attr__( 'Set the size of your font in the Scheduler tables.', 
                                         'vertycal' ),
            'default'     => '100',
            'placeholder' => '' 
        ) 
    );
    // c.6.) settings vertycal_users_caps
    add_settings_field(
        'vertycal_users_caps',
        esc_attr__('Limit User Data', 'vertycal'),
        'vertycal_users_caps_render',
        'vertycal_options',
        'vertycal_options_settings_section',
        array( 
            'type'        => 'checkbox',
            'option_name' => 'vertycal_options', 
            'name'        => 'vertycal_users_caps',
            'label_for'   => 'vertycal_users_caps',
            'value'       => ( empty( get_option('vertycal_options')['vertycal_users_caps'] )) 
            ? '0' : get_option('vertycal_options')['vertycal_users_caps'],
            'description' => esc_html__( 'Check to allow user to see and edit only their own posts.', 
                             'vertycal' ),
            'checked'     => esc_attr( checked( 1, 
            ( empty( get_option('vertycal_options')['vertycal_users_caps'] )) 
            ? '0' : get_option('vertycal_options')['vertycal_users_caps'], false ) ),
            'tip'         => esc_html__( 'This option allows only Administrator to see or edit every user scheduled post. Even Media uploads will be restricted.', 
                             'vertycal' )
            )
    );    
    // c.6.) checkbox field vertycal_state_checkbox_markdone 
    add_settings_field(
        'vertycal_checkbox_markdone',
        __( 'Mark Complete Usage', 'vertycal' ),
        'vertycal_checkbox_markdone_render',
        'vertycal_options',
        'vertycal_options_settings_section',
        array( 
            'type'        => 'checkbox',
            'option_name' => 'vertycal_options', 
            'name'        => 'vertycal_checkbox_markdone',
            'value'       => ( empty( get_option('vertycal_options')['vertycal_checkbox_markdone'] )) 
            ? '0' : get_option('vertycal_options')['vertycal_checkbox_markdone'],
            'description' => esc_html__( 'Check to have access to marking status publically.',
                                         'vertycal' ),
            'checked'     => esc_attr( checked( 1, 
            ( empty( get_option('vertycal_options')['vertycal_checkbox_markdone'] )) 
            ? '0' : get_option('vertycal_options')['vertycal_checkbox_markdone'], false ) ),
            'tip'         => esc_attr__( 'By default marking a task as complete must be done on the Admin side editor page. 
                                Checking this box will allow task to be marked complete directly from the Scheduler page.
                Once checked and submitted an email is sent to the admin of site.', 'vertycal' ) 
            )
    ); 
    // c.6a.) checkbox field vertycal_state_checkbox_showyear
    add_settings_field(
        'vertycal_checkbox_showyear',
        __( 'Show or Hide Year', 'vertycal' ),
        'vertycal_checkbox_showyear_render',
        'vertycal_options',
        'vertycal_options_settings_section',
        array( 
            'type'        => 'checkbox',
            'option_name' => 'vertycal_options', 
            'name'        => 'vertycal_checkbox_showyear',
            'value'       => ( empty( get_option('vertycal_options')['vertycal_checkbox_showyear'] )) 
            ? '0' : get_option('vertycal_options')['vertycal_checkbox_showyear'],
            'description' => esc_html__( 'Check to remove year display from Scheduler.',
                                         'vertycal' ),
            'checked'     => esc_attr( checked( 1, 
            ( empty( get_option('vertycal_options')['vertycal_checkbox_showyear'] )) 
            ? '0' : get_option('vertycal_options')['vertycal_checkbox_showyear'], false ) ),
            'tip'         => esc_attr__( 'By default year will show on the Scheduler table page. 
            Checking this box will remove the display of the year from the Scheduler table view.', 'vertycal' ) 
            )
    ); 
    // c.7) settings vertycal_dashnews_widgetcheck
    add_settings_field(
        'vertycal_dashnews_widgetcheck',
        esc_attr__('Add Custom Branding colors', 'vertycal'),
        'vertycal_dashnews_widgetcheck_cb',
        'vertycal_options',
        'vertycal_options_settings_section',
        array( 
            'type'        => 'checkbox',
            'option_name' => 'vertycal_options', 
            'name'        => 'vertycal_dashnews_widgetcheck',
            'label_for'   => 'vertycal_dashnews_widgetcheck',
            'value'       => ( empty( get_option('vertycal_options')['vertycal_dashnews_widgetcheck'] )) 
            ? '0' : get_option('vertycal_options')['vertycal_dashnews_widgetcheck'],
            'description' => esc_html__( 'Check to remove some WordPress Dashboard widgets and re-brand controls with custom colors.', 
                             'vertycal' ),
            'checked'     =>  esc_attr( checked( 1, 
            ( empty( get_option('vertycal_options')['vertycal_dashnews_widgetcheck'] )) 
            ? '0' : get_option('vertycal_options')['vertycal_dashnews_widgetcheck'], false ) ),
            'tip'         => esc_attr__( 'This option personalizes the dashboard with custom information for each logged in user.', 
                             'vertycal' )
            )
    );
    //color settings 
    add_settings_field(
        'vertycal_color_field_1',
        __( 'Date Time Primary Color', 'vertycal' ),
        'vertycal_color_field_1_cb',
        'vertycal_options',
        'vertycal_options_settings_section',
        array( 
            'label'       => esc_html__( 'Color setting', 'vertycal' ), 
            'type'        => 'color',
            'option_name' => 'vertycal_options', 
            'name'        => 'vertycal_color_field_1',
            'value'       => ( empty( get_option('vertycal_options')['vertycal_color_field_1'] )) 
                        ? '#155d93' : get_option('vertycal_options')['vertycal_color_field_1'],
            'class'       => 'select-text',
            'default'     => '#155d93',
            'description' => esc_html__( 'Set text color in Sheduler display table.', 'vertycal' ),
            'tip'     => esc_attr__( 'DateTime |first field| Also will be the themed color for admin sidebar.', 'vertycal' ),  
        ) 
    );  
    //color settings 
    add_settings_field(
        'vertycal_color_field_2',
        __( 'Admin Text Color', 'vertycal' ),
        'vertycal_color_field_2_cb',
        'vertycal_options',
        'vertycal_options_settings_section',
        array( 
            'label'       => esc_html__( 'Color setting', 'vertycal' ), 
            'type'        => 'color',
            'option_name' => 'vertycal_options', 
            'name'        => 'vertycal_color_field_2',
            'value'       => ( empty( get_option('vertycal_options')['vertycal_color_field_2'] )) 
                        ? '#ffffff' : get_option('vertycal_options')['vertycal_color_field_2'],
            'class'       => 'select-text',
            'default'     => '#ffffff',
            'description' => esc_html__( 'Set text color for menu items.', 'vertycal' ),
            'tip'     => esc_attr__( 'Menu items for the admin sidebar - mostly only text link color will change.', 'vertycal' ),  
        ) 
    ); 
    //color settings rgb(225,214,153)
    add_settings_field(
        'vertycal_color_field_3',
        __( 'Admin Background Color', 'vertycal' ),
        'vertycal_color_field_3_cb',
        'vertycal_options',
        'vertycal_options_settings_section',
        array( 
            'label'       => esc_html__( 'Color setting', 'vertycal' ), 
            'type'        => 'color',
            'option_name' => 'vertycal_options', 
            'name'        => 'vertycal_color_field_3',
            'value'       => ( empty( get_option('vertycal_options')['vertycal_color_field_3'] )) 
                        ? '#e1d699' : get_option('vertycal_options')['vertycal_color_field_3'],
            'class'       => 'select-text',
            'default'     => '#e1d699',
            'description' => esc_html__( 'Set background color in admin page.', 'vertycal' ),
            'tip'     => esc_attr__( 'Most likely the page you are now on.', 'vertycal' ),  
        ) 
    );  
    add_settings_field(
        'vertycal_whosees_what',
        __( 'Determine What Content Shows', 'vertycal'),
        'vertycal_whosees_what_cb',
        'vertycal_options',
        'vertycal_options_settings_section',
        array( 
            'tip'     => esc_attr__( 'Users that are logged in will see only the content you allow if one of the last two choices are selected. Select Protect All Content and only logged in users will see all pages.', 'vertycal' ),  
        )
    );
    add_settings_field(
        'vertycal_public_page_post',
        __( 'Set Post ID to Show for Options', 'vertycal' ),
        'vertycal_public_page_post_cb',
        'vertycal_options',
        'vertycal_options_settings_section',
        array( 
            'type'        => 'text',
            'option_name' => 'vertycal_options', 
            'name'        => 'vertycal_public_page_post',
            'value'       => ( empty( get_option('vertycal_options')['vertycal_public_page_post'] )) 
                            ? false : get_option('vertycal_options')['vertycal_public_page_post'],
            'default'     => '',
            'description' => esc_html__( 'Enter the post ID number to display on the Options page of Scheduler.', 'vertycal' ),
            'tip'     => esc_attr__( 'Create a post using the standard post type and get the ID of said post by looking at the address bar. The ID number will be followed by &#39;?edit.php=xxxx&#39;.', 'vertycal' ),  
            'placeholder' => esc_attr__( 'See tip on how to find ID', 'vertycal' )   
        ) 
    );  
}

/**
 * Render the before content on which page
 * @string $contpg
 * @since  1.0.3
 * All pages as default
 */
function vertycal_whosees_what_cb($args) 
{ 
    $options = get_option('vertycal_options'); 
    $vcontpg = ( empty ( $options['vertycal_whosees_what'] ) ) 
               ? 'single' : $options['vertycal_whosees_what'];
    ?>
    <fieldset><b class="vrttip" data-title=<?php print( $args['tip'] ); ?>">?</b><sup>13</sup>
    <label class="olmin"><?php esc_html_e( 'What Content to make Public', 'vertycal' ); ?></label>
    <select name = "vertycal_options[vertycal_whosees_what]" id="vcontpg">
        <option value="all"    <?php selected( 'all', $vcontpg ); ?> 
                title="<?php esc_attr_e( 'Only logged in users will see pages', 'vertycal' ); ?>">
        <?php esc_html_e( 'Protect All VertyCal Pages', 'vertycal' ); ?></option>

        <option value="public" <?php selected( 'public', $vcontpg ); ?>
                title="<?php esc_attr_e( 'Anyone will see only Scheduler page.', 'vertycal' ); ?>">
        <?php esc_html_e( 'Protect Single Listing Publicly', 'vertycal' ); ?></option>

        <option value="home"   <?php selected( 'home', $vcontpg ); ?>
                title="<?php esc_attr_e( 'Logged in users will see both Scheduler and single Scheduled pages.', 'vertycal' ); ?>">
        <?php esc_html_e( 'No Restrict Sheduler Pages', 'vertycal' ); ?></option>
        
        <option value="single" <?php selected( 'single', $vcontpg ); ?>
                title="<?php esc_attr_e( 'Logged in users will see only Scheduler page.', 'vertycal' ); ?>">
        <?php esc_html_e( 'Protect Single Listing Only', 'vertycal' ); ?></option>

        <option value="not-all" <?php selected( 'not-all', $vcontpg ); ?> 
                title="<?php esc_attr_e( 'Everyone will see both pages but not Add New form', 'vertycal' ); ?>">
        <?php esc_html_e( 'Publicly View All Pages Debugging only', 'vertycal' ); ?></option>


    </select>
    </fieldset>
<?php      
}

/** 
 * render for '0' field
 * @since 1.0.0
 */
function vertycal_public_page_post_cb($args)
{  
   printf(
    '<fieldset><b class="vrttip" data-title="%7$s">?</b><sup>14</sup>
    <input id="%1$s" class="regular-text" type="%2$s" 
    name="%3$s[%1$s]" value="%4$s" placeholder="%5$s"/><br>
    <span class="vmarg">%6$s </span></fieldset>',
        $args['name'],
        $args['type'],
        $args['option_name'],
        $args['value'],
        $args['placeholder'],
        $args['description'],
        $args['tip']
    );
}

/** 
 * render for '0' field
 * @since 1.0.0
 */
function vertycal_text_field_0_render($args)
{  
   printf(
    '<fieldset><b class="vrttip" data-title="%6$s">?</b><sup>1</sup>
    <input id="%1$s" class="regular-text" type="%2$s" 
    name="%3$s[%1$s]" value="%4$s" /><br>
    <span class="vmarg">%5$s </span></fieldset>',
        $args['name'],
        $args['type'],
        $args['option_name'],
        $args['value'],
        $args['description'],
        $args['tip']
    );
}

/** 
 * render for '1' field
 * @since 1.0.0
 */
function vertycal_text_field_1_render($args)
{  
   printf(
    '<fieldset><b class="vrttip" data-title="%6$s">?</b><sup>2</sup>
    <input id="%1$s" class="regular-text" type="%2$s" 
    name="%3$s[%1$s]" value="%4$s" /><br>
    <span class="vmarg">%5$s <small> %7$s</small></span></fieldset>',
        $args['name'],
        $args['type'],
        $args['option_name'],
        $args['value'],
        $args['description'],
        $args['tip'],
        $args['sub_text']
    );
}
/** 
 * render for '0' field
 * @since 1.0.0
 */
function vertycal_email_field_1_render($args)
{  
   printf(
    '<fieldset><b class="vrttip" data-title="%6$s">?</b><sup>3</sup>
    <input id="%1$s" class="regular-text" type="%2$s" 
    name="%3$s[%1$s]" value="%4$s" /><br>
    <span class="vmarg">%5$s </span></fieldset>',
        $args['name'],
        $args['type'],
        $args['option_name'],
        $args['value'],
        $args['description'],
        $args['tip']
    );
}
/** 
 *  d.4.) render for '2' text_field
 * @since 1.0.0
 */
function vertycal_text_field_2_render($args)
{  
   printf(
    '<fieldset><b class="vrttip" data-title="%6$s">?</b><sup>4</sup>
    <input id="%1$s" class="regular-text" type="%2$s" 
    name="%3$s[%1$s]" value="%4$s" /><br>
    <span class="vmarg">%5$s </span></fieldset>',
        $args['name'],
        $args['type'],
        $args['option_name'],
        $args['value'],
        $args['description'],
        $args['tip']
    );
}
/** 
 *  d.5.) render for '1' font_field
 * @since 1.0.0
 */
function vertycal_font_field_1_render($args)
{  
   printf(
    '<fieldset><b class="vrttip" data-title="%6$s">?</b><sup>5</sup>
    <input id="%1$s" class="regular-text" type="%2$s" 
    name="%3$s[%1$s]" value="%4$s" /><br>
    <span class="vmarg">%5$s </span></fieldset>',
        $args['name'],
        $args['type'],
        $args['option_name'],
        $args['value'],
        $args['description'],
        $args['tip']
    );
}
/** 
 *  d.6.) render checkbox 'users_caps' field
 * @since 1.0.0
 */
function vertycal_users_caps_render($args)
{
    printf(
    '<fieldset><b class="vrttip" data-title="%6$s">?</b><sup>8</sup>
    <input type="hidden" name="%3$s[%1$s]" value="0">
    <input id="%1$s" type="%2$s" name="%3$s[%1$s]" value="1"  
    class="regular-checkbox" %7$s /><br>
    <span class="vmarg">%5$s </span><em> = %4$s</em></fieldset>',
        $args['name'],
        $args['type'],
        $args['option_name'],
        $args['value'],
        $args['description'],
        $args['tip'],
        $args['checked']
    );
}

/** 
 * render checkbox 'limit user data' field
 * @since 1.0.0
 */
function vertycal_checkbox_markdone_render($args)
{  
   printf(
    '<fieldset><b class="vrttip" data-title="%6$s">?</b><sup>8</sup>
    <input type="hidden" name="%3$s[%1$s]" value="0">
    <input id="%1$s" type="%2$s" name="%3$s[%1$s]" value="1"  
    class="regular-checkbox" %7$s /><br>
    <span class="vmarg">%5$s </span><em> = %4$s</em></fieldset>',
        $args['name'],
        $args['type'],
        $args['option_name'],
        $args['value'],
        $args['description'],
        $args['tip'],
        $args['checked']
    );
}

/** 
 * render checkbox 'show hide year' field
 * @since 1.0.0
 */
function vertycal_checkbox_linkview_render($args)
{  
   printf(
    '<fieldset><b class="vrttip" data-title="%6$s">?</b><sup>7</sup>
    <input type="hidden" name="%3$s[%1$s]" value="0">
    <input id="%1$s" type="%2$s" name="%3$s[%1$s]" value="1"  
    class="regular-checkbox" %7$s /><br>
    <span class="vmarg">%5$s </span><em> = %4$s</em></fieldset>',
        $args['name'],
        $args['type'],
        $args['option_name'],
        $args['value'],
        $args['description'],
        $args['tip'],
        $args['checked']
    );
}

/** 
 * render checkbox 'year show' field
 * @since 1.0.0
 */
function vertycal_checkbox_showyear_render($args)
{  
   printf(
    '<fieldset><b class="vrttip" data-title="%6$s">?</b><sup>8</sup>
    <input type="hidden" name="%3$s[%1$s]" value="0">
    <input id="%1$s" type="%2$s" name="%3$s[%1$s]" value="1"  
    class="regular-checkbox" %7$s /><br>
    <span class="vmarg">%5$s </span><em> = %4$s</em></fieldset>',
        $args['name'],
        $args['type'],
        $args['option_name'],
        $args['value'],
        $args['description'],
        $args['tip'],
        $args['checked']
    );
}

/** 
 * switch for 'dashboard new widget' 
 * @since 1.0.1
 * @input type checkbox
 */
function vertycal_dashnews_widgetcheck_cb($args)
{ 
     printf(
        '<fieldset><b class="vrttip" data-title="%6$s">?</b><sup>9</sup>
    <input type="hidden" name="%3$s[%1$s]" value="0">
    <input id="%1$s" type="%2$s" name="%3$s[%1$s]" value="1"  
    class="regular-checkbox" %7$s /><br>
    <span class="vmarg">%5$s </span><em> = %4$s</em></fieldset>',
        $args['name'],
        $args['type'],
        $args['option_name'],
        $args['value'],
        $args['description'],
        $args['tip'],
        $args['checked']
    );
}     
/**
 * Render page branding colors option
 * @string $args = array()
 * @since  1.0.0
 */
function vertycal_color_field_1_cb($args) 
{ 
    //be safe default
    if($args['default'] == '') $args['default'] = sanitize_text_field('#46494c');

    printf( '<fieldset><label>%1$s </label> <b class="vrttip" data-title="%9$s">?</b><sup>10</sup> 
    <em> %7$s</em><br>
        <input type="%2$s" name="%3$s[%4$s]" value="%5$s" 
        class="%6$s vertycal-color-picker-1" id="%3$s-%4$s"
         data-default-color="%8$s"/> </fieldset>',
        $args['label'],
        $args['type'],
        $args['option_name'],
        $args['name'],        
        $args['value'],
        $args['class'],
        $args['description'],
        $args['default'],
        $args['tip']
    ); 
}
/**
 * Render page branding colors option
 * @string $args = array()
 * @since  1.0.0
 */
function vertycal_color_field_2_cb($args) 
{ 
    //be safe default
    if($args['default'] == '') $args['default'] = sanitize_text_field('#ffffff');

    printf( '<fieldset><label>%1$s </label> <b class="vrttip" data-title="%9$s">?</b><sup>11</sup> 
    <em> %7$s</em><br>
        <input type="%2$s" name="%3$s[%4$s]" value="%5$s" 
        class="%6$s vertycal-color-picker-2" id="%3$s-%4$s"
         data-default-color="%8$s"/> </fieldset>',
        $args['label'],
        $args['type'],
        $args['option_name'],
        $args['name'],        
        $args['value'],
        $args['class'],
        $args['description'],
        $args['default'],
        $args['tip']
    ); 
}
/**
 * Render page branding colors option
 * @string $args = array()
 * @since  1.0.0
 */
function vertycal_color_field_3_cb($args) 
{ 
    //be safe default
    if($args['default'] == '') $args['default'] = sanitize_text_field('#e1d699');

    printf( '<fieldset><label>%1$s </label> <b class="vrttip" data-title="%9$s">?</b><sup>12</sup> 
    <em> %7$s</em><br>
        <input type="%2$s" name="%3$s[%4$s]" value="%5$s" 
        class="%6$s vertycal-color-picker-3" id="%3$s-%4$s"
         data-default-color="%8$s"/> </fieldset>',
        $args['label'],
        $args['type'],
        $args['option_name'],
        $args['name'],        
        $args['value'],
        $args['class'],
        $args['description'],
        $args['default'],
        $args['tip']
    ); 
}
/** 
 * info fields 
 * @since 1.0.1
 * @uses ASCII in code elements to sanitize output
 */
function vertycal_docs_field_0_render()
{ 
//$nessy = esc_url( plugin_dir_url(dirname(__FILE__)) . 'css/monster68x.png' );
$schedulertheme_url = 'http://sunlandcomputers/vertycal/documentation/'; 
?>
<p class="vrtcl-activate" style="background:#f2f2a0"><?php esc_html_e( 'Thank you for adding this plugin! There are two requirements prior to using VertyCal: Please make a blank PAGE named ', 
            'vertycal' ); ?> 
    <strong><a href="<?php echo esc_url( $newpage ); ?>" 
            title="<?php echo esc_html__( 'Sheduler', 'vertycal' ); ?>">
    <?php echo esc_html__( 'Sheduler', 'vertycal' ); ?></a></strong> <?php esc_html_e( 'And another for Viewing called', 'vertycal' ); ?> <strong>Scheduled</strong>.</p>
<?php 
ob_start();
echo '<div class="vrtcl-wrap-docs"><h3>' . esc_html__( 'FAQ - Get more information on the following options and settings', 'vertycal' ) . '</h3><div id="vertycalDocsField">
<ul class="vertycal-faq">

<li class="vertycal-question"><sup>1</sup>' . esc_html__( 'Public Heading Title', 'vertycal' ) . '</li>
<li class="vertycal-answer">' . esc_html__( 'This field sets the title of the Scheduler table. This helps brand your site to any type of listing you would like it to be. Possible titles could be Schedules, Scheduler, Deliveries, Events, Meetings, etc.', 'vertycal' ) . '</li>

<li class="vertycal-question"><sup>2</sup>' . esc_html__( 'Page Name of Scheduler', 'vertycal' ) . '</li>
<li class="vertycal-answer">' . esc_html__( 'A page name <!>is required<!> to make the VertyCal scheduler page display the table page to the public, or private. By default the page name is Scheduler and you should not have to change this unless you would like to have the url in the address bar display a different name--such as service-calls, etc. It may also be needed for language translations where you language type set is only readable with a certain font, maybe.', 'vertycal' ) . '</li>

<li class="vertycal-question"><sup>3</sup>' . esc_html__( 'Email to Send Notification', 'vertycal' ) . '</li>
<li class="vertycal-answer">' . esc_html__( 'By adding an email to this field you are telling the plugin to send an email to the address you enter whenever a scheduled item is marked Complete. If you leave this blank then no emails will be sent. By default your WordPress admin email will not be used. Be sure to add an email here if you want to receive messages.', 'vertycal' ) . '</li>

<li class="vertycal-question"><sup>4</sup>' . esc_html__( 'Schedule Page Margin', 'vertycal' ) . '</li>
<li class="vertycal-answer">' . esc_html__( 'This setting is for using VertyCal with themes which may or may not support VertyCal plugin. Default is 15 pixels so you may not need to change anything. The setting makes more space between the bottom of the scheduling table and whatever comes after that in your theme. This helps with mobile views as well. Adjust accordingly by experimenting with various devices. By default the Scheduler Theme* that is designed for VertyCal should work well on all devices. Also check the Custimzer settings if you are using the Scheduler Theme. There are several functions to help you preserve more space and helps provide a cleaner user interface.', 'vertycal' ) . '</li>

<li class="vertycal-question"><sup>5</sup>' . esc_html__( 'Scheduler Font Size', 'vertycal' ) . '</li>
<li class="vertycal-answer">' . esc_html__( 'You can set the size of the font in the scheding table. This setting is adjusted by a percentage factor of the theme default font size. If the body tag has a font size of 16px, for example, then 100% would be 16 pixels.', 'vertycal' ) . '</li>

<li class="vertycal-question"><sup>6</sup>' . esc_html__( 'Limit User Data', 'vertycal' ) . '</li>
<li class="vertycal-answer">' . esc_html__( 'This option allows users to edit only their own items and see only their own attachments such as documents and media they may have uploaded. Settings are restricted to allow only Administrators to edit_posts, edit_published_posts, edit_others_posts and edit_private_posts. Ticing this box makes only Administrator level users have the capabilities to do these tasks. Otherwise if you uncheck the box field you will have all items including media and editing of others items available. This could be used if you have a team or only one person using the site and you want to have broader access to editing items.', 'vertycal' ) . '</li>

<li class="vertycal-question"><sup>7</sup>' . esc_html__( 'Mark Complete Usage', 'vertycal' ) . '</li>
<li class="vertycal-answer">' . esc_html__( 'By ticcing this option your are allowing users to mark a Scheduled item as &#39;Fulfilled&#39. By default marking a task as complete must be done on the Admin side editor page. Checking this box will allow task to be marked complete directly from the Scheduler page. Once checked and submitted an email is sent to the admin of site.', 'vertycal' ) . '</li>

<li class="vertycal-question"><sup>8</sup>' . esc_html__( 'Show Hide Year', 'vertycal' ) . '</li>
<li class="vertycal-answer">' . esc_html__( 'By checking the box in this setting you will be removeing the year from in front of the first displayed date in the scheduler table. By unchecking you will be showing the year date format. Show is the default.', 'vertycal' ) . '</li>

<li class="vertycal-question"><sup>9</sup>' . esc_html__( 'Add Custom Dashboard Widget', 'vertycal' ) . '</li>
<li class="vertycal-answer">' . esc_html__( 'By activating the branding option you are turning on several settings and optimizing components to the Admin side Dashboard and Menus. Turning on this selection will effect most of the settings options. For example if you want to use the color settings for more than just the public/front-side colors. Once this is turned on you can custom brand your backend to fit your company branding.', 'vertycal' ) . '</li>

<li class="vertycal-question"><sup>10</sup>' . esc_html__( 'Date Time Primary Colors', 'vertycal' ) . '</li>
<li class="vertycal-answer">' . esc_html__( 'If the branding option is activated this options and the next two options will work. This color option sets the sidebar menu color AND the color of the text on the scheduler front side table. You may try several configurations to get the one that works best for you branding to match your company colors or just to make the screen easier to read for your users.', 'vertycal' ) . '</li>

<li class="vertycal-question"><sup>11</sup>' . esc_html__( 'Admin Text Color', 'vertycal' ) . '</li>
<li class="vertycal-answer">' . esc_html__( 'Set the text color for all elements inside the administrative page menu sidebar. This color should be the opposite contrast of the above setting which is the background color of sidebar menus.', 'vertycal' ) . '</li>

<li class="vertycal-question"><sup>12</sup>' . esc_html__( 'Admin Background Color', 'vertycal' ) . '</li>
<li class="vertycal-answer">' . esc_html__( 'Setting the background color of your working admin main body using this option will also set the background color of the main scheduler table on the front viewable side.', 'vertycal' ) . '</li>

<li class="vertycal-question"><sup>13</sup>' . esc_html__( 'Determine What Content Shows', 'vertycal' ) . '</li>
<li class="vertycal-answer">' . esc_html__( 'By default all content of this plugin should be private and not viewable to the public with the exception of the scheduler table. You may change this by selecting on of the four options. 1. Protect All VertyCal Pages - just means anyone must be logged in to view any VertyCal page. 2. Protect Single Listing Publicly. This protects only the single full page listing of a scheduled item. You will know this page by looking at the address bar and it says &#39;scheduled/&#39; just before the name of the page item. By contrast the page you see the main scheduler table on will display &#39;scheduler&#39; in the address bar. 3. No Restrict Scheduler Page. Logged in users will see both Scheduler and single Scheduled pages. But both pages will not be seen by the public. 4. Protect Single Listing Only. Logged in users will see only Scheduler page. This means that no one but Admins will see any single Scheduled page. 5. Publicly View All Pages Debugging only. Everyone will see both pages but not able to use Add New form. only recommended if your information is OK to be seen publically.', 'vertycal' ) . '</li>

<li class="vertycal-question"><sup>14</sup>' . esc_html__( 'Set Post ID to Show for Options', 'vertycal' ) . '</li>
<li class="vertycal-answer">' . esc_html__( 'Enter the post ID number to display on the Options page of Scheduler. You can add any tyype of content to a post and build an buide book or any type of regulations or service level expectations, maybe. Create a post using the standard post type and get the ID of said post by looking at the address bar. The ID number will be followed by &#39;?edit.php=xxxx&#39;.', 'vertycal' ) .'</li>

</ul>

<p><sup>*</sup><a href="' . esc_url( $schedulertheme_url ) . '" title="' . esc_url( $schedulertheme_url ) . '" target="_blank">' . esc_url( $schedulertheme_url ) . '</a></p>
<ul class="vertycal-faq-two">
<li>' . esc_html__( 'FAQ', 'vertycal' ) .'</li>
<li class="vertycal-question">' . esc_html__( 'Schedule table looks bad on page', 'vertycal' ) . '</li>
<li class="vertycal-answer">' . esc_html__( 'Your theme may not support Full Width Layout. First try looking for a Full Width or No Sidebar template in the page editor. If neither is available, or do not work, Try removing the widgets inside of any sidebar from the Widgets page.', 'vertycal' ) . '</li>
<li class="vertycal-question">' . esc_html__( 'Added new user but they can not add Shedules', 'vertycal' ) . '</li>
<li class="vertycal-answer">' . esc_html__( 'You must make the new user an Editor or Administrator when the new user is added. This can be done under Users > Role.', 'vertycal' ) . '</li>
</ul>
<hr>
<h4>' . esc_html__( 'Options Settings Reference', 'vertycal' ) .'</h4>
<h5>vertycal_docs_settings_section</h5>
<table width="98%">
<tr>
<th>' . esc_html__( 'Option', 'vertycal' ) .'</th>
<th>' . esc_html__( 'Vertycal Option Name', 'vertycal' ) .'</th>
<th>' . esc_html__( 'ref.', 'vertycal' ) .'</th>
</tr>
<tr>
<td>' . esc_html__( 'Documentation', 'vertycal' ) .'</td>
<td>vertycal_docs_field_0</td>
<td>vertycal-settings.php</td>
</tr>
</tbody>
</table>
<br>
<h5>vertycal_options_settings_section</h5>
<table width="98%">
<tbody>
<tr>
<th>' . esc_html__( 'Option', 'vertycal' ) .'</th>
<th>' . esc_html__( 'Vertycal Option Name', 'vertycal' ) .'</th>
<th>' . esc_html__( 'ref.', 'vertycal' ) .'</th>
</tr>
<tr>
<td>' . esc_html__( 'Public Heading Title', 'vertycal' ) .'</td><td> vertycal_text_field_0</td><td></td></tr><tr>
<td>' . esc_html__( 'Page Name of Scheduler', 'vertycal' ) .'</td><td> vertycal_text_field_1</td><td></td></tr><tr>
<td>' . esc_html__( 'Email to Send Fulfilled', 'vertycal' ) .'</td><td> vertycal_email_field_1</td><td></td></tr><tr>
<td>' . esc_html__( 'Single Page Margin', 'vertycal' ) .'</td><td> vertycal_text_field_2</td><td></td></tr><tr>
<td>' . esc_html__( 'Limit User Data', 'vertycal' ) .'</td><td> vertycal_users_caps</td><td></td></tr><tr>
<td>' . esc_html__( 'Mark Complete Usage', 'vertycal' ) .'</td><td> vertycal_checkbox_markdone</td><td></td></tr><tr>
<td>' . esc_html__( 'Add Custom Dashboard Widget', 'vertycal' ) .'</td><td> vertycal_dashnews_widgetcheck</td><td></td></tr>
</tbody></table>
<hr>
<br>
<h4>' . esc_html__( 'Shortcode Reference', 'vertycal' ) .'</h4>
<table width="98%">
<tbody>
<tr>
<th>' . esc_html__( 'Shortcode to Use', 'vertycal' ) . '</th>
<th>' . esc_html__( 'Where to Use', 'vertycal' ) . '</th>
<th>' . esc_html__( 'Function or Page', 'vertycal' ) . '</th>
</tr>
<tr>
<td>[vertycal_dateinadmin&#93;</td>
<td><small>' . esc_html__( 'Displays the date on the user Dashboard page.', 'vertycal' ) .'</small></td>
<td>' . esc_html__( 'User Dashboard.', 'vertycal' ) .'</td>
</tr>
<tr><td> [vertycal_userip&#93; </td>
<td><small>' . esc_html__( 'Displays the logged in user&#39;s IP.', 'vertycal' ) . '</small></td>
<td>' . esc_html__( 'User Dashboard.', 'vertycal' ) . '</td>
</tr>
</tbody></table>
    <hr></div>';
    
    
    $html = ob_get_clean();
    
        echo $html;
} 

//callback for description of document section
function vertycal_docs_settings_section_callback()
{
    printf( '<h4>%s</h4>', 
        __( 'Plugin Help: ', 'vertycal' ) 
    );
}

//callback for description of options section
function vertycal_options_settings_section_callback() 
{
    printf( '<p><img src="%sprop/bezeledicon.png" alt="plugin logo" height="50"/>
    <small class="vrtcl-block">Ver. %s</small></p>',
    VERTYCAL_URL,
    VERTYCAL_VER 
);
    printf( '<p>%s <b class="vrttip active" data-title="Oh my you did it">?</b> %s</p>',
         esc_html__( 'For more information hover over the tips icon.', 'vertycal' ),
         esc_html__( 'Or view Instructions Tab referenced superset.', 'vertycal' )
    ); 
}

// display the plugin settings page
function vertycal_display_settings_page() 
{

	// check if user is allowed access
    if ( ! current_user_can( 'manage_options' ) ) return;
    
    $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'vertycal_options';
	?>

	<div id="vrtclWrap" class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <h2 class="nav-tab-wrapper">
    <a href="?post_type=vertycal&page=vertycal&tab=vertycal_options" 
       class="nav-tab <?php echo $active_tab == 'vertycal_options' ? 
       'nav-tab-active' : ''; ?>">
       <?php esc_html_e( 'General Options', 'vertycal' ); ?></a>
    <a href="?post_type=vertycal&page=vertycal&tab=vertycal_docs" 
       class="nav-tab <?php echo $active_tab == 'vertycal_docs' ? 
       'nav-tab-active' : ''; ?>">
       <?php esc_html_e( 'Instructions', 'vertycal' ); ?></a></h2>

	<?php
        if( $active_tab == 'vertycal_options' ) { 
        print( '<form action="options.php" method="post">' );

            // output security fields
			settings_fields( 'vertycal_options' );

			// output setting sections
            do_settings_sections( 'vertycal_options' );
            submit_button();
        
        print( '</form>' );            
        }
        else {  
            
            settings_fields( 'vertycal_docs' );

            do_settings_sections( 'vertycal_docs' ); 
			// no submit button
        } 
        ?>
	</div>

	<?php
} 
