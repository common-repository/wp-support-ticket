<?php
/*
Plugin Name: WP Support Ticket
Plugin URI: https://wordpress.org/plugins/wp-support-ticket/
Description: Wordpress user support plugin. Registered users will be able to create new support tickets and reply to already created support tickets. 
Version: 3.4.7
Domain Path: /languages
Text Domain: wp-support-ticket
Author: aviplugins.com
Author URI: https://www.aviplugins.com/
*/

/*
  |||||
<(`0_0`)>
()(afo)()
  ()-()
*/

define('WPST_PLUGIN_DIR', 'wp-support-ticket');
define('WPST_PLUGIN_PATH', dirname(__FILE__));

$ticket_status_array = array(1 => 'Open', 2 => 'Closed', 3 => 'Resolved');

include_once WPST_PLUGIN_PATH . '/config/config-supported-files.php';
include_once WPST_PLUGIN_PATH . '/config/config-default-messages.php';

function plug_load_wp_support_ticket() {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
    if (is_plugin_active('wp-support-pro/support.php')) {
        wp_die('It seems you have <strong>WP Support (PRO)</strong> plugin activated. Please deactivate to continue.');
        exit;
    }

    include_once WPST_PLUGIN_PATH . '/includes/class-settings.php';
    include_once WPST_PLUGIN_PATH . '/includes/class-scripts.php';
    include_once WPST_PLUGIN_PATH . '/includes/class-ticket.php';
    include_once WPST_PLUGIN_PATH . '/includes/class-ticket-meta.php';
    include_once WPST_PLUGIN_PATH . '/includes/class-support-data.php';
    include_once WPST_PLUGIN_PATH . '/includes/class-message.php';
    include_once WPST_PLUGIN_PATH . '/includes/class-reply.php';
    include_once WPST_PLUGIN_PATH . '/includes/class-notification.php';
    include_once WPST_PLUGIN_PATH . '/includes/class-form.php';

    include_once WPST_PLUGIN_PATH . '/functions.php';
    include_once WPST_PLUGIN_PATH . '/mod-functions.php';
    include_once WPST_PLUGIN_PATH . '/support-shortcodes.php';

    new Support_Settings;
    new WP_Support_Scripts;
    new Support_Data;
    new Support_Notification;

    new Create_Support_SC_Class;
    new Ticket_SC_Class;
}

class WP_Support_Ticket_Load {
    function __construct() {
        plug_load_wp_support_ticket();
    }
}
new WP_Support_Ticket_Load;

class WP_Support_Activate {

    static function wps_install() {
        global $wpdb;
        $create_table = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "support_reply` (
		  `reply_id` int(11) NOT NULL AUTO_INCREMENT,
		  `ticket_id` int(11) NOT NULL,
		  `user_id` int(11) NOT NULL,
		  `reply_from` ENUM( 'user', 'admin' ) NOT NULL,
		  `reply_msg` text NOT NULL,
		  `reply_added` datetime NOT NULL,
		  PRIMARY KEY (`reply_id`)
		)";
        $wpdb->query($create_table);

        $create_table1 = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "support_attachment` (
		  `att_id` int(11) NOT NULL AUTO_INCREMENT,
		  `reply_id` int(11) NOT NULL,
		  `att_file` varchar(255) NOT NULL,
		  PRIMARY KEY (`att_id`)
		)";
        $wpdb->query($create_table1);

        $create_table2 = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "support_reply_noti` (
		  `n_id` int(11) NOT NULL AUTO_INCREMENT,
		  `reply_id` int(11) NOT NULL,
		  `n_status` enum('Unread','Read') NOT NULL,
		  PRIMARY KEY (`n_id`)
		)";
        $wpdb->query($create_table2);

    }
    static function wps_uninstall() {}
}
register_activation_hook(__FILE__, array('WP_Support_Activate', 'wps_install'));
register_deactivation_hook(__FILE__, array('WP_Support_Activate', 'wps_uninstall'));

// actions 
add_action('post_edit_form_tag', 'update_edit_form_for_ticket_post');
add_action('plugins_loaded', 'wp_support_ticket_text_domain');
add_action('template_redirect', 'start_session_if_not_started');

if (is_admin()) {
    add_action('load-post.php', 'call_ticket_meta_class');
    add_action('load-post-new.php', 'call_ticket_meta_class');
    new Ticket_Class;
}

// mod actions
add_action('init', 'wp_support_do_rewrite');
add_action('init', 'custom_rewrite_tag', 10, 0);
add_filter('query_vars', 'add_wp_support_query_vars_filter');
