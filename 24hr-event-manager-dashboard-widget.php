<?php
/**
 * Create a dashboard widget for excel export of users
 */
function eventmanager_dashboard_widget() {
    $eventmanager = new EventManager();
    $events = $eventmanager->get_upcoming_events();
    $pluginRoot = '/wp-content/plugins/24hr-Event-Manager';
    foreach($events as $e): 
        echo '<a target="_blank" href="'.$pluginRoot . '/classes/xls.php?event=' . $e->id . '&name=' . $e->name.'">Ladda ner bokningar för eventet: ' . $e->name . '</a><br/>';
    endforeach;
}
function eventmanager_add_dashboard_widget() {
    wp_add_dashboard_widget( 'eventmanager-widget', 'Deltagarlistor Events', 'eventmanager_dashboard_widget' );
}
add_action( 'wp_dashboard_setup', 'eventmanager_add_dashboard_widget' );