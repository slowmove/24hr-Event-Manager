<?php if( !$_POST["name"] ): ?>
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
                <label for="description">Kort beskrivning</label>
                <textarea id="description" name="description" placeholder="Kort Beskrivning"></textarea>
                <br/>
                <label for="content">Information</label>
                <textarea id="content" name="content" placeholder="Information"></textarea>
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
    $content = $_POST["content"];
    $places = $_POST["places"];
    $res = $eventmanager->create_event($name, $address, $city, $time, $description, $content, $places);
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