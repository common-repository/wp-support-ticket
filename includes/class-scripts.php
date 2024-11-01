<?php
class WP_Support_Scripts {
	
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_support_ticket_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'wp_support_ticket_scripts_admin' ) );
	}
	
	public function wp_support_ticket_scripts_admin() {
		wp_enqueue_style( 'style-support-admin', plugins_url( WPST_PLUGIN_DIR . '/assets/style-support-admin.css' ) );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'ap.cookie', plugins_url( WPST_PLUGIN_DIR . '/js/ap.cookie.js' ) );
		wp_enqueue_script( 'ap-tabs', plugins_url( WPST_PLUGIN_DIR . '/js/ap-tabs.js' ) );
	}
	
	public function wp_support_ticket_scripts() {
		wp_enqueue_style( 'style-support', plugins_url( WPST_PLUGIN_DIR . '/assets/style-support.css' ) );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery.validate.min', plugins_url( WPST_PLUGIN_DIR . '/js/jquery.validate.min.js' ) );
		wp_enqueue_script( 'additional-methods', plugins_url( WPST_PLUGIN_DIR . '/js/additional-methods.js' ) );
	}
	
}
