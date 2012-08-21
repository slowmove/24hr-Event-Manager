<?php
/**
 * Single Event Widget
 */
class Single_Event_Widget extends WP_Widget {

    function __construct()
    {
        parent::__construct('single_event_widget', 'Event Manager - Registrering enskilt event');
    }
    
    public function widget($args, $instance)
    {
        echo $before_widget;
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
     
        if (!empty($title))
          echo $before_title . $title . $after_title;;
     
        $eventmanager = new EventManager();
        $eventmanager->show_registration_form($instance['event_id']);
     
        echo $after_widget;
    }
    
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['event_id'] = $new_instance['event_id'];
        return $instance;
    }
    
    public function form($instance)
    {
        $instance = wp_parse_args( (array) $instance, array(
                                                            'title' => '',
                                                            'event_id' => ''
                                                            )
                                  );
        $title = $instance['title'];
        $event_id = $instance['event_id'];
        
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                Rubrik:
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
            </label>
        </p>        
        <p>
            <label for="<?php echo $this->get_field_id('event_id'); ?>">
                Event ID:
                <input class="widefat" id="<?php echo $this->get_field_id('event_id'); ?>" name="<?php echo $this->get_field_name('event_id'); ?>" type="text" value="<?php echo attribute_escape($event_id); ?>" />
            </label>
        </p>
        <?php
    }

}
add_action( 'widgets_init', create_function('', 'return register_widget("Single_Event_Widget");') );

/**
 * List upcoming events
 */
class Upcoming_Events_Widget extends WP_Widget {

    function __construct()
    {
        parent::__construct('upcoming_events_widget', 'Event Manager - Kommande events');
    }
    
    public function widget($args, $instance)
    {
        echo $before_widget;
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
     
        if (!empty($title))
          echo $before_title . $title . $after_title;;
     
        $eventmanager = new EventManager();
        $eventlist = $eventmanager->get_upcoming_events();
        if(count($eventlist) > 0)
        {
            echo '<ul>';
            foreach($eventlist as $e)
            {
                echo '<li>' . $e->name . '</li>';
            }
            echo '</ul>';
        }
     
        echo $after_widget;
    }
    
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        return $instance;
    }
    
    public function form($instance)
    {
        $instance = wp_parse_args( (array) $instance, array(
                                                            'title' => ''
                                                            )
                                  );
        $title = $instance['title'];        
        
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                Rubrik:
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
            </label>
        </p>        
        <?php
    }

}
add_action( 'widgets_init', create_function('', 'return register_widget("Upcoming_Events_Widget");') );