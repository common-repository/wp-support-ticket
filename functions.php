<?php

function call_ticket_meta_class() {
    new Ticket_Meta_Class();
}

function support_attachment_upload_filter( $file ){
	$file['name'] = time() . $file['name'];
	return $file;
}

function update_edit_form_for_ticket_post() {
	echo ' enctype="multipart/form-data"';
}

function wp_support_ticket_text_domain(){
	load_plugin_textdomain('wp-support-ticket', FALSE, basename( dirname( __FILE__ ) ) .'/languages');
}

if(!function_exists( 'start_session_if_not_started' )){
	function start_session_if_not_started(){
		if(!session_id()){
			@session_start();
		}
	}
}