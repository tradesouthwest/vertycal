<?php 
/**
 * @package vertycal
 * @subpackage vertycal/vertycal-init
 * 
 */
// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {	exit; }

/**
 * Admin Notice on Activation.
 * Notify admin to add new page
 * 
 * @since 1.0.1
 */
function vertycal_plugin_activation_notices()
{
    if( get_transient( 'vrtcl-admin-notice-startup' ) )
    { 
		$newpage = admin_url( 'post-new.php?post_type=page' );
    ?>
    <div class="updated notice is-dismissible">
        <br>
        <p class="vrtcl-activate" style="background:#f2f2a0"><?php esc_html_e( 'Thank you for adding this plugin! There are two requirements prior to using VertyCal: Please make a blank PAGE named ', 
            'vertycal' ); ?> 
        <strong><a href="<?php echo esc_url( $newpage ); ?>" 
                    title="<?php echo esc_html__( 'Scheduler', 'vertycal' ); ?>">
		<?php echo esc_html__( 'Scheduler', 'vertycal' ); ?></a></strong> <?php esc_html_e( 'And another for Viewing called', 'vertycal' ); ?> <strong><?php esc_html_e( 'Scheduled', 'vertycal' ); ?></strong>.</p>
        <br>
    </div>
    <?php delete_transient( 'vrtcl-admin-notice-startup' );
    }
} 
