<?php
class Create_Support_SC_Class{
	
	public $ticket_before_login_message_create;
	
	public function __construct(){
		add_shortcode( 'create_support', array( $this, 'create_support_ticket_function' ) );
		$ticket_before_login_message_create = stripslashes(get_option('ticket_before_login_message_create'));
		if( empty($ticket_before_login_message_create) ){
			$this->ticket_before_login_message_create = __(WPST_LOGIN_TO_CREATE_ST,'wp-support-ticket');
		} else {
			$this->ticket_before_login_message_create = html_entity_decode($ticket_before_login_message_create);
		}
	}
	
	public function create_support_ticket_function( $atts ) {
		$a = shortcode_atts( array(
			'title' => '',
		), $atts );
		if(is_user_logged_in()){
			ob_start();
			$mc = new Support_Message;
			$mc->show_message();
			$this->load_script('ticket_create');
			include( WPST_PLUGIN_PATH . '/view/frontend/ticket-create.php');
			$ret = ob_get_clean();	
		} else {
			$ret = do_shortcode($this->ticket_before_login_message_create);	
		}
		return $ret;
	}
	
	public function load_script($fid = 'f'){?>
		<script type="text/javascript">
			jQuery(document).ready(function () {
				jQuery('#<?php echo $fid;?>').validate({ errorClass: "st-error" });
			});
		</script>
	<?php }
}

class Ticket_SC_Class{
	
	public $ticket_before_login_message_search;
	
	public $ticket_before_login_message_list;
	
	public function __construct(){
		add_shortcode( 'ticket_details', array( $this, 'ticket_details_function' ) );
		add_shortcode( 'ticket_list', array( $this, 'ticket_list_function' ) );
		add_shortcode( 'ticket', array( $this, 'ticket_function' ) );
		add_shortcode( 'ticket_search', array( $this, 'ticket_search_function' ) );
	}
	
	public function ticket_search_function( $atts ) {
		$a = shortcode_atts( array(
			'title' => '',
		), $atts );
		$ticket_before_login_message_search = stripslashes(get_option('ticket_before_login_message_search'));
		if( empty($ticket_before_login_message_search) ){
			$this->ticket_before_login_message_search = __(WPST_LOGIN_TO_SEARCH_ST,'wp-support-ticket');
		} else {
			$this->ticket_before_login_message_search = html_entity_decode($ticket_before_login_message_search);
		}
		if(is_user_logged_in()){
			ob_start();
			$rc = new Support_Reply();
			include( WPST_PLUGIN_PATH . '/view/frontend/ticket-search.php');
			$ret = ob_get_clean();	
		} else {
			$ret = do_shortcode($this->ticket_before_login_message_search);	
		}
		return $ret;
	}
	
	public function ticket_details_function( $atts ) {
		$a = shortcode_atts( array(
			'id' => '',
		), $atts );
		$id = $a['id'];
		if(!$id)
		return;
		
		$ticket_before_login_message_list = stripslashes(get_option('ticket_before_login_message_list'));
		
		if( empty($ticket_before_login_message_list) ){
			$this->ticket_before_login_message_list = __(WPST_LOGIN_TO_VIEW_ST,'wp-support-ticket');
		} else {
			$this->ticket_before_login_message_list = html_entity_decode($ticket_before_login_message_list);
		}
		
		if(is_user_logged_in()){
			ob_start();
			$ticket = get_post( $id );
			if(!$ticket){
				return;
			}
			if ( $ticket->post_author != get_current_user_id() )  {
				return;
			}
			global $ticket_status_array;
			$mc = new Support_Message;
			$mc->show_message();
			$this->load_script( 'reply_ticket' );
			$ticket_status = get_post_meta( $id, '_ticket_status', true );
			include( WPST_PLUGIN_PATH . '/view/frontend/ticket-status.php');
			include( WPST_PLUGIN_PATH . '/view/frontend/reply-add-form.php');
			$this->get_ticket_reply($id);
			$ret = ob_get_clean();	
		} else {
			$ret = do_shortcode($this->ticket_before_login_message_list);	
		}
		return $ret;
	}
	
	public function load_script($fid = 'f'){?>
		<script type="text/javascript">
			jQuery(document).ready(function () {
				jQuery('#<?php echo $fid;?>').validate({ errorClass: "st-error" });
			});
		</script>
	<?php }
	
	public static function tick_details_link($id){
		if(!$id){
			return;
		}
		
		if(get_option('permalink_structure') != ''){
			$link = get_permalink(get_option('ticket_sc_page')).'details/'.$id;
		} else {
			if(is_front_page()){
				$link = '#';
			} else {
				$link = get_permalink(get_option('ticket_sc_page')).'&view=details&supticket='.$id;
			}
		}
		return $link;
	}
	
	public function ticket_list_function( $atts ) {
		$a = shortcode_atts( array(
			'title' => '',
		), $atts );
		$title = $a['title'];
		$ticket_before_login_message_list = stripslashes(get_option('ticket_before_login_message_list'));
		if( empty($ticket_before_login_message_list) ){
			$this->ticket_before_login_message_list = __( WPST_LOGIN_TO_VIEW_ST, 'wp-support-ticket' );
		} else {
			$this->ticket_before_login_message_list = html_entity_decode($ticket_before_login_message_list);
		}
		if(is_user_logged_in()){
			ob_start();
			global $ticket_status_array;
			$rc = new Support_Reply;
			$data = $rc->get_user_tickets(get_current_user_id());
			include( WPST_PLUGIN_PATH . '/view/frontend/ticket-list.php');
			$big = 999999999;
			echo paginate_links( array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' => '?paged=%#%',
				'prev_text' => __('Previous','wp-support-ticket'),
				'next_text' => __('Next','wp-support-ticket'),
				'current' => max( 1, get_query_var('paged') ),
				'total' => $data->max_num_pages
			) );
			$ret = ob_get_clean();	
		} else {
			$ret = do_shortcode($this->ticket_before_login_message_list);	
		}
		return $ret;
	}
	
	public function get_ticket_reply($id = ''){
		if(!$id)
		return;
		$rc = new Support_Reply( $id );
		$data = $rc->get_reply_data();
		include( WPST_PLUGIN_PATH . '/view/frontend/ticket-replies.php');
		Support_Notification::update_reply_noti_user( $id );
	}
	
	public function get_attachments($reply_id){
		$rc = new Support_Reply;
		$data = $rc->get_attachments_data($reply_id);
		if($data){
			_e('Attachments:','wp-support-ticket');
			foreach($data as $key => $value){
				echo '<a href="'.$value->att_file.'" target="_blank"><img style="border:none;" src="'.plugins_url( WPST_PLUGIN_DIR . '/assets/attach.png' ).'"></a>';
			}
		}
	}
	
	public function ticket_function( $atts ) {
		$a = shortcode_atts( array(
			'title' => '',
		), $atts );
		
		$ticket = get_query_var( 'supticket' );
		
		ob_start();
		if(!$ticket){
			$ret = $this->get_ticket_list($a['title']);
		} else {
			$ret = $this->get_ticket_details($ticket);
		}
		$ret = ob_get_clean();	
		return $ret;
	}
	
	public function get_ticket_details($ticket = ''){
		echo do_shortcode('[ticket_details id="'.$ticket.'"]');
	}
	
	public function get_ticket_list($title = ''){
		echo do_shortcode('[ticket_list title="'.$title.'"]');
	}
	
}