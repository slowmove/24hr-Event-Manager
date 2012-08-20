<?php if( !$_POST["name"] ): ?>
    <div class="wrap">
        <h2>Skapa Event</h2>
        <div class="innerWrapper">
            <form action="" method="post">
                <input type="text" name="name" placeholder="Namn" />
                <br/>
                <input type="text" name="address" placeholder="Adress" />
                <br/>
                <input type="text" name="city" placeholder="Stad" />
                <br/>
                <input type="date" id="timepick" name="time" placeholder="Tid" />
                <br/>
                <textarea name="description" placeholder="Beskrivning"></textarea>
                <br/>
                <input type="text" name="places" placeholder="Antal platser" />
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
    $name = $_POST["name"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $time = $_POST["time"];
    $description = $_POST["description"];
    $places = $_POST["places"];
    $res = $eventmanager->create_event($name, $address, $city, $time, $description, $places);
    if($res):
    ?>
    <div class="wrap">
        <h2>Event skapat</h2>
        <div class="innerWrapper">
            <p>Ditt event är nu skapat. Gå <a href="">tillbaka</a> för att skapa fler event.</p>
        </div>
    </div>
    <?php else: ?>
    <div class="wrap">
        <h2>Event kunde inte skapat</h2>
        <div class="innerWrapper">
            <p>Ditt event kunde tyvärr inte skapas. Kontakta administratören.</p>
        </div>
    </div>    
    <?php endif; ?>
<?php endif; ?>