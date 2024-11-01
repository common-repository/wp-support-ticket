<?php
class Support_Data{
	
	public function __construct(){
 		add_action( 'init', array( $this, 'support_ticket_validate' ) );
	}
	
	public function support_ticket_validate(){
	
		if(isset($_REQUEST['action']) and sanitize_text_field($_REQUEST['action']) == 'add_ticket'){
			start_session_if_not_started();
			$error = false;
			$msg = '';
			$mc = new Support_Message;
			$ticket_subject = sanitize_text_field($_REQUEST['ticket_subject']);
			$ticket_body = esc_html($_REQUEST['ticket_body']);
			
			if ( ! isset( $_POST['support_form_action_field'] ) || ! wp_verify_nonce( $_POST['support_form_action_field'], 'support_form_action' ) ) {
			   wp_die( 'Sorry, your nonce did not verify.');
			} 
			
			if(!is_user_logged_in()){
				$error = true;
				$msg .= __(WPST_LOGIN_TO_CREATE_ST,'wp-support-ticket').'<br>';
			}
			if(!$ticket_subject){
				$error = true;
				$msg .= __(WPST_NO_SUBJECT_ST,'wp-support-ticket').'<br>';
			}
			if(!$ticket_body){
				$error = true;
				$msg .= __(WPST_NO_MESSAGE_ST,'wp-support-ticket').'<br>';
			}
			
			if(!$error){
				$data = array('ticket_subject' => $ticket_subject, 'ticket_body' => $ticket_body);
				$rc = new Support_Reply;
				$ret = $rc->insert_ticket($data);
				
				if(!$ret){
					$error = true;
					$msg = __(WPST_ERR_CREATE_ST,'wp-support-ticket');
					$mc->add_message($msg,'bg-danger');
				} else {
					$msg = __(WPST_CREATED_ST,'wp-support-ticket');
					$mc->add_message($msg,'bg-success');
				}
			} else {
				$mc->add_message($msg,'bg-danger');
			}
		}
		
		if(isset($_REQUEST['action']) and sanitize_text_field($_REQUEST['action']) == 'add_reply'){
			start_session_if_not_started();
			$error = false;
			$msg = '';
			$mc = new Support_Message;
			
			$ticket_body = esc_html($_REQUEST['ticket_body']);
			
			if ( ! isset( $_POST['support_form_action_field'] ) || ! wp_verify_nonce( $_POST['support_form_action_field'], 'support_form_action' ) ) {
			   wp_die( 'Sorry, your nonce did not verify.');
			} 
			
			if(!is_user_logged_in()){
				$error = true;
				$msg .= __(WPST_LOGIN_TO_CREATE_REPLY_ST,'wp-support-ticket').'<br>';
			}
			if(!$ticket_body){
				$error = true;
				$msg .= __(WPST_NO_MESSAGE_REPLY_ST,'wp-support-ticket').'<br>';
			}
			
			if(!$error){
				$data = array( 'ticket_body' => $ticket_body);
				$rc = new Support_Reply(sanitize_text_field($_REQUEST['ticket_id']));
				$ret = $rc->insert_ticket_reply($data);
				
				if(!$ret){
					$error = true;
					$msg = __(WPST_ERR_CREATE_REPLY_ST,'wp-support-ticket');
					$mc->add_message($msg,'bg-danger');
				} else {
					$msg = __(WPST_CREATED_REPLY_ST,'wp-support-ticket');
					$mc->add_message($msg,'bg-success');
				}
			} else {
				$mc->add_message($msg,'bg-danger');
			}
		}
	}
	
}