<div id="24hr-event-manager-form-<?php echo $event_id; ?>">
<?php
$eventmanager = new EventManager();
$event = $eventmanager->get_event($event_id);
$nr_of_users = $eventmanager->get_number_of_users_for_event($event_id);

if($nr_of_users->nr_to_come < $event->places):
?>
    <?php
    $pluginRoot = plugins_url('', __FILE__);
    if( !$_GET["thx"] ):
        if(is_user_logged_in())
        {
            global $current_user;
            get_currentuserinfo();           
            $name = $current_user->user_firstname . " " . $current_user->user_lastname;
            $email = $current_user->user_email;
            $id = $current_user->ID;
        }
        ?>
        <script type="text/javascript" src="<?php echo $pluginRoot ?>/assets/js/placeholder.min.js"></script>
        <form class="24hr-event-manager-form" action="" method="post">            
            <input type="text" name="name" class="name" placeholder="Namn" value="<?php echo $name; ?>" />
            <br/>
            <input type="text" name="email" class="email" placeholder="Mail" value="<?php echo $email; ?>" />
            <br/>
            <select name="nr_to_come" class="nr_to_come">
                <option disabled="disabled" selected="selected value="0">Antal som kommer</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <br/>
            <label for="interested_in_mote">Intresserad av fler event</label>
            <input type="checkbox" name="interested_in_more" class="interested_in_more"/>
            <br/>
            <textarea name="comment" class="comment" placeholder="Kommentar (ev. allergier m.m.)"></textarea>
            
            <input type="hidden" name="user_id" class="user_id" value="<?php echo $id; ?>" />
            <input type="hidden" name="event_id" class="event_id" value="<?php echo $event_id; ?>" />
            
            <input type="submit" value="Skicka" />            
        </form>
        
        <script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery('.24hr-event-manager-form input:submit').click(function(event){
                    event.preventDefault();
                    var name = jQuery(".24hr-event-manager-form .name").val();
                    var email = jQuery(".24hr-event-manager-form .email").val();
                    var nr_to_come = jQuery(".24hr-event-manager-form .nr_to_come").val();
                    var interested_in_more = jQuery(".24hr-event-manager-form .interested_in_more").val();
                    var comment = jQuery(".24hr-event-manager-form .comment").val();
                    var user_id = jQuery(".24hr-event-manager-form .user_id").val();
                    var event_id = jQuery(".24hr-event-manager-form .event_id").val();
                    
                    jQuery.ajax({
                        type: "POST",
                        url: "<?php echo $pluginRoot ?>/api/add-attendance.php",
                        async: true,
                        timeout: 50000,
                        data: { name: name, email: email, nr_to_come: nr_to_come, interested_in_more: interested_in_more, comment: comment, user_id: user_id, event_id: event_id},
                        success: function(data) {
                            alert("Ditt deltagande i eventet har registrerats.");
                            location.href = location.href + "?thx=ohyeah";
                        },
                        error: function(data) {
                            alert("Registreringen misslyckades, kontakta administratören.");
                        }
                    });	                   
                });
            });
        </script>
    <?php else: ?>
        <p>Du är nu registrerad till detta event. Välkommen.</p>
    <?php endif; ?>
<?php else: ?>
    <p>Eventet är dessvärre fullbokat.</p>
<?php endif; ?>
</div>