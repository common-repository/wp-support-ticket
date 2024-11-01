<?php
Form_Class::form_open('f','reply_ticket','post','','multipart/form-data');
wp_nonce_field( 'support_form_action', 'support_form_action_field' );
Form_Class::form_input('hidden','action','','add_reply');
Form_Class::form_input('hidden','ticket_id','',$id);
?>
  <div class="form-group">
    <label><?php _e('Message','wp-support-ticket');?></label>
    <?php Form_Class::form_textarea('ticket_body','','','form-control','','','','3','',true);?>
  </div>
  
  <div class="form-group">
    <label><?php _e('Attachment','wp-support-ticket');?></label>
    <?php Form_Class::form_input('file','safile','safile');?>
  </div>
<?php Form_Class::form_input('submit','submit','',__('Submit','wp-support-ticket'),'btn btn-default','','','','','',false,'');?>
<?php Form_Class::form_close();?>