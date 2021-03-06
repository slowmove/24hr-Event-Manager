<?php
/**
 * Add admin page
 */
add_action('admin_menu', 'EventManager_add_page');
function EventManager_add_page() {
    add_menu_page('Event Manager', 'Event Manager', 'read_private_pages', dirname(__file__), 'EventManagerList');
	add_submenu_page('Event Manager', 'Event Manager', 'manage_options', 'EventManager_list_events', 'EventManagerList');
    add_submenu_page(dirname(__file__), 'Skapa Event', 'Skapa Event', 'manage_options', 'EventManager_create_event', 'EventManagerCreate');
	add_submenu_page(dirname(__file__), 'Redigera Event', 'Redigera Event', 'manage_options', 'EventManager_edit_event', 'EventManagerEdit');
    
	wp_enqueue_style('EventManagerAdminCss');
    wp_enqueue_style('jQueryUICSS');
		
    wp_enqueue_script('mainAdmin',  array('jquery'));
	wp_localize_script('mainAdmin', 'eventusershandling', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}

add_action('admin_enqueue_scripts', 'add_scripts');
function add_scripts()
{
	wp_enqueue_script('EventManagerModal', array('jquery'));
	wp_enqueue_script('Placeholder', array('jquery'));
    
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-widget');
	wp_enqueue_script('jquery-ui-mouse');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('jquery-ui-button');
	wp_enqueue_script('jquery-ui-datepicker');
	
	wp_enqueue_script('jqueryUIdate');
    wp_enqueue_script('jqueryUItime', array('jquery-ui-datepicker', 'jquery-ui-slider'));	
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

/**
 * The admin view to edit events
 */
function EventManagerEdit()
{
	include 'admin-pages/edit.php';
}