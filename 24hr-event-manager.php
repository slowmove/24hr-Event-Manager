<?php

/*
	Plugin Name: 24HR Event Manager
	Plugin URI: http://www.24hr.se/
	Description: Handles event and bookings
	Version: 1.1
	Author: Erik Johansson
	License: GPL2
	*/

	/*  Copyright 2011  Erik Johansson  (email : erik.johansson@24hr.se)

	    This program is free software; you can redistribute it and/or modify
	    it under the terms of the GNU General Public License, version 2, as 
	    published by the Free Software Foundation.

	    This program is distributed in the hope that it will be useful,
	    but WITHOUT ANY WARRANTY; without even the implied warranty of
	    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	    GNU General Public License for more details.

	    You should have received a copy of the GNU General Public License
	    along with this program; if not, write to the Free Software
	    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
class EventManager
{
    // plugin db version
    public static $twentyfourEventManagerDBVersion = "0.06";  
	public static $tables = array(
		"events" => "event_manager_events",
	    "bookings" => "event_manager_bookings",
		"standby" => "event_manager_standby"
	);
	
    //---------------------------------------------------------------------------------
    //	singleton instance reference
    //---------------------------------------------------------------------------------        
	public static $singletonRef = NULL;
	
	public $tableNameEvents;
	public $tableNameBookings;
	public $tableNameStandby;

    private $wpdb;    

    //---------------------------------------------------------------------------------
    //	creates an instance of the class, if no isntance was created before (singleton implementation)
    //---------------------------------------------------------------------------------            
	public static function getInstance()
	{
		if (self::$singletonRef == NULL)
		{
			self::$singletonRef = new ExpertChat();
		}
		return self::$singletonRef;
	}
    
    public function __construct()
    {
	    global $wpdb;
	    $this->wpdb = $wpdb;
	    
	    $this->tableNameEvents = $this->wpdb->prefix . EventManager::$tables['events'];
	    $this->tableNameBookings = $this->wpdb->prefix . EventManager::$tables['bookings'];
		$this->tableNameStandby = $this->wpdb->prefix . EventManager::$tables['standby'];
    }
    
    /**
     * Events
     * Create / receive events
     */
    public function create_event($name, $address, $city, $time, $description, $content, $places)
    {
        $result = $this->wpdb->insert($this->tableNameEvents,
                                      array(
                                        'name' => $name,
                                        'address' => $address,
                                        'city' => $city,
                                        'time' => $time,
                                        'description' => $description,
										'content' => $content,
                                        'places' => $places
                                      )
        );
        
        if ($result)
        {
            $result = $this->wpdb->insert_id;
			
			$event_holder_page_id = get_option("events_holder_page_id");			
			$event_page = array(
			   'post_title' => $name,
			   'post_status' => 'publish',
			   'post_author' => 1,
			   'post_type' => 'page',
			   'post_parent' => $event_holder_page_id
			);  		
			$event_page_id = wp_insert_post( $event_page );
			update_post_meta($event_page_id, "_wp_page_template", dirname( __FILE__ ) . "/page-templates/event-template.php"); 
			add_post_meta($event_page_id, "event_id", $result);
			$this->set_page_for_event($result, $event_page_id);
        }
        
        return $result;   
    }
    
    public function update_event($id, $name, $address, $city, $time, $description, $content, $places)
    {
        $result = $this->wpdb->update($this->tableNameEvents,
                                    array(
                                        'name' => $name,
                                        'address' => $address,
                                        'city' => $city,
                                        'time' => $time,
                                        'description' => $description,
										'content' => $content,
                                        'places' => $places
                                    ),
                                    array(
                                        'id' => $id
                                    )
        );  
        return $result;        
    }
	
	public function set_page_for_event($id, $pageid)
	{
		$result = $this->wpdb->update($this->tableNameEvents,
									  array(
										'pageid' => $pageid
									  ),
									  array(
										'id' => $id
									  )
		);
		return $result;
	}
    
    public function get_all_events()
    {
        $result = $this->wpdb->get_results("SELECT * FROM $this->tableNameEvents");
        return $result;        
    }
    
    public function get_upcoming_events()
    {
        $result = $this->wpdb->get_results("SELECT * FROM $this->tableNameEvents WHERE time > NOW() ORDER BY time ASC");
        return $result;           
    }
    
    public function get_old_events()
    {
        $result = $this->wpdb->get_results("SELECT * FROM $this->tableNameEvents WHERE time < NOW()");
        return $result;                
    }
    
    public function get_event($id)
    {
        $result = $this->wpdb->get_results("SELECT * FROM $this->tableNameEvents where id = $id");
        return $result[0];        
    }
	
	public function get_event_by_pageid($pageid)
	{
        $result = $this->wpdb->get_results("SELECT * FROM $this->tableNameEvents where pageid = $pageid");
        return $result[0];  		
	}
    
    /**
     * Users - Bookings
     * set user for a event and get users for one event
     */
    public function add_user_to_event($event_id, $user_id, $name, $email, $nr_to_come, $interested_in_more, $comment)
    {
        $result = $this->wpdb->insert($this->tableNameBookings,
                                      array(
                                        'event_id' => $event_id,
                                        'user_id' => $user_id,
                                        'name' => $name,
                                        'email' => $email,
                                        'nr_to_come' => $nr_to_come,
                                        'interested_in_more' => $interested_in_more,
                                        'comment' => $comment
                                      )
        );
        
        if ($result)
        {
            $result = $this->wpdb->insert_id;
			
			$event = $this->get_event($event_id);
			/**
			 * Mail to the user
			 */
			$htmlmail = "<html><head></head><body>
			<h2>Hej $name </h2>
			<p>
			Du har nu anmält ditt deltagande på $event->name.
			Glöm inte avanmäla senast 48 timmar innan eventet.
			</p>
			<p>
			Mvh,
			Nätverket 100 procent
			</p>
			</body></html>
			";
			$this->html_mail($email, "info@natverket100procent.se", "Vi har mottagit din anmälan", $htmlmail);
			/**
			 * Mail to 100 procent
			 */
			$htmlmail = "";
			$this->html_mail("info@natverket100procent.se", "info@natverket100procent.se", $name . " har anmält sig till $event->name", $htmlmail);
        }
        
        return $result;                 
    }
    
    public function get_number_of_users_for_event($event_id)
    {
        $result = $this->wpdb->get_results("SELECT SUM(nr_to_come) as nr_to_come FROM $this->tableNameBookings where event_id = $event_id");
        return $result[0];          
    }
    
    public function get_users_for_event($event_id)
    {
        $result = $this->wpdb->get_results("SELECT * FROM $this->tableNameBookings where event_id = $event_id");
        return $result;         
    }
	
	public function get_specific_user_for_event($event_id, $user_id)
	{
        $result = $this->wpdb->get_results("SELECT * FROM $this->tableNameBookings where event_id = $event_id and user_id = $user_id");
        return $result[0];   		
	}
	
	public function is_attendance($event_id, $user_id)
	{
        $result = $this->wpdb->get_results("SELECT * FROM $this->tableNameBookings where event_id = $event_id and user_id = $user_id");
        if( $result )
			return true;
		else
			return false;
	}
	
	/**
	 * Users - stand-by
	 * set user on the stand by list for one event
	 */
	public function add_user_as_standby_to_event($event_id, $user_id, $name, $email, $nr_to_come, $interested_in_more, $comment)
	{
		$result = $this->wpdb->insert($this->tableNameStandby,
                                      array(
                                        'event_id' => $event_id,
                                        'user_id' => $user_id,
                                        'name' => $name,
                                        'email' => $email,
                                        'nr_to_come' => $nr_to_come,
                                        'interested_in_more' => $interested_in_more,
                                        'comment' => $comment
                                      )
        );
		
		if( $result )
		{
			$result = $this->wpdb->insert_id;
		}
		
		
		$event = $this->get_event($event_id);
		/**
		 * Mail to the user
		 */
		$htmlmail = "<html><head></head><body>
		<h2>Hej $name </h2>
		<p>
		Du har nu anmält dig till väntelistan för $event->name.
		Får du möjlighet att delta på eventet kommer vi höra av oss.
		</p>
		<p>
		Mvh,
		Nätverket 100 procent
		</p>
		</body></html>
		";
		$this->html_mail($email, "info@natverket100procent.se", "Vi har mottagit din anmälan", $htmlmail);
		/**
		 * Mail to 100 procent
		 */
		$htmlmail = "";
		$this->html_mail("info@natverket100procent.se", "info@natverket100procent.se", $name . " har anmält sig till $event->name", $htmlmail);
		
		return $result;
	}
	
	public function remove_user_from_standby_list($event_id, $user_id)
	{
        $result = $this->wpdb->query($this->wpdb->prepare("DELETE FROM $this->tableNameStandby WHERE event_id = $event_id AND user_id = $user_id "));  
        return $result;  		
	}
	
	public function get_standby_users_for_event($event_id)
	{
		$result = $this->wpdb->get_results("SELECT * FROM $this->tableNameStandby where event_id = $event_id");
		return $result;
	}
	
	public function get_specific_standby_user_for_event($event_id, $user_id)
	{
        $result = $this->wpdb->get_results("SELECT * FROM $this->tableNameStandby where event_id = $event_id and user_id = $user_id");
        return $result[0];   		
	}
	
	/**
	 * AJAX enabled functions
	 */
	public function remove_user_from_event($event_id = "", $user_id = "")
    {
		if( empty($event_id) || empty($user_id) )
		{
			$event_id = $_POST["eventId"];
			$user_id = $_POST["userId"];
		}
		global $wpdb;
		$dbtable = $wpdb->prefix . EventManager::$tables['bookings'];
        $result = $wpdb->query($wpdb->prepare("DELETE FROM $dbtable WHERE event_id = $event_id AND user_id = $user_id "));  
        return $result;    		        
    }
	
	public function move_standby_user_to_event($event_id = "", $user_id = "")
	{
		if( empty($event_id) || empty($user_id) )
		{
			$event_id = $_POST["eventId"];
			$user_id = $_POST["userId"];
		}		
		$eventhandler = new EventManager();
		$user = $eventhandler->get_specific_standby_user_for_event($event_id, $user_id);
		
		$moved = $eventhandler->add_user_to_event($event_id, $user_id, $user->name, $user->email, $user->nr_to_come, $user->interested_in_more, $user->comment);
		
		if( $moved )
		{
			$eventhandler->remove_user_from_standby_list($event_id, $user_id);

			return true;
		}
		else
		{
			return false;
		}
	}	
    
	/**
	 * Shows the registration form on the site
	 */
	public function show_registration_form($event_id)
	{
		include('24hr-event-manager-form.php');
	}
	
	/**
	 * Makes it easier to send html mail with utf-8 charset
	 * @param string $receiver
	 * @param string $sender
	 * @param string $subject 
	 * @param string $message 
	 * @return bool $success
	 */
	public function html_mail($receiver, $sender, $subject, $message)
	{
		$headers = 'To: '.$receiver.' <'. $receiver . '>' . "\r\n";
		$headers .= 'From: '.$sender.' <' .$sender .'>' . "\r\n";
		$headers .= 'MIME-Version: 1.0\r\n';
		$headers .= 'Content-type: text/html; charset=UTF-8\r\n';
		$headers .= "Content-Transfer-Encoding: 8bit\r\n";
		add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
		
		$success = wp_mail($receiver, $subject, $message, $headers);
		
		return $success;
	}
    
    //---------------------------------------------------------------------------------
    //     INSTALLATION
    //	install function, ie create or update the database
    //---------------------------------------------------------------------------------    
    public static function install() 
    {
        
        global $wpdb;
        
        $installed_ver = get_option( "twentyfourEventManagerDBVersion" );
        if($installed_ver != EventManager::$twentyfourEventManagerDBVersion ) 
        {
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			
			// events table
            $table_name = $wpdb->prefix . EventManager::$tables['events'];
            $sql = "CREATE TABLE " . $table_name . " (
    	        id mediumint(9) NOT NULL AUTO_INCREMENT,
                name VARCHAR(300) NOT NULL,
    	        address VARCHAR(300) NOT NULL,
    	        city VARCHAR(300) NOT NULL,
                time DATETIME NOT NULL,
                description VARCHAR(1000) NOT NULL,
				content VARCHAR(5000) NOT NULL,
                places mediumint(9) NOT NULL,
				pageid mediumint(9),
				UNIQUE KEY id (id)
            );";
			dbDelta($sql);
			
			// bookings table
			$table_name = $wpdb->prefix . EventManager::$tables['bookings'];
            $sql = "CREATE TABLE " . $table_name . " (
    	        id mediumint(9) NOT NULL AUTO_INCREMENT,
                event_id mediumint(9) NOT NULL,
                user_id mediumint(9) NOT NULL,
                name VARCHAR(100) NOT NULL,
				email VARCHAR(300) NOT NULL,
				nr_to_come mediumint(9) NOT NULL,
				interested_in_more smallint DEFAULT 0,	            	        
				comment VARCHAR(500),				
				UNIQUE KEY id (id)
            );";
		    dbDelta($sql);
			
			// stand by table
			$table_name = $wpdb->prefix . EventManager::$tables['standby'];
			$sql = "CREATE TABLE " . $table_name . " (
    	        id mediumint(9) NOT NULL AUTO_INCREMENT,
                event_id mediumint(9) NOT NULL,
                user_id mediumint(9) NOT NULL,
                name VARCHAR(100) NOT NULL,
				email VARCHAR(300) NOT NULL,
				nr_to_come mediumint(9) NOT NULL,
				interested_in_more smallint DEFAULT 0,	            	        
				comment VARCHAR(500),				
				UNIQUE KEY id (id)
			);";
			dbDelta($sql);
 
			//echo $sql;
            update_option("twentyfourEventManagerDBVersion", EventManager::$twentyfourEventManagerDBVersion);

        }
        
		if( !get_option("events_holder_page_id") )
		{
			$events_holder = array(
			   'post_title' => 'Events',
			   'post_status' => 'publish',
			   'post_author' => 1,
			   'post_type' => 'page',
			   'menu_order' => 98
			);  		
			$events_holder_page_id = wp_insert_post( $events_holder );
			update_post_meta($events_holder_page_id, "_wp_page_template", dirname( __FILE__ ) . "/page-templates/event-list-template.php"); 
			add_option("events_holder_page_id", $events_holder_page_id);
		}
    }
    
	/**
	 * Checks if a database table update is needed
	 * Then run the install function
	 */
    public static function update()
    {
        $installed_ver = get_option( "twentyfourEventManagerDBVersion" );
        if($installed_ver != EventManager::$twentyfourEventManagerDBVersion) 
        {
            EventManager::install();
        }
    }

    public static function setRequiredReferences()
    {
        // css
        wp_register_style('EventManagerAdminCss', plugins_url('assets/css/style.css', __FILE__));
		wp_register_style('jQueryUICSS', plugins_url('assets/css/smoothness/jquery-ui-1.8.23.custom.css', __FILE__));

        // load script
		wp_register_script('EventManagerModal', plugins_url('/assets/js/jquery.simplemodal.1.4.1.min.js', __FILE__));
		wp_register_script('Placeholder', plugins_url('/assets/js/placeholder.min.js', __FILE__));		
		wp_register_script('jqueryUI', plugins_url('/assets/js/jquery.ui.js', __FILE__));
		wp_register_script('jqueryUItime', plugins_url('/assets/js/jquery.ui.timepicker.js', __FILE__));
        wp_register_script('mainAdmin', plugins_url('/assets/js/main.js', __FILE__));
    }     
}

// hooks for install and update
register_activation_hook(__FILE__, 'EventManager::install');
add_action('plugins_loaded', 'EventManager::update');
add_action('admin_menu', 'EventManager::setRequiredReferences');

// hooks for ajax calls
add_action('wp_ajax_remove_user', 'EventManager::remove_user_from_event');
add_action('wp_ajax_add_user', 'EventManager::move_standby_user_to_event');

// load some help functions
require_once('24hr-event-manager-helpfunctions.php');
// load admin page
require_once('24hr-event-manager-admin.php');
require_once('24hr-event-manager-dashboard-widget.php');
require_once('24hr-event-manager-widget.php');