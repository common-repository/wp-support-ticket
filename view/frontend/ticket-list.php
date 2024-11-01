<?php if ($title) {?>
<h2><?php echo $title; ?></h2>
<?php }?>
<table class="support-list-table table-hover">
  <thead>
    <tr>
      <th><?php _e('#', 'wp-support-ticket');?></th>
      <th><?php _e('Ticket', 'wp-support-ticket');?></th>
      <th><?php _e('Status', 'wp-support-ticket');?></th>
      <th><?php _e('Last Post By', 'wp-support-ticket');?></th>
      <th><?php _e('Created On', 'wp-support-ticket');?></th>
    </tr>
  </thead>
  <tbody>
    <?php
if ($data->have_posts()) {
    while ($data->have_posts()) {
        $data->the_post();
        $ticket_status = get_post_meta($data->post->ID, '_ticket_status', true);
        ?>
        <tr>
          <td><a href="<?php echo $this->tick_details_link($data->post->ID); ?>"><?php echo $data->post->ID; ?></a></td>
          <td><a href="<?php echo $this->tick_details_link($data->post->ID); ?>"><?php echo get_the_title(); ?></a></td>
          <td><?php echo $ticket_status_array[$ticket_status]; ?></td>
          <td><?php echo $rc->last_post_by($data->post->ID); ?></td>
          <td><?php echo get_the_date('F j, Y - g:i A', $data->post->ID); ?></td>
        </tr>
    <?php
} // end of loop
} else {?>
    <tr>
      <td colspan="5" align="center"><?php _e(WPST_NO_TICKETS_POSTED, 'wp-support-ticket');?></td>
    </tr>
    <?php }?>
  </tbody>
</table>