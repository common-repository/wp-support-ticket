<?php
class Support_Notification {
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'new_support_reply_noti') );
		add_action( 'wp_head', array( $this, 'new_support_reply_noti_front') );
		add_action( 'init', array( $this, 'new_support_reply_noti_front_clean') );
		add_action( 'admin_init', array( $this, 'new_support_reply_noti_clean') );
	}
	
	public static function curPageURL() {
		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		if (isset($_SERVER["SERVER_PORT"]) and $_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	
	/* admin */ 
	
	public static function update_reply_noti( $ticket_id = '' ){
		if(  $ticket_id == '' ) return;
		global $wpdb;
		$query = $wpdb->prepare( "UPDATE `".$wpdb->prefix."support_reply_noti` SET n_status = '%s' WHERE reply_id IN( SELECT reply_id FROM `".$wpdb->prefix."support_reply` WHERE ticket_id = '%d' AND reply_from = '%s')", 'Read', $ticket_id, 'user' );
		$wpdb->query($query);
		return;		
	}
	
	public function is_new_reply_posted_admin(){
		global $wpdb;
		$query = $wpdb->prepare( "SELECT sr.ticket_id, COUNT( rn.reply_id ) AS noti_count FROM `".$wpdb->prefix."support_reply` AS sr, `".$wpdb->prefix."support_reply_noti` AS rn WHERE sr.reply_id = rn.reply_id AND sr.reply_from = '%s' AND rn.n_status = '%s' GROUP BY sr.ticket_id", 'user', 'Unread' );
		$result = $wpdb->get_results( $query, OBJECT );	
		return $result;
	}
	
	public function new_support_reply_noti(){
		$msg = '';
		
		if(!is_admin()) return;
		if(!is_user_logged_in()) return;
		
		$user_id = get_current_user_id();
		$user_meta = get_userdata($user_id);
		$user_roles = $user_meta->roles;
		$roles = array('administrator');
		if( is_array($roles) and !in_array( $user_roles[0], $roles) ){
			return;
		}
		
		$data = $this->is_new_reply_posted_admin();
		
		if( is_array($data) ){
			foreach( $data as $key => $value ){
				$msg .= '<p>Ticket <a href="post.php?post='.$value->ticket_id.'&action=edit">'.get_the_title( $value->ticket_id ).'</a> has '.$value->noti_count.' new message(s)</p>';
			}
		}
		if( $msg ){
			$clear_all_link = '<div class="support-notice-clear"><a href="'.wp_nonce_url( 'options-general.php?page=wp_support_ap&action=support_clear_noti_admin&redirect='.$this->curPageURL(), 'remove_all_noti', 'sup_nonce' ).'">'.__('Dismiss','wp-support-ticket').'</a></div>';
			
			echo '<div class="notice notice-success">'.$clear_all_link.$msg.'</div>';
		}
	}
	
	public function new_support_reply_noti_clean(){
		if(isset($_REQUEST['action']) and sanitize_text_field($_REQUEST['action']) == 'support_clear_noti_admin'){
			if(!is_admin()) return;
			if(!is_user_logged_in()) return;
			if(!wp_verify_nonce($_REQUEST['sup_nonce'],'remove_all_noti')) return;
			if(!current_user_can('activate_plugins')) return;
			
			$user_id = get_current_user_id();
			
			global $wpdb;
			$query = $wpdb->prepare( "UPDATE `".$wpdb->prefix."support_reply_noti` SET n_status = '%s' WHERE reply_id IN( SELECT reply_id FROM `".$wpdb->prefix."support_reply` as sr WHERE sr.reply_from = '%s')", 'Read', 'user' );
			$wpdb->query($query);
			
			wp_redirect( sanitize_text_field($_REQUEST['redirect']) );
			exit;
		}
	}
	
	/* admin */ 
	
	/* frontend */ 
	
	public static function update_reply_noti_user( $ticket_id = '' ){
		if(  $ticket_id == '' ) return;
		global $wpdb;
		$query = $wpdb->prepare( "UPDATE `".$wpdb->prefix."support_reply_noti` SET n_status = '%s' WHERE reply_id IN( SELECT reply_id FROM `".$wpdb->prefix."support_reply` WHERE ticket_id = '%d' AND reply_from = '%s')", 'Read', $ticket_id, 'admin' );
		$wpdb->query($query);
		return;		
	}
	
	public function is_new_reply_posted_user( $user_id = '' ){
		global $wpdb;
		$query = $wpdb->prepare( "SELECT sr.ticket_id, COUNT( rn.reply_id ) AS noti_count FROM `".$wpdb->prefix."support_reply` AS sr, `".$wpdb->prefix."support_reply_noti` AS rn, `".$wpdb->prefix."posts` as p WHERE sr.reply_id = rn.reply_id AND sr.reply_from = '%s' AND rn.n_status = '%s' AND p.post_author = '%d' AND p.ID = sr.ticket_id GROUP BY sr.ticket_id", 'admin', 'Unread', $user_id );
		$result = $wpdb->get_results( $query, OBJECT );	
		return $result;
	}
	
	public function new_support_reply_noti_front(){
		$msg = '';
		
		if(!is_user_logged_in()) return;
		
		$user_id = get_current_user_id();
		$data = $this->is_new_reply_posted_user( $user_id );
		
		if( is_array($data) ){
			foreach( $data as $key => $value ){
				$msg .= '<p>Ticket <a href="'.Ticket_SC_Class::tick_details_link( $value->ticket_id ).'">'.get_the_title( $value->ticket_id ).'</a> has '.$value->noti_count.' new message(s)</p>';
			}
		}
		if( $msg ){
			$clear_all_link = '<div class="support-notice-clear"><a href="'.wp_nonce_url( site_url( '?action=support_clear_noti' ), 'remove_all_noti', 'sup_nonce' ).'"><img src="'.plugins_url( WPST_PLUGIN_DIR . '/images/dismiss.png' ).'" alt="Dismiss"></a> <a href="javascript:void(0);" onclick="HideNotiForNow()"><img src="'.plugins_url( WPST_PLUGIN_DIR . '/images/hide.png' ).'" alt="Hide"></a></div>';
			$noti = '<div class="support-notice">'.$clear_all_link.$msg.'</div>';
			?>
            <script>
				jQuery(document).ready(function(){jQuery('body').prepend('<?php echo $noti;?>');});
				function HideNotiForNow(){jQuery('.support-notice').slideUp();}
			</script>
            <?php
		}
	}
	
	public function new_support_reply_noti_front_clean(){
		if(isset($_REQUEST['action']) and sanitize_text_field($_REQUEST['action']) == 'support_clear_noti'){
			if(!is_user_logged_in()) return;
			if(!wp_verify_nonce( $_REQUEST['sup_nonce'], 'remove_all_noti' )) return;
			
			$user_id = get_current_user_id();
		
			global $wpdb;
			$query = $wpdb->prepare( "UPDATE `".$wpdb->prefix."support_reply_noti` SET n_status = '%s' WHERE reply_id IN( SELECT reply_id FROM `".$wpdb->prefix."support_reply` as sr, `".$wpdb->prefix."posts` as p WHERE sr.ticket_id = p.ID AND p.post_author = '%d' AND sr.reply_from = '%s')", 'Read', $user_id, 'admin' );
			$wpdb->query($query);
			return;		
		}
	}
	
	/* frontend */ 
}


