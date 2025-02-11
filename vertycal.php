<?php
/**
 * -----------------------------------------------------------------------------
 * Plugin Name:  VertyCal
 * Description:  Scheduling based application to manage service calls or list schedules. Settings in Scheduler menu.
 * Plugin URI:   http://sunlandcomputers/vertycal/scheduler
 * Author:       Larry Judd
 * Author URI:   https://tradesouthwest.com
 * Version:      1.1.4
 * Requires PHP: 5.6
 * Requires CP:  2.4
 * Text Domain:  vertycal
 * Domain Path:  /languages
 * License:      GPLv2 or up
 * License URI:  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * -----------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.txt.
 * -----------------------------------------------------------------------------
 */
// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {	exit; }

/** 
 * Constants
 * 
 * @param VERTYCAL_VER         Using bumped ver.
 * @param VERTYCAL_URL         Base path
 * @param VERTYCAL_PLUGIN_PATH Directory path      
 */
if( !defined( 'VERTYCAL_VER' )) { define( 'VERTYCAL_VER', '1.1.4' ); }
if( !defined( 'VERTYCAL_URL' )) { define( 'VERTYCAL_URL', 
    plugin_dir_url(__FILE__)); }
if( !defined( 'VERTYCAL_PLUGIN_PATH' )) { define( 'VERTYCAL_PLUGIN_PATH', 
    plugin_dir_path( __FILE__ ) ); }

/**
 * Load custom post type first to avoid resetting permalinks after activate.
 * 
 * @param object 'vertycal' Custom Post Type
 */

    require_once ( VERTYCAL_PLUGIN_PATH . 'vertycal-cpt-init.php' );
    
    add_action( 'init', 'vertycal_custom_post_type_schedule', 1 ); //aka vertycal 
    add_action( 'init', 'vertycal_taxonomies_forcpt_vertycal', 0 );
   //add_action( 'contextual_help', 'vertycal_admin_contextual_help', 10, 3 );
    add_filter( 'post_updated_messages', 'vertycal_cpt_updated_messages' );

/**
 * (init) functions for plugin activation
 * 
 * Admin Notice on Activation.
 */
require_once ( plugin_dir_path(__FILE__) . 'vertycal-init.php' );

    // Start the plugin when it is loaded.
    register_activation_hook(   __FILE__, 'vertycal_plugin_activation' );
    register_deactivation_hook( __FILE__, 'vertycal_plugin_deactivation' );
    register_uninstall_hook(    __FILE__, 'uninstall' );
    add_action( 'after_switch_theme',     'vertycal_plugin_reactivate' );	
    add_action( 'admin_notices',          'vertycal_plugin_activation_notices' );

    if( is_multisite() ) : 
        add_action('network_admin_notices', 'vertycal_plugin_activation_notices' );
    endif;
    
/**
 * Activate/deactivate hooks
 * 
 */
function vertycal_plugin_activation() 
{
    flush_rewrite_rules();
    // Create transient data 
    set_transient( 'vrtcl-admin-notice-startup', true, 5 );
    return false;
}
function vertycal_plugin_deactivation() 
{
    return false;
}

/**
 * Reactivate plugin
 * 
 * @uses `after_switch_theme` Reflush after theme change
*/
function vertycal_plugin_reactivate() 
{ 
    // clean up any CPT cache
    flush_rewrite_rules();  
    return false;      
}

/**
 * Define the locale for this plugin for internationalization.
 * Set the domain and register the hook with WordPress.
 *
 * @uses slug `vertycal`
 */
add_action( 'plugins_loaded', 'vertycal_load_plugin_textdomain' );

function vertycal_load_plugin_textdomain() 
{

    $plugin_dir = basename( dirname(__FILE__) ) .'/languages';
                  load_plugin_textdomain( 'vertycal', false, $plugin_dir );
}

/**
 * Plugin Scripts
 *
 * Register and Enqueues plugin scripts
 *
 * @since 1.0.0
 * 
 * @uses wp_script_is | Boolean To determine if script is already enqueued.
 */
add_action( 'wp_enqueue_scripts', 'vertycal_plugin_public_scripts' ); 

function vertycal_plugin_public_scripts() 
{
    /*
     * Register Styles */
    // The plugin stylesheet 
    wp_enqueue_style( 'vertycal-plugin-style', 
                    VERTYCAL_URL . '/prop/css/vertycal-plugin-style.css', 
                        array(), VERTYCAL_VER, 
                        false 
                    );
    /* ClassicPress does not require jQuery!
    if( !wp_style_is( 'vertycal-jquery-ui', 'enqueue') ) : 
        //jquery ui for datepicker (if not already enqueued)
        wp_enqueue_style( 'vertycal-jquery-ui', 
                        VERTYCAL_URL . 'prop/css/jquery-ui.css', 
                        array(), VERTYCAL_VER, 
                        false 
                    );     
    endif;
    */
    /* 
     * Register Enqueue Scripts */ 
    //vrtcl-tabs on page - no script
    wp_enqueue_script( 'vertycal-vtabs', 
                        plugins_url( 'prop/js/vertycal-vtabs.js', 
                        __FILE__ ), 
                        array(), 
                        VERTYCAL_VER,
                        true );
    /* ClassicPress does not require jQuery!
    if( !wp_script_is( 'jquery-ui-datepicker', 'enqueued' ) ) : 
        //datepicker public 
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-datepicker' ); 
        wp_enqueue_script( 'vertycal-public', 
                            plugins_url( 'prop/js/vertycal-public.js', 
                            __FILE__ ), 
                            array( 
                                'jquery','jquery-ui-core', 
                                'jquery-ui-datepicker' ), 
                                VERTYCAL_VER,
                            false 
                        ); 
    endif;
    */
}

/** 
 * Admin side specific
 *
 * Top level scripts
 * See readme.txt @see Installation [5.] for datepicker transaltion locales.
 * Enqueue admin only scripts 
 */ 
function vertycal_load_admin_scripts() 
{
    /*
     * Enqueue styles */
    wp_enqueue_style( 'vertycal-admin', 
                    VERTYCAL_URL . 'prop/vertycal-admin-style.css', 
                    array(), VERTYCAL_VER, false 
                    );
    /* ClassicPress does not require jQuery!
    wp_enqueue_style( 'vertycal-jquery-ui', 
                    VERTYCAL_URL . 'prop/css/jquery-ui.css', 
                    array(), VERTYCAL_VER, false 
                    ); 
    */
    //wp_enqueue_style( 'wp-color-picker' ); 

    /*
     * Register Scripts */
    //runs datepicker reqmnts
    /* ClassicPress does not require jQuery!
    wp_register_script( 'vertycal-plugin', 
                        plugins_url( 'prop/js/vertycal-plugin.js', 
                        __FILE__ ), 
                            array( 
                                'jquery', 
                                'jquery-ui-core', 
                                'jquery-ui-datepicker' 
                                ), true 
                        );  
    */
    //color
    /* ClassicPress does not require jQuery!
    wp_register_script( 'vertycal-colors',                         
                        plugins_url( 'prop/js/vertycal-colors.js', 
                        __FILE__ ),
                            array( 
                                'jquery', 
                                'wp-color-picker' 
                            ), true 
                        ); 

    wp_enqueue_script( 'wp-color-picker');
    wp_enqueue_script( 'vertycal-colors');
    wp_enqueue_script( 'vertycal-plugin' ); 
    */
}
    add_action( 'admin_enqueue_scripts', 'vertycal_load_admin_scripts' );      

/** 
 * @since 1.0.1
 * Load metaboxes for datepicker @see admin/vertical-metaboxes
 * Register Save Post action     @see admin/vertical-metaboxes
 * Add notation to Title field   @see admin/vertical-metaboxes
 */
    add_action( 'add_meta_boxes', 'vertycal_date_time_meta_box' );
    add_action( 'save_post',      'vertycal_update_date_time_meta', 10, 2 ); 
    add_action( 'edit_form_after_title', 'vertycal_admin_inline_customize', 10, 1 );

/**
 * Register_meta_boxes and hooks from metaboxes
 * 
 * @since 1.0.0
 */
require_once( plugin_dir_path(__FILE__) . 'admin/vertycal-metaboxes.php' );

/**
 * Load settings page in admin 
 * 
 * @since 1.0.0
 */
require_once( plugin_dir_path(__FILE__) . 'admin/vertycal-settings-page.php' );

/*
 * Load hooks init in admin 
 * 
 * Some options commented
 */
    add_filter( 'manage_edit-vertycal_columns', 'vertycal_edit_scheduled_columns' );
    
    // Changes excerpt editor context.
    add_filter( 'gettext',           'vertycal_editor_gettext', 10, 3 ); 

    // load columns settings in admin (edit)
    add_filter( 'manage_edit-vertycal_sortable_columns', 
                   'vertycal_vertycal_sortable_columns' );
    add_action( 'manage_vertycal_posts_custom_column',   
                   'vertycal_manage_scheduled_columns', 10, 2 );
    
    add_action('admin_head',          'vertycal_adminpage_custom_colors');
    add_action( 'wp_dashboard_setup', 'vertycal_remove_dashboard_meta' );  
    add_action( 'wp_dashboard_setup', 'vertycal_add_custom_dashboard_widgets' ); 
    //add_action( 'pre_get_posts',    'vertycal_maybe_remove_wpautop' ); 
    /*
     * Dashboard hooks only fire if option is set */
    if( function_exists( 'vertycal_run_manager_caps' ) ) : 
          
        add_action( 'plugins_loaded', 'vertycal_run_manager_caps' ); 
    endif;
/**
 * File with above filters and hooks
 * @since 1.0.0
 */
require_once ( plugin_dir_path(__FILE__) . 'admin/vertycal-admin.php' );

/**
 * Load hooks init for templates
 * 
 * @since 1.0.0
 * 
 * @subpackage inc/vertycal-template-actions
 */
add_action( 'vertycal_before_scheduler_list', 'vertycal_before_scheduler_list_render', 10, 1 );
add_action( 'vertycal_before_scheduler_tabs', 'vertycal_before_scheduler_tabs_render', 10, 1 );

    if( function_exists( 'vertycal_pagination_schedule' ) ) : 

        add_action( 'vertycal_tmplt_pagination_schedule', 'vertycal_pagination_schedule' );
    endif;

require_once ( plugin_dir_path(__FILE__) . 'inc/vertycal-template-actions.php' );
require_once ( plugin_dir_path(__FILE__) . 'inc/vertycal-helpers.php' );

/**
 * Add pertinent functions | All hooks from inc/helpers file
 * 
 * @since 1.0.0
 * 
 */
// Add shortcodes for dashboard
function vertycal_register_shortcodes() 
{   
    add_shortcode( 'vertycal_userip', 'vertycal_display_user_ip' ); 
    add_shortcode( 'vertycal_dateinadmin','vertycal_display_dateinadmin_shortcode');
} 

/**
 * Add pertinent functions | Hooks from inc/functions file
 * 
 * @since 1.0.0
 */
    // Inline scripts
    add_action( 'wp_enqueue_scripts', 'vertycal_inline_public_styles' ); 

    // Register shortcodes
    add_action( 'init', 'vertycal_register_shortcodes' ); 
    
    // Templates
    add_filter( 'page_template',     'vertycal_wp_page_template' );
    add_filter( 'taxonomy_template', 'vertycal_wp_tax_template' );
    add_filter( 'single_template',   'vertycal_custom_single_template' );
    add_filter( 'body_class',        'vertycal_prefix_scheduler_body_class' );

require_once ( plugin_dir_path(__FILE__) . 'inc/vertycal-functions.php' );
?>