<?php if( !$_POST["name"] ): ?>
    <div class="wrap">
        <script type="text/javascript" src="<?php echo WP_PLUGIN_URL ?>/24hr-Event-Manager/tiny_mce/tiny_mce.js"></script>
        
        <script type="text/javascript">
        tinyMCE.init({
            theme : "advanced",
            mode : "exact",
            elements : "description, content, content2",
            height : "320"
        });
        </script>        
        <h2>Skapa Event</h2>
        <div class="innerWrapper">
            <form action="" method="post">
                <input type="text" name="name" placeholder="Namn" />
                <br/>
                <input type="text" name="address" placeholder="Adress" />
                <br/>
                <input type="text" name="city" placeholder="Stad" />
                <br/>
                <label for="eventdate">Datum</label>
                <input type="date" id="eventdate" name="eventdate" placeholder="YYYY-mm-dd" />
                <br/>
                <label for="starttime">Starttid</label>
                <input type="text" id="startime" name="starttime" placeholder="HH:mm" />
                <br/>
                <label for="endtime">Sluttid</label>
                <input type="text" id="endtime" name="endtime" placeholder="HH:mm" />
                <br/>
                <label for="description">Kort beskrivning</label>
                <textarea id="description" name="description" placeholder="Kort Beskrivning"></textarea>
                <br/>
                <label for="content">Information vänsterfält</label>
                <textarea id="content" name="content" placeholder="Information"></textarea>
                <br/>
                <label for="content2">Information högerfält</label>
                <textarea id="content2" name="content2" placeholder="Information"></textarea>
                <br/>
                <input type="text" name="places" placeholder="Antal platser" />
                <br/>
                <input type="submit" value="Spara event" />
            </form>
        </div>
    </div>    
<?php else: ?>
    <?php
    $eventmanager = new EventManager();
    $name = $_POST["name"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    
    $eventdate = $_POST["eventdate"];
    $starttime = $_POST["starttime"];
    $endtime = $_POST["endtime"];
    
    $description = $_POST["description"];
    $content = $_POST["content"];
    $content2 = $_POST["content2"];
    $places = $_POST["places"];
    $res = $eventmanager->create_event($name, $address, $city, $eventdate, $starttime, $endtime, $description, $content, $content2, $places);
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