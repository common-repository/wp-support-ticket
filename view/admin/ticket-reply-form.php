<table width="100%" border="0">
  <tr>
    <td>
    <?php Form_Class::form_textarea('reply_msg','','','','','','','','','','','width:100%; height:200px;');?>
    </td>
  </tr>
  <tr>
    <td><strong><?php _e('Attachment','wp-support-ticket');?></strong></td>
  </tr>
  <tr>
    <td>
        <?php Form_Class::form_input('file','safile','safile');?>
    </td>
  </tr>
</table>