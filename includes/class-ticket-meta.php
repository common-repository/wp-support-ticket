<?php

class Ticket_Meta_Class {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'ticket_other_fields' ) );
		add_action( 'add_meta_boxes', array( $this, 'ticket_author_fields' ) );
		add_action( 'add_meta_boxes', array( $this, 'ticket_reply_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'ticket_posts_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}
	
	public function ticket_other_fields( $post_type ) {
			$post_types = array('ticket');  
			if ( in_array( $post_type, $post_types )) {
				add_meta_box(
					'ticket_other_fields'
					,__( 'Status', 'wp-support-ticket' )
					,array( $this, 'render_ticket_other_fields' )
					,$post_type
					,'side'
					,'high'
				);
			}
	}
	
	public function ticket_author_fields( $post_type ) {
			$post_types = array('ticket');  
			if ( in_array( $post_type, $post_types )) {
				add_meta_box(
					'ticket_author_fields'
					,__( 'Ticket Created by', 'wp-support-ticket' )
					,array( $this, 'render_ticket_author_fields' )
					,$post_type
					,'side'
					,'high'
				);
			}
	}
	
	public function ticket_posts_box( $post_type ) {
			$post_types = array('ticket');  
			if ( in_array( $post_type, $post_types )) {
				add_meta_box(
					'ticket_posts_box'
					,__( 'Posts', 'wp-support-ticket' )
					,array( $this, 'render_ticket_posts_box' )
					,$post_type
					,'advanced'
					,'high'
				);
			}
	}
	
	public function ticket_reply_box( $post_type ) {
			$post_types = array('ticket');  
			if ( in_array( $post_type, $post_types )) {
				add_meta_box(
					'ticket_reply_box'
					,__( 'Reply', 'wp-support-ticket' )
					,array( $this, 'render_ticket_reply_box' )
					,$post_type
					,'advanced'
					,'high'
				);
			}
	}

	public function save( $post_id ) {
		global $wpdb;
		if ( ! isset( $_POST['wpt_inner_custom_box_ticket_nonce'] ) )
			return $post_id;

		$nonce = sanitize_text_field($_POST['wpt_inner_custom_box_ticket_nonce']);

		if ( ! wp_verify_nonce( $nonce, 'wpt_inner_custom_box_ticket' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}
		
		$ticket_status = sanitize_text_field($_REQUEST['ticket_status']);
		update_post_meta( $post_id, '_ticket_status', $ticket_status );
		
		$reply_msg =  esc_html($_POST['reply_msg']);
		if($reply_msg){
			$data = array(
				'ticket_id' => $post_id,
				'user_id' => get_current_user_id(), 
				'reply_from' => 'admin', // for admin 
				'reply_msg' => $reply_msg,
				'reply_added' => current_time( 'mysql' )
			);
			$data_type = array( 
				'%d', 
				'%d', 
				'%s',
				'%s', 
				'%s',  
			);
			$wpdb->insert( $wpdb->prefix."support_reply", $data, $data_type ); 
			$reply_id = $wpdb->insert_id;
			
			update_post_meta( $post_id, 'last_post_date', current_time( 'mysql' ) );
			
			// add reply noti //
			$noti_data = array(
				'reply_id' => $reply_id,
				'n_status' => 'Unread',
			);
			$noti_type = array( 
				'%d', 
				'%s', 
			);
			
			$wpdb->insert( $wpdb->prefix."support_reply_noti", $noti_data, $noti_type ); 
			$n_id = $wpdb->insert_id;
			// add reply noti //
		}
		
		
		// add attachments //
		
		
		if(isset($_FILES["safile"])){	
			global $supported_files_array;
			if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
			$uploadedfile = $_FILES['safile'];
			$upload_overrides = array( 'test_form' => false );
			
			add_filter('wp_handle_upload_prefilter', 'support_attachment_upload_filter' );
			
			$arr_file_type = wp_check_filetype($uploadedfile['name']);
			$uploaded_type = $arr_file_type['type'];
			
			// Check if the type is supported. If not, throw an error.
			if(in_array($uploaded_type, $supported_files_array)) {
				$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
			}
		 }
			 
		if($reply_id){
			if (file_exists($movefile['file'])) {
				$att_data = array(
				'reply_id' => $reply_id,
				'att_file' => $movefile['url']
				);
				$data_type = array( 
					'%d', 
					'%s', 
				);
				$wpdb->insert( $wpdb->prefix."support_attachment", $att_data, $data_type ); 
			}
		}
		// add attachments //
		
		// emails //
		// user email // 
		$post_author_id = get_post_field( 'post_author', $post_id );
		$user_info = get_userdata($post_author_id);
		$headers1 = 'From: '.get_bloginfo('name').' <'.get_option('support_admin_from_email').'>' . "\r\n";
		$message1 .= __('Hello,','wp-support-ticket') . "\r\n\r\n";
		$message1 .= __('A new ticket reply is posted on ','wp-support-ticket'). get_the_title( $post_id ) . "\r\n\r\n";
		$message1 .= __('Message:','wp-support-ticket').$_REQUEST['reply_msg']. "\r\n\r\n";
		$message1 .= __('Thank You','wp-support-ticket'). "\r\n\r\n";
		wp_mail($user_info->user_email, __('New ticket reply','wp-support-ticket'), $message1, $headers1);
		// emails //
		
	}
	
	public function render_ticket_other_fields( $post ) {
		wp_nonce_field( 'wpt_inner_custom_box_ticket', 'wpt_inner_custom_box_ticket_nonce' );
		$ticket_status = get_post_meta( $post->ID, '_ticket_status', true );
		$rc = new Support_Reply();
		include( WPST_PLUGIN_PATH . '/view/admin/ticket-status-form.php');
	}
	
	public function render_ticket_author_fields( $post ) {
		wp_nonce_field( 'wpt_inner_custom_box_ticket', 'wpt_inner_custom_box_ticket_nonce' );
		$rc = new Support_Reply();
		include( WPST_PLUGIN_PATH . '/view/admin/ticket-author-form.php');
	}
	
	public function render_ticket_posts_box( $post ) {
		$rc = new Support_Reply( $post->ID );
		$data = $rc->get_reply_data();
		include( WPST_PLUGIN_PATH . '/view/admin/ticket-reply-view.php');
		Support_Notification::update_reply_noti( $post->ID );
	}
	
	public function get_attachments($reply_id){
		$rc = new Support_Reply;
		$data = $rc->get_attachments_data($reply_id);
		if($data){
			_e('Attachments:','wp-support-ticket');
			foreach($data as $key => $value){
				echo '<a href="'.$value->att_file.'" target="_blank"><img border="0" src="'.plugins_url( WPST_PLUGIN_DIR . '/assets/attach.png' ).'"></a>';
			}
		}
	}
	
	public function render_ticket_reply_box( $post ) {
		wp_nonce_field( 'wpt_inner_custom_box_ticket', 'wpt_inner_custom_box_ticket_nonce' );
		$ticket_status = get_post_meta( $post->ID, '_ticket_status', true );
		include( WPST_PLUGIN_PATH . '/view/admin/ticket-reply-form.php');
	}
	
}