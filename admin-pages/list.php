<?php
$eventmanager = new EventManager();
$events = $eventmanager->get_all_events();
?>
<div class="wrap tfmac">
    <h2>Events</h2>
    <div class="innerWrapper">
        <table id="mailList">
            <thead>
                <tr>
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
                    foreach($events as $event):
                    $nr_of_users = $eventmanager->get_number_of_users_for_event($event->id);
                ?>
                    <tr class="item<?php echo ($counter) % 2 == 0 ? " odd": ""; ?>" id="mailItem-<?php echo $event->id; ?>">
                        <td class="date"><?php
                            $date = new DateTime($event->time);
                            echo $date->format('Y-m-d'); 
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
                            <?php echo (count($nr_of_users) > 0 ? $nr_of_users : 0) . " / " . $event->places ?>
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