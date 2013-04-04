<?php

/**
 * Shortcode setup event list
 */
function eventlist_shortcode_func( $atts ) {
	extract( shortcode_atts( array(
	), $atts ) );

    $eventmanager = new EventManager();
	$events = $eventmanager->get_upcoming_events();

	return "<iframe src=\"https://embed.spotify.com/?uri={$play}&view={$view}&theme={$theme}\" style=\"width:{$width}px; height:{$height}px;\" frameborder=\"0\" allowTransparency=\"true\"></iframe>";
}
add_shortcode( 'eventlist', 'eventlist_shortcode_func' );

/**
 * Shortcode setup single event
 */
function event_shortcode_func( $atts ) {
	extract( shortcode_atts( array(
		'id' => ''
	), $atts ) );

	$eventmanager = new EventManager();
    $event = $eventmanager->get_event($id);

	return "<iframe src=\"https://embed.spotify.com/?uri={$play}&view={$view}&theme={$theme}\" style=\"width:{$width}px; height:{$height}px;\" frameborder=\"0\" allowTransparency=\"true\"></iframe>";
}
add_shortcode( 'event', 'event_shortcode_func' );




add_filter( 'page_template', 'event_page_templates' );
function event_page_templates( $page_template )
{
    global $post;

    $event_list_page_id = get_option('events_holder_page_id');    
    
    if ( $post->ID == $event_list_page_id )
    {
        $page_template = dirname( __FILE__ ) . "/page-templates/event-list-template.php";
    }
    elseif ( $post->post_parent == $event_list_page_id )
    {
        $page_template = dirname( __FILE__ ) . "/page-templates/event-template.php";
    }
    
    return $page_template;
}