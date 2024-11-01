<?php
if($data){
	foreach($data as $key => $value){
	?>
	<div class="reply-wrap reply-from-<?php echo $value->reply_from;?>">
		<div class="pull-left">
			<?php echo $rc->reply_user_avatar($value->user_id);?>
		</div>
		<div class="pull-right">
		<h4 class="media-heading"><?php echo $rc->get_reply_user_name($value->user_id);?>
			<small><?php echo $rc->reply_post_date($value->reply_added);?></small>
		</h4>
		<?php echo stripslashes($value->reply_msg);?>
		<div class="attachments"><?php $this->get_attachments($value->reply_id);?></div>
		</div>
	</div>
	<?php 
	} // end of foreach
} else { 
	_e(WPST_NO_REPLY_POSTED,'wp-support-ticket');
}