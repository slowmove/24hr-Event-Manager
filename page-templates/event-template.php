<?php
/**
 * Template Name: Event
 */
?>

<?php get_header() ?>
<?php get_sidebar(); ?>

            <div id="content">
                <?php
                $person = get_current_user_id();
                $eventmanager = new EventManager();
                global $post;
                $event = $eventmanager->get_event_by_pageid($post->ID);
                $event_id = $event->id;
                $booked = $eventmanager->is_attendance($event_id, $person) || $_GET["mail"];
                $event = $eventmanager->get_event($event_id);
                $places_taken = $eventmanager->get_number_of_users_for_event($event_id);
                ?>
                
                
                <?php if (have_posts()) : ?>
                            
                <?php while (have_posts()) : the_post(); ?>

                        <h1><?php echo $event->name ?></h1>
                        
                        <div class="progress">
                          <div class="bar" style="width:<?php echo ($places_taken/$event->places)*100 ?>%;"> </div>
                            <?php if ( $event->places > 0 ): ?>
                                <span><?php echo $event->places - $places_taken; ?> platser kvar</span>
                            <?php else: ?>
                                <span>Anmälan öppnas två veckor innan eventet</span>
                            <?php endif; ?>
                        </div>                        
                        
                        <div id="eventInfoBox">
                            <div id="eventInfoHolder">
                                <div id="eventInfoText">
                                    <div class="leftCol">
                                        <p>
                                            <label class="headline">Tidpunkt:</label>
                                            <?php echo date("l, j F Y", strtotime($event->eventdate)) ?>
                                            <br/>
                                            kl <?php echo date("H:i", strtotime($event->starttime)) ?> - <?php echo date("H:i", strtotime($event->endtime)) ?>
                                        </p>
                                        <p>
                                            <label class="headline">Plats:</label>
                                            <?php echo $event->address ?><br />                                
                                            <?php echo $event->city ?>                                    
                                        </p>
                                    </div>
                                    <div class="rightCol">
                                        <label class="headline">Kort info:</label>
                                        <?php echo $event->description ?>
                                    </div>
                                </div>
                            </div>
                        </div>
            
                        <div class="contentText" >
                            <?php if( strlen(stripslashes($event->content)) > 1 ): ?>
                                <div class="twoCol">
                                    <h2>Information</h2>
                                    <p><?php echo str_replace("../", "/",stripslashes($event->content)) ?></p>
                                </div>
                                <div class="twoCol">
                                    <p><?php echo str_replace("../", "/",stripslashes($event->content2)) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div id="bookingArea">
                            <?php if (is_user_logged_in() && $event->places > 0): ?>
                                <h2>Anmälan</h2>
                                <?php if ($booked != true && $event->places > $places_taken): ?>
                                    <p>Är du allergisk eller har något annat att meddela? Ange det här.</p>
                                    <?php                                        
                                    $user = new WP_User( get_current_user_id() );
                                    $email = $user->user_email;
                                    ?>
                                    <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>?mail=<?php echo urlencode($email) ?>">
                                        <textarea name="comment" class="text"></textarea>
                                        <input type="hidden" value="booking" name="booking" />
                                        <img class="divider" src="<?php bloginfo('template_url'); ?>/markup/imgs/line-360.png" />                                        
                                        <input type="submit" class="button orangeBtn" value="Skicka anmälan" />                        
                                    </form>
                                <?php elseif ($booked != true && $event->places <= $places_taken): ?>
                                    <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>?mail=<?php echo urlencode($email) ?>">
                                        <textarea name="comment" class="text"></textarea>
                                        <input type="hidden" value="reserving" name="booking" />
                                        <img class="divider" src="<?php bloginfo('template_url'); ?>/markup/imgs/line-360.png" />                                        
                                        <input type="submit" class="button orangeBtn" value="Anmäl dig på reservlistan" />                        
                                    </form>                                
                                <?php else: ?>
                                    <?php
                                    $user = new WP_User( get_current_user_id() );
                                    $user_id = get_current_user_id();
                                    $name = $user->user_firstname . ' ' . $user->user_lastname;
                                    $email = $user->user_email;                                                                        
                                    $bookingmail = urldecode($_GET["mail"]);
                                    if($bookingmail != "" && $_POST["booking"] == "booking")
                                    {
                                        $book = $eventmanager->add_user_to_event($event_id, $user_id, $name, $email, 1, 0, $_POST["comment"]);
                                        
                                        if ( $book ):
                                            echo "<p>Du är nu anmäld till eventet och ett bekräftelsemail har skickats till din mailadress. Välkommen!</p>";
                                        else:
                                            echo "<p>Du är nu anmäld till eventet. Välkommen!</p>";
                                        endif;                                            
                                    }
                                    elseif($bookingmail != "" && $_POST["booking"] == "reserving")
                                    {
                                        $reserve = $eventmanager->add_user_as_standby_to_event($event_id, $user_id, $name, $email, 1, 0, $_POST["comment"]);

                                        if ( $reserve ):
                                            echo "<p>Du är nu anmäld som reserv till eventet och ett bekräftelsemail har skickats till din mailadress.</p>";
                                        else:
                                            echo "<p>Du är nu anmäld som reserv till eventet.</p>";
                                        endif;                                           
                                    }
                                    else
                                    {
                                        echo "<p>Du är anmäld till eventet. Välkommen!</p>";
                                    }
                                    ?>                                        
                                <?php endif; ?> 
                            <?php endif; ?>                            
                        </div>
                <?php endwhile; ?>
            
                <?php else : ?>
                        <h2 class="center">Sidan kunde inte hittas.</h2>                                    
                <?php endif; ?>	                     
            </div>   

<?php get_footer(); ?>