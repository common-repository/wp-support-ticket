<?php if($a['title']){?>
<h2><?php echo $a['title']; ?> </h2>
<?php } ?>

<?php
Form_Class::form_open('f','ticket_create','post','','multipart/form-data');
wp_nonce_field( 'support_form_action', 'support_form_action_field' );
Form_Class::form_input('hidden','action','','add_ticket');
?>
  <div class="form-group">
    <label><?php _e('Subject','wp-support-ticket');?></label>
    <?php Form_Class::form_input('text','ticket_subject','ticket_subject','','form-control','','','','','',true,__('Ticket Title','wp-support-ticket'),'',true,__('Please enter subject','wp-support-ticket'));?>
  </div>
  <div class="form-group">
    <label><?php _e('Message','wp-support-ticket');?></label>
    <?php Form_Class::form_textarea('ticket_body','','','form-control','','','','','',true,'','',true,__('Please enter message','wp-support-ticket'));?>
  </div>
  
  <div class="form-group">
    <label><?php _e('Attachment','wp-support-ticket');?></label>
    <?php Form_Class::form_input('file','safile','safile');?>
  </div>
  
  <?php Form_Class::form_input('submit','submit','',__('Submit','wp-support-ticket'),'btn btn-default','','','','','',false,'');?>
  
<?php Form_Class::form_close();?>