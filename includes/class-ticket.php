<?php
class Ticket_Class {

    public function __construct() {
        add_filter('manage_edit-ticket_columns', array($this, 'show_ticket_fields'));
        add_action('manage_ticket_posts_custom_column', array($this, 'display_ticket_fields'), 10, 2);
        add_filter('manage_edit-ticket_sortable_columns', array($this, 'display_ticket_sortable_fields'));
        add_action('pre_get_posts', array($this, 'ticket_last_post_date_orderby'));
        add_action('init', array($this, 'ticket_post'));
    }

    public function ticket_post() {
        $labels = array(
            'name' => _x('Ticket', 'post type general name', 'wp-support-ticket'),
            'singular_name' => _x('Ticket', 'post type singular name', 'wp-support-ticket'),
            'menu_name' => _x('Tickets', 'admin menu', 'wp-support-ticket'),
            'name_admin_bar' => _x('Ticket', 'add new on admin bar', 'wp-support-ticket'),
            'add_new' => _x('Add New', 'Ticket', 'wp-support-ticket'),
            'add_new_item' => __('Add New Ticket', 'wp-support-ticket'),
            'new_item' => __('New Ticket', 'wp-support-ticket'),
            'edit_item' => __('Edit Ticket', 'wp-support-ticket'),
            'view_item' => __('View Ticket', 'wp-support-ticket'),
            'all_items' => __('All Tickets', 'wp-support-ticket'),
            'search_items' => __('Search Tickets', 'wp-support-ticket'),
            'not_found' => __('No Ticket found.', 'wp-support-ticket'),
            'not_found_in_trash' => __('No Ticket found in Trash.', 'wp-support-ticket'),
        );

        $args = array(
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'ticket'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'menu_icon' => 'dashicons-tag',
            'supports' => array('title'),
        );

        register_post_type('ticket', $args);
    }

    public function display_ticket_sortable_fields($columns) {
        $columns['last_post_date'] = 'last_post_date';
        $columns['created_on'] = 'created_on';
        return $columns;
    }

    public function show_ticket_fields($columns) {
        $new_columns['cb'] = '<input type="checkbox" />';
        $new_columns['title'] = __('Title', 'wp-support-ticket');
        $new_columns['last_post'] = __('Last Post By', 'wp-support-ticket');
        $new_columns['created_by'] = __('Created By', 'wp-support-ticket');
        $new_columns['last_post_date'] = __('Last Post Date', 'wp-support-ticket');
        $new_columns['status'] = __('Status', 'wp-support-ticket');
        $new_columns['created_on'] = __('Created On', 'wp-support-ticket');
        return $new_columns;
    }

    public function display_ticket_fields($column, $post_id) {
        global $ticket_status_array;
        $rc = new Support_Reply;
        switch ($column) {
        case 'last_post':
            echo $rc->last_post_by($post_id);
            break;
        case 'created_by':
            echo $rc->get_ticket_author($post_id);
            break;
        case 'last_post_date':
            echo $rc->last_post_date($post_id);
            break;
        case 'status':
            $status_id = get_post_meta($post_id, '_ticket_status', true);
            echo $ticket_status_array[$status_id];
            break;
        case 'created_on':
            echo get_the_date('F j, Y - g:i A');
            break;
        }
    }

    public function ticket_last_post_date_orderby($query) {
        if (!is_admin()) {
            return;
        }

        $orderby = $query->get('orderby');

        if ('last_post_date' == $orderby) {
            $query->set('meta_key', 'last_post_date');
            $query->set('orderby', 'meta_value');
        }
    }

}
