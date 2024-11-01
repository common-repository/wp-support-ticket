<table border="0" width="100%" class="ap-table"> 
  <tr>
    <td colspan="2"><h3><?php _e('WP Support Settings','wp-support-ticket');?></h3></td>
  </tr>
  <tr>
    <td colspan="2">
        <div class="ap-tabs">
            <div class="ap-tab"><?php _e('Email Settings','wp-support-ticket');?></div>
            <div class="ap-tab"><?php _e('Other Settings','wp-support-ticket');?></div>
            <div class="ap-tab"><?php _e('Shortcodes','wp-support-ticket');?></div>
        </div>
        <div class="ap-tabs-content">
            <div class="ap-tab-content">
            <table width="100%">
              <tr>
                <td valign="top" width="300"><strong><?php _e('Admin Email','wp-support-ticket');?></strong></td>
                <td><?php Form_Class::form_input('text','support_admin_email','',$support_admin_email,'widefat','','','','','',false,__('admin@example.com','wp-support-ticket'));?>
                    <br />
                    <i><?php
                    printf( esc_html__( 'This email will be used when support ticket related emails are sent. (When new ticket is created by user, User add a reply to a ticket etc) %s version has option to edit Email Body and use Email Templates.', 'wp-support-ticket' ), '<a href="https://www.aviplugins.com/wp-support-pro/" target="_blank">PRO</a>' );
                    ?>
                    </i></td>
              </tr>
              <tr>
              <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td valign="top"><strong><?php _e('From Email','wp-support-ticket');?></strong></td>
                <td><?php Form_Class::form_input('text','support_admin_from_email','',$support_admin_from_email,'widefat','','','','','',false,__('no-reply@example.com','wp-support-ticket'));?>
                <br>
                <i><?php _e('Enter From Email so that emails do not go to SPAM folder','wp-support-ticket');?></i>
                    </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><?php Form_Class::form_input('submit','submit','',__('Save','wp-support-ticket'),'button button-primary button-large button-ap-large','','','','','',false,'');?></td>
              </tr>
              </table>
            </div>
            <div class="ap-tab-content">
            <table width="100%">
               <tr>
                <td valign="top" width="300"><strong><?php _e('Ticket Shortcode Page','wp-support-ticket');?></strong></td>
                <td><?php
                        $args = array(
                        'depth'            => 0,
                        'selected'         => $ticket_sc_page,
                        'echo'             => 1,
                        'show_option_none' => '-',
                        'id' 			   => 'ticket_sc_page',
                        'name'             => 'ticket_sc_page'
                        );
                        wp_dropdown_pages( $args ); 
                    ?><font color="red"><?php _e('Important','wp-support-ticket');?></font>
                    <br />
                    <i><?php _e('Please create a new page and put the shortcode <strong>[ticket]</strong> in the page and select that page here. Please do not use that page as Front page.','wp-support-ticket');?></i></td>
              </tr>
               <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
               <tr>
                <td valign="top"><strong><?php _e('Before Login Message Ticket Create','wp-support-ticket');?></strong><p><i><?php _e('If user is not logged in then this message will be displayed with <strong>[create_support]</strong> shortcode.','wp-support-ticket');?></i></p></td>
                <td><?php Form_Class::form_textarea('ticket_before_login_message_create','',$ticket_before_login_message_create,'widefat','','','','3','','','Please login to create support ticket. [login_widget]');?><br><?php _e('HTML tags and shortcodes are allowed','wp-support-ticket');?></td>
              </tr>
               <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
               <tr>
                <td valign="top"><strong><?php _e('Before Login Message Ticket Search','wp-support-ticket');?></strong><p><i><?php _e('If user is not logged in then this message will be displayed with <strong>[ticket_search]</strong> shortcode.','wp-support-ticket');?></i></p></td>
                <td><?php Form_Class::form_textarea('ticket_before_login_message_search','',$ticket_before_login_message_search,'widefat','','','','3','','','Please login to search ticket. [login_widget]');?><br><?php _e('HTML tags and shortcodes are allowed','wp-support-ticket');?></td>
              </tr>
               <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
               <tr>
                <td valign="top"><strong><?php _e('Before Login Message Ticket List','wp-support-ticket');?></strong>
                <p><i><?php _e('If user is not logged in then this message will be displayed with <strong>[ticket]</strong> shortcode.','wp-support-ticket');?></i></p></td>
                <td><?php Form_Class::form_textarea('ticket_before_login_message_list','',$ticket_before_login_message_list,'widefat','','','','3','','','Please login to view support ticket. [login_widget]');?><br><?php _e('HTML tags and shortcodes are allowed','wp-support-ticket');?></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><?php Form_Class::form_input('submit','submit','',__('Save','wp-support-ticket'),'button button-primary button-large button-ap-large','','','','','',false,'');?></td>
              </tr>
              </table>
            </div>
            <div class="ap-tab-content">
            <table width="100%">
                  <tr>
                <td>
                <p>1. <strong>[create_support]</strong> to display Create New Support Ticket Form.</p>
                <p>2. <strong>[ticket]</strong> to display Tickets Created by User. User must me logged in to view support ticket created by him/ her.</p>
                <p>3. <strong>[ticket_search]</strong> to display Ticket Search Form.</p>
                </td>
              </tr>
              <tr>
                <td><hr /></td>
              </tr>
            <tr>
                <td>
                <font color="red"><strong><?php _e('Note','wp-support-ticket');?>*</strong></font><br>
                <p>1. If you face <strong>Page Not Found</strong> issue when visiting the ticket details page please update the <a href="options-permalink.php">Permalinks</a> settings.</p>
                <p>2. If you are using permalinks for your site then your permalink structure should contain <strong>%supticket%</strong>.</p>
                <p>For example, if your permalink looks like this <strong>/%postname%/</strong> then you should change that to <strong>/%postname%/%supticket%/</strong>. <strong>This is not mandatory</strong>.</p>
                
                </td>
              </tr>
              </table>
            </div>
        </div>
    </td>
  </tr>
</table>