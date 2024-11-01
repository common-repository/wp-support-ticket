<?php if($a['title']){?>
<h2><?php echo $a['title']; ?></h2>
<?php } ?>

<?php
Form_Class::form_open('search','','get',get_permalink(get_option('ticket_sc_page')));
Form_Class::form_input('hidden','action','','srch_ticket');
?>
<table width="100%" border="0">
    <tbody>
        <tr>
            <td><div class="support-form-group"><?php Form_Class::form_input('text','st_title','','','support-search-form-control','','','','','',false,__('Ticket','wp-support-ticket'));?></div> </td>
            <td><?php Form_Class::form_input('submit','submit','',__('Search','wp-support-ticket'),'btn btn-default','','','','','',false,'');?></td>
        </tr>
    </tbody>
</table>
<?php Form_Class::form_close();?>