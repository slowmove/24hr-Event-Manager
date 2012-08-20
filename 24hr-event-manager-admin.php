<?php
/**
 * Add admin page
 */
add_action('admin_menu', 'EventManager_add_page');
function EventManager_add_page() {
    add_menu_page('Event Manager', 'Event Manager', 'read_private_pages', dirname(__file__), 'EventManagerList');
	add_submenu_page('Event Manager', 'Event Manager', 'manage_options', 'EventManager_list_events', 'EventManagerList');
    add_submenu_page(dirname(__file__), 'Skapa Event', 'Skapa Event', 'manage_options', 'EventManager_create_event', 'EventManagerCreate');
    
	wp_enqueue_style('EventManagerAdminCss');
    wp_enqueue_style('jQueryUICSS');
	wp_enqueue_script('EventManagerModal');
	wp_enqueue_script('Placeholder');
    wp_enqueue_script('jqueryUI');
    wp_enqueue_script('jqueryUItime');
}

/**
 * The Listing of events
 */
function EventManagerList()
{
	include 'admin-pages/list.php';
}

/**
 * The admin view to create new events
 */
function EventManagerCreate()
{
    include 'admin-pages/create.php';
}