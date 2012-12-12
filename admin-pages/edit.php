<?php
$eventmanager = new EventManager();
?>
<?php if( !$_GET["event_id"] && !$_POST["name"] ): ?>
<?php
$events = $eventmanager->get_upcoming_events();
?>
<div class="wrap tfmac">
    <h2>Events</h2>
    <div class="innerWrapper">
        <table id="mailList">
            <thead>
                <tr>
					<th>ID</th>
                    <th class="date">Datum</th>
                    <th>Namn</th>
                    <th>Adress</th>
                    <th>Stad</th>
                    <th>Deltagare</th>
					<th>Ändra</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    
                    $counter = 0;
                	date_default_timezone_set('Europe/Stockholm'); 
                ?>
                <?php
                    foreach($events as $event):
                    $nr_of_users = $eventmanager->get_number_of_users_for_event($event->id);
                ?>
                    <tr class="item<?php echo ($counter) % 2 == 0 ? " odd": ""; ?>" id="mailItem-<?php echo $event->id; ?>">
						<td>
							<?php echo $event->id; ?>
						</td>
                        <td class="date"><?php
                            $date = new DateTime($event->time);
                            echo $date->format('Y-m-d H:i:s'); 
                        ?></td>
                        <td class="">
                            <?php echo $event->name; ?>
                        </td>
                        <td class="">
                            <?php echo $event->address; ?>
                        </td>									
                        <td class="">
                            <?php echo $event->city; ?>
                        </td>
                        <td class="">
                            <?php
							$nr = $nr_of_users->nr_to_come;
							echo isset($nr) ? $nr . " / " . $event->places : "0 / " . $event->places
							?>
                        </td>
						<td>
							<input type="button" value="Redigera" onclick="location.href=location.href + '&event_id=<?php echo $event->id; ?>'" />
						</td>
                    </tr>
                <?php 
                    $counter++;
                    endforeach; 
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php elseif( $_GET["event_id"] && !$_POST["name"] ): ?>
    <div class="wrap">
        <script type="text/javascript" src="<?php echo WP_PLUGIN_URL ?>/24hr-Event-Manager/tiny_mce/tiny_mce.js"></script>
        
        <script type="text/javascript">
        tinyMCE.init({
            theme : "advanced",
            mode : "exact",
            elements : "description, content",
            height : "320"
        });
        </script>
        <?php
        $e = $eventmanager->get_event($_GET["event_id"]);
        ?>
        <h2>Redigera Event</h2>
        <div class="innerWrapper">
            <form action="" method="post">
                <input type="hidden" name="event_id" value="<?php echo $_GET["event_id"]; ?>" />
                <input type="text" name="name" value="<?php echo $e->name ?>" />
                <br/>
                <input type="text" name="address" value="<?php echo $e->address ?>" />
                <br/>
                <input type="text" name="city" value="<?php echo $e->city ?>" />
                <br/>
                <input type="date" id="timepick" name="time" value="<?php echo $e->time ?>" />
                <br/>
                <label for="description">Kort beskrivning</label>
                <textarea id="description" name="description"><?php echo $e->description ?></textarea>
                <br/>
                <label for="content">Information</label>
                <textarea id="content" name="content"><?php echo $e->content ?></textarea>
                <br/>
                <input type="text" name="places" value="<?php echo $e->places ?>" />
                <br/>
                <input type="submit" value="Spara event" />
            </form>
            <script type="text/javascript">
                jQuery(document).ready(function(){
                    jQuery('#timepick').datetimepicker({
                        dateFormat: 'yy-mm-dd',
                        timeFormat: 'hh:mm:ss'
                    });
                    //2012-08-10 11:30:50
                });
            </script>
        </div>
    </div>    
<?php else: ?>
    <?php
    $eventmanager = new EventManager();
    $id = $_POST["event_id"];
    $name = $_POST["name"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $time = $_POST["time"];
    $description = $_POST["description"];
    $content = $_POST["content"];
    $places = $_POST["places"];
    $res = $eventmanager->update_event($id, $name, $address, $city, $time, $description, $content, $places);
    if($res):
    ?>
    <div class="wrap">
        <h2>Event skapat</h2>
        <div class="innerWrapper">
            <p>Ditt event är nu uppdaterat.</p>
        </div>
    </div>
    <?php else: ?>
    <div class="wrap">
        <h2>Event kunde inte uppdateras</h2>
        <div class="innerWrapper">
            <p>Ditt event kunde tyvärr inte uppdateras. Kontakta administratören.</p>
        </div>
    </div>    
    <?php endif; ?>
<?php endif; ?>