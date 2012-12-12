<?php
$eventmanager = new EventManager();

if(!$_GET["eventid"]): // Show list of events
$events = $eventmanager->get_upcoming_events();
$old_events = $eventmanager->get_old_events();
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
                    <th>Väntelista</th>
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
							<input type="button" value="Deltagarlista" onclick="location.href=location.href + '&eventid=<?php echo $event->id; ?>'" />
						</td>
                        <td>
                            <input type="button" value="Väntelista" onclick="location.href=location.href + '&eventid=<?php echo $event->id; ?>&standby=true'" />
                        </td>
                    </tr>
                <?php 
                    $counter++;
                    endforeach; 
                ?>
            </tbody>
        </table>
    </div>
	<br/><br/><br/>
	<?php if( count($old_events) > 0 ): ?>
		<h2>Gamla events</h2>
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
					</tr>
				</thead>
				<tbody>
					<?php
						
						$counter = 0;
						date_default_timezone_set('Europe/Stockholm'); 
					?>
					<?php
						foreach($old_events as $event):
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
						</tr>
					<?php 
						$counter++;
						endforeach; 
					?>
				</tbody>				
			</table>
		</div>
	<?php endif; ?>	
</div>
<?php
elseif($_GET["eventid"] && !$_GET["standby"]): // Show list of users for a single event

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
                    <th>Action</th>
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
                    <tr class="item<?php echo ($counter) % 2 == 0 ? " odd": ""; ?>" id="mailItem-<?php echo $event->id; ?>" data-event="<?php echo $event->id; ?>" data-user="<?php echo $user->user_id; ?>">
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
                        <td>
                            <input id="removeUser" type="button" value="Ta bort" />
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
elseif($_GET["eventid"] && $_GET["standby"]): // Show list of standby users for a single event

$event = $eventmanager->get_event($_GET["eventid"]);
$users = $eventmanager->get_standby_users_for_event($_GET["eventid"]);
?>
<div class="wrap tfmac">
    <h2>Väntelista <?php echo $event->name; ?></h2>
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
                <th>Action</th>
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
                <tr class="item<?php echo ($counter) % 2 == 0 ? " odd": ""; ?>" id="mailItem-<?php echo $event->id; ?>" data-event="<?php echo $event->id; ?>" data-user="<?php echo $user->user_id; ?>">
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
                    <td>
                        <input id="addUser" type="button" value="Lägg till i deltagarlista" />
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