<?php
class Support_Settings {
	
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wp_support_ap_menu' ) );
		add_action( 'admin_init',  array( $this, 'wp_support_save_settings' ) );
	}
	
	public function  wp_support_ap_options() {
		global $wpdb;
		$support_admin_email 					= get_option('support_admin_email');
		$support_admin_from_email				= get_option('support_admin_from_email');
		$ticket_sc_page 						= get_option('ticket_sc_page');
		$ticket_before_login_message_create 	= stripslashes(get_option('ticket_before_login_message_create'));
		$ticket_before_login_message_search 	= stripslashes(get_option('ticket_before_login_message_search'));
		$ticket_before_login_message_list 		= stripslashes(get_option('ticket_before_login_message_list'));
		
		echo '<div class="wrap">';
		$this->show_message();
		$this->wp_recommended_plugins_add();
		$this->help_support();
		Form_Class::form_open();
		wp_nonce_field( 'wp_support_save_action', 'wp_support_save_action_field' );
    	Form_Class::form_input('hidden','option','','wp_support_save_settings');
		include( WPST_PLUGIN_PATH . '/view/admin/settings.php');
		Form_Class::form_close();
		$this->support_ticket_pro_add();
		$this->donate();
		echo '</div>';
	}
	
	public function show_message(){
		if(isset($GLOBALS['msg'])){
			echo '<div class="updated notice notice-success"><p>'.$GLOBALS['msg'].'</p></div>';
		}
	}
	
	public function wp_recommended_plugins_add(){ 
		include( WPST_PLUGIN_PATH . '/view/admin/recommended-add.php');
	}
	
	public function support_ticket_pro_add(){
		include( WPST_PLUGIN_PATH . '/view/admin/pro-add.php');
	}
	
	public function donate(){
		include( WPST_PLUGIN_PATH . '/view/admin/donate.php');
	}
	
	public function help_support(){
		include( WPST_PLUGIN_PATH . '/view/admin/help.php');
	}
	
	
	public function wp_support_save_settings(){
		if(isset($_POST['option']) and $_POST['option'] == "wp_support_save_settings"){
			if ( ! isset( $_POST['wp_support_save_action_field'] ) || ! wp_verify_nonce( $_POST['wp_support_save_action_field'], 'wp_support_save_action' ) ) {
			   wp_die( 'Sorry, your nonce did not verify.');
			} 
			update_option( 'ticket_sc_page', sanitize_text_field($_POST['ticket_sc_page']) );
			update_option( 'support_admin_from_email', sanitize_text_field($_POST['support_admin_from_email']) );
			update_option( 'support_admin_email', sanitize_text_field($_POST['support_admin_email']) );
			update_option( 'ticket_before_login_message_create', esc_html($_POST['ticket_before_login_message_create']) );
			update_option( 'ticket_before_login_message_search', esc_html($_POST['ticket_before_login_message_search']) );
			update_option( 'ticket_before_login_message_list', esc_html($_POST['ticket_before_login_message_list']) );	
			$GLOBALS['msg'] = 'Data updated successfully.';
		}
	}
	
	public function wp_support_ap_menu () {
		add_menu_page( 'WP Support', 'WP Support', 'activate_plugins', 'wp_support_ap', array( $this, 'wp_support_ap_options' ));
	}
	
}
