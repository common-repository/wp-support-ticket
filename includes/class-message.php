<?php
if(!class_exists('Support_Message')){
	class Support_Message {
		public function __construct(){
			start_session_if_not_started();
		}
		
		public function show_message(){
			if(isset($_SESSION['add_ticket_msg']) and $_SESSION['add_ticket_msg']){
				echo '<p class="'.$_SESSION['add_ticket_msg_class'].'">'.$_SESSION['add_ticket_msg'].'</p>';
				unset($_SESSION['add_ticket_msg']);
				unset($_SESSION['add_ticket_msg_class']);
			}
		}
		
		public function add_message($msg = '', $class = ''){
			$_SESSION['add_ticket_msg'] = $msg;
			$_SESSION['add_ticket_msg_class'] = $class;		
		}
	}
}