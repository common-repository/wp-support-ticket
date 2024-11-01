<?php
class Support_Reply {
	
	private $ticket_id;
	
	public function __construct($ticket_id=''){
		$this->ticket_id = $ticket_id;
	}
	
	public function get_user_tickets($user_id = ''){
		global $wpdb;
		if(!$user_id){
			return;
		}
		
		$st_title = sanitize_text_field( get_query_var( 'st_title' ) );
		
		$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
		$args = array(
		'post_type' => 'ticket',
		'posts_per_page' => 10,
		'post_status' => 'publish',
		'author' => $user_id,
		'paged' => $paged,
		);
		
		if($st_title){
			$args['s'] = $st_title;
		}
		
		$tickets = new WP_Query( $args );
		return $tickets;
		
	}
	
	public function ticket_status_selected($sel = ''){
		global $ticket_status_array;
		$ret = '';
		if(is_array($ticket_status_array)){
			foreach($ticket_status_array as $key => $value){
				if($key == $sel){
					$ret .= '<option value="'.$key.'" selected="selected">'.$value.'</option>';
				} else {
					$ret .= '<option value="'.$key.'">'.$value.'</option>';
				}
			}
			return $ret;
		}
	}
	
	public function get_reply_data(){
		global $wpdb;
		if(!$this->ticket_id)
		return;
		
		$query = $wpdb->prepare( "SELECT * FROM `".$wpdb->prefix."support_reply` WHERE ticket_id = %d ORDER BY `reply_added` DESC", $this->ticket_id );
		$results = $wpdb->get_results( $query, OBJECT );
		if($results){
			return $results;
		} else {
			return;
		}
		
	}
	
	public function get_tickets_data(){
		global $wpdb;
		if(!$this->ticket_id)
		return;
		
		$query = $wpdb->prepare( "SELECT * FROM `".$wpdb->prefix."support_reply` WHERE ticket_id = %d ORDER BY `reply_added` DESC", $this->ticket_id );
		$results = $wpdb->get_results( $query, OBJECT );
		if($results){
			return $results;
		} else {
			return;
		}
		
	}
	
	public function get_attachments_data($reply_id){
		global $wpdb;
		if(!$reply_id)
		return;
		
		$query = $wpdb->prepare( "SELECT * FROM `".$wpdb->prefix."support_attachment` WHERE reply_id = %d", $reply_id );
		$results = $wpdb->get_results( $query, OBJECT );
		if($results){
			return $results;
		} else {
			return;
		}
		
	}
	
	public function get_ticket_author($post_id = ''){
		if(!$post_id){
			return 'NA';
		}
		
		$post = get_post( $post_id );
		$user_info = get_userdata($post->post_author);
		if( $user_info ){
			return $user_info->display_name . ' (<a href="mailto:'.$user_info->user_email.'">'.$user_info->user_email.'</a>)';
		} else {
			return 'NA';
		}
	}
	
	public function get_reply_user_name($user_id = ''){
		if(!$user_id){
			return 'NA';
		}
		
		$user_info = get_userdata($user_id);
		if( $user_info ){
			return $user_info->display_name; 
		} else {
			return 'NA';
		}
	}
	
	
	public function reply_post_date($date){
		$date = strtotime($date);
		$date = date("F j, Y - g:i A",$date);
		return $date;
	}
	
	public function insert_ticket($data = array()){
		start_session_if_not_started();
		global $wpdb;
		$movefile = array();
		
		// add ticket //
		$post = array(
		  'post_title'     => $data['ticket_subject'],
		  'post_status'    => 'publish',
		  'post_type'      => 'ticket',
		  'post_author'    => get_current_user_id(),
		  'post_date'      => current_time( 'mysql' ),
		);  
		$new_ticket_id = wp_insert_post( $post ); 
		
		if(!$new_ticket_id){
			return false;
		}
		
		update_post_meta( $new_ticket_id, '_ticket_status', 1 );
		// add ticket //
		
		// add reply //
		$reply_data = array(
			'ticket_id' => $new_ticket_id,
			'user_id' => get_current_user_id(),
			'reply_from' => 'user', // for user 
			'reply_msg' => $data['ticket_body'],
			'reply_added' => current_time( 'mysql' )
		);
		$data_type = array( 
			'%d', 
			'%d', 
			'%s',
			'%s',
			'%s',
		);
		
		$wpdb->insert( $wpdb->prefix."support_reply", $reply_data, $data_type ); 
		$reply_id = $wpdb->insert_id;
		
		update_post_meta( $new_ticket_id, 'last_post_date', current_time( 'mysql' ) );
		// add reply //
		
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
			if (isset($movefile['file']) and file_exists($movefile['file'])) {
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
		// admin email // 
		$user_info = get_userdata(get_current_user_id());
		$headers = 'From: '.$user_info->user_login.' <'.$user_info->user_email.'>' . "\r\n";
		$message = '';
		$message .= __('A new ticket is created.','wp-support-ticket') . "\r\n\r\n";
		$message .= __('Message:','wp-support-ticket').$data['ticket_body']. "\r\n\r\n";
		wp_mail(get_option('support_admin_email'), $data['ticket_subject'], $message, $headers);
		
		// user email //
		$headers1 = 'From: '.get_bloginfo('name').' <'.get_option('support_admin_from_email').'>' . "\r\n";
		$message1 = '';
		$message1 .= __('Hello,','wp-support-ticket') . "\r\n";
		$message1 .= $user_info->user_login . "\r\n\r\n";
		$message1 .= __('Your new support ticket is successfully added.','wp-support-ticket') . "\r\n\r\n";
		$message1 .= __('Thank You','wp-support-ticket') . "\r\n\r\n";
		wp_mail($user_info->user_email, __('New support ticket','wp-support-ticket'), $message1, $headers1);
		// emails //
		
		return true;
	}
	
	public function insert_ticket_reply($data = array()){
		start_session_if_not_started();
		global $wpdb;
		if(!$this->ticket_id){
			return false;
		}
		// update ticket //
		update_post_meta( $this->ticket_id, '_ticket_status', 1 );
		// update ticket //
		
		// add reply //
		$reply_data = array(
			'ticket_id' => $this->ticket_id,
			'user_id' => get_current_user_id(),
			'reply_from' => 'user', // for user 
			'reply_msg' => $data['ticket_body'],
			'reply_added' => current_time( 'mysql' )
		);
		$data_type = array( 
			'%d', 
			'%d', 
			'%s', 
			'%s', 
			'%s', 
		);
				
		$wpdb->insert( $wpdb->prefix."support_reply", $reply_data, $data_type ); 
		$reply_id = $wpdb->insert_id;
		
		update_post_meta( $this->ticket_id, 'last_post_date', current_time( 'mysql' ) );
		// add reply //
		
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
			if ( !empty($movefile['file']) and file_exists($movefile['file'])) {
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
		// admin email //
		$message = ''; 
		$user_info = get_userdata(get_current_user_id());
		$headers = 'From: '.$user_info->user_login.' <'.$user_info->user_email.'>' . "\r\n";
		$message .= __('A new ticket reply is posted on ','wp-support-ticket'). get_the_title( $this->ticket_id ) . "\r\n\r\n";
		$message .= __('Message:','wp-support-ticket').$data['ticket_body']. "\r\n\r\n";
		wp_mail(get_option('support_admin_email'), __('New reply added','wp-support-ticket'), $message, $headers);
		// emails //
		
		return true;
	}
	
	public function reply_user_avatar($user_id){
		$default = '<img alt="anonymous" src="http://placehold.it/64x64">';
		if($user_id == ''){
			return $default;
		}
		if($user_id == 0){
			return $default;
		}
		
		$img = get_avatar( $user_id, 64 );
		if($img){
			return $img;
		} else {
			return $default;
		}
	}
	
	public function last_post_by($post_id = ''){
		global $wpdb;
		if(!$post_id){
			return 'NA';
		}
		$query = $wpdb->prepare( "SELECT * FROM `".$wpdb->prefix."support_reply` WHERE ticket_id = %d ORDER BY `reply_added` DESC limit 1", $post_id );
		$result = $wpdb->get_row( $query, OBJECT );
		
		if($result->user_id == ''){
			return 'NA';
		}
		
		$user_info = get_userdata($result->user_id);
		
		if( empty($user_info) ){
			return 'NA';
		}
		
		return $user_info->display_name;	
		
	}
	
	public function last_post_date($post_id = ''){
		global $wpdb;
		if(!$post_id){
			return 'NA';
		}
		
		$query = $wpdb->prepare( "SELECT * FROM `".$wpdb->prefix."support_reply` WHERE ticket_id = %d ORDER BY `reply_added` DESC limit 1", $post_id );
		$result = $wpdb->get_row( $query, OBJECT );
		
		if(is_object($result)){
			$date = $this->reply_post_date($result->reply_added);
			return $date;	
		}
	}
	
}