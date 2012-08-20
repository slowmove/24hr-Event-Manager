<?php

/*
	Plugin Name: 24HR Event Manager
	Plugin URI: http://www.24hr.se/
	Description: Handles extra user information and let users sell products
	Version: 1.0
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
    public static $twentyfourEventManagerDBVersion = "0.01";  
	public static $tables = array(
		"events" => "event_manager_events",
	    "bookings" => "event_manager_bookings"
	);
	
    //---------------------------------------------------------------------------------
    //	singleton instance reference
    //---------------------------------------------------------------------------------        
	public static $singletonRef = NULL;
	
	public $tableNameEvents;
	public $tableNameBookings;

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
    }
    
    /**
     * Events
     * Create / receive events
     */
    public function create_event($name, $address, $city, $time, $description, $places)
    {
        $result = $this->wpdb->insert($this->tableNameEvents,
                                      array(
                                        'name' => $name,
                                        'address' => $address,
                                        'city' => $city,
                                        'time' => $time,
                                        'description' => $description,
                                        'places' => $places
                                      )
        );
        
        if ($result)
        {
            $result = $this->wpdb->insert_id;
        }
        
        return $result;   
    }
    
    public function update_event($id, $name, $address, $city, $time, $description, $places)
    {
        $result = $this->wpdb->update($this->tableNameUsers,
                                    array(
                                        'name' => $name,
                                        'address' => $address,
                                        'city' => $city,
                                        'time' => $time,
                                        'description' => $description,
                                        'places' => $places
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
        
    }
    
    public function get_old_events()
    {
        
    }
    
    public function get_event($id)
    {
        $result = $this->wpdb->get_results("SELECT * FROM $this->tableNameEvents where id = $id");
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
                                        'comment' => $comment,
                                        'places' => $places
                                      )
        );
        
        if ($result)
        {
            $result = $this->wpdb->insert_id;
        }
        
        return $result;                 
    }
    
    public function get_number_of_users_for_event($event_id)
    {
        $result = $this->wpdb->get_results("SELECT SUM(nr_to_come) FROM $this->tableNameBookings where event_id = $event_id");
        return $result[0];          
    }
    
    public function remove_user_from_event($event_id, $user_id)
    {
        $result = $this->wpdb->query($this->wpdb->prepare("DELETE FROM $this->tableNameBookings WHERE event_id = $event_id AND user_id = $user_id "));  
        return $result;    		        
    }
    
    public function get_users_for_event($event_id)
    {
        $result = $this->wpdb->get_results("SELECT * FROM $this->tableNameBookings where event_id = $event_id");
        return $result;         
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
                places mediumint(9) NOT NULL,
				UNIQUE KEY id (id)
            );";
			dbDelta($sql);
			
			// bookings table
			$table_name = $wpdb->prefix . EventManager::$tables['bookings'];
            $sql = "CREATE TABLE " . $table_name . " (
    	        id mediumint(9) NOT NULL AUTO_INCREMENT,
                event_id mediumint(9) NOT NULL,
                user_id mediumint(9) NOT NULL
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
    }     
}

// hooks for install and update
register_activation_hook(__FILE__, 'EventManager::install');
add_action('plugins_loaded', 'EventManager::update');
add_action('admin_menu', 'EventManager::setRequiredReferences');

// load admin page
require_once('24hr-event-manager-admin.php');
require_once('24hr-event-manager-dashboard-widget.php');