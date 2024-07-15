<?php 
/**
 * Send Email Functions
 * @package vertycal
 * @subpackage admin/vertycal-send-email
 * @deprecated 1.0.1 Holding for updates in later version
 */
		
	/** Get from name */
	function vertycal_from_name() {
		$from_name = 'VertyCal Scheduler';
			return $from_name;
	}
	
	/** Get from e-mail address */
	function vertycal_from_address() {
		
		$from_email = esc_attr( $form['vertycal_from'] );
			return $from_email;
	}
	
	/** Get content type text/html or text */
	function vertycal_content_type() {
		return 'text/html';
	}
	/** Let's go send email procedure */
	function vertycal_send_email( $to, $subject, $message, 
					$headers = "Content-Type: text/html\r\n", $attachments = "" ) 
	{	
		// Hook filter
		add_filter( 'wp_mail_from', vertycal_from_address() );
		add_filter( 'wp_mail_from_name', vertycal_from_name() );
		add_filter( 'wp_mail_content_type', vertycal_content_type() );
		
		ob_start();
			
		wp_mail( $to, $subject, $message, $headers, $attachments );
		
		ob_end_clean();
		
		// Unhook
		remove_filter( 'wp_mail_from', vertycal_from_address() );
		remove_filter( 'wp_mail_from_name', vertycal_from_name() );
		remove_filter( 'wp_mail_content_type', vertycal_content_type() );
	} 
