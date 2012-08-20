<?php
/**
 * Add admin page
 */
add_action('admin_menu', 'EventManager_add_page');
function EventManager_add_page() {
	add_menu_page('Event Manager', 'Event Manager', 'manage_options', __FILE__, 'EventManagerList');
    add_submenu_page(dirname(__file__), 'Skapa Event', 'Skapa Event', 'manage_options', __file__, 'EventManagerCreate');
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