<?php
$eventmanager = new EventManager();

if(!$_GET["eventid"]):
$events = $eventmanager->get_all_events();
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
					<th>Deltagarlista</th>
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
							<input type="button" value="Visa" onclick="location.href=location.href + '&eventid=<?php echo $event->id; ?>'" />
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
<?php
else:

$event = $eventmanager->get_event($_GET["eventid"]);
$users = $eventmanager->get_users_for_event($_GET["eventid"]);
?>
<div class="wrap tfmac">
    <h2>Deltagarlista <?php echo $event->name; ?></h2>
	<p>
		<a href="javascript:history.back();">Tillbaka till eventlistan</a>
	</p>
    <div class="innerWrapper">
        <table id="mailList">
            <thead>
                <tr>
                    <th class="date">Namn</th>
                    <th>Mail</th>
                    <th>Antal</th>
                    <th>Intresserad i framtida events</th>
                    <th>Kommentar</th>
                </tr>
            </thead>
            <tbody>
                <?php                    
                    $counter = 0;
                	date_default_timezone_set('Europe/Stockholm'); 
                ?>
                <?php
                    foreach($users as $user):
                ?>
                    <tr class="item<?php echo ($counter) % 2 == 0 ? " odd": ""; ?>" id="mailItem-<?php echo $event->id; ?>">
                        <td class="date">
							<?php echo $user->name; ?>
                        </td>
                        <td class="">
                            <?php echo $user->email; ?>
                        </td>
                        <td class="">
                            <?php echo $user->nr_to_come; ?>
                        </td>									
                        <td class="">
                            <?php echo $user->interested_in_more ? "Ja" : "Nej"; ?>
                        </td>
                        <td class="">
                            <?php echo $user->comment; ?>
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
<?php endif; ?>