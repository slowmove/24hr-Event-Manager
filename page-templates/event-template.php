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
                $booked = $eventmanager->is_attendance($event_id, $person);
                $event = $eventmanager->get_event($event_id);
                ?>


                <?php if (have_posts()) : ?>

                <?php while (have_posts()) : the_post(); ?>

                        <h1><?php echo $event->name ?></h1>

                        <div class="contentText">

                            <div class="twoCol">
                                <h2>Information</h2>
                                <p><?php echo $event->description ?></p>
                                <label class="headline">Tidpunkt</label>
                                <p><?php echo date("l, j F Y", strtotime($event->time)) ?></p>
                                <label class="headline">Plats</label>
                                <p>
                                    <?php echo $event->address ?><br />
                                    <?php echo $event->city ?>
                                </p>

                                <label class="headline">Mer information</label>
                                <p><?php echo $event->content ?></p>

                            </div>

                            <div class="twoCol">
                                <?php if (is_user_logged_in()): ?>
                                    <h2>Anmälan</h2>
                                    <?php if ($booked != true): ?>
                                        <p>Är du allergisk? Ange det här.</p>
                                        <?php
                                        $user = new WP_User( get_current_user_id() );
                                        $email = $user->user_email;
                                        ?>
                                        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>?mail=<?php echo urlencode($email) ?>">
                                            <textarea name="comment" class="text"></textarea>
                                            <input type="hidden" value="booking" name="booking" />
                                            <input type="submit" class="button" value="Skicka anmälan" />
                                        </form>
                                    <?php else: ?>
                                        <?php
                                        $bookingmail = urldecode($_GET["mail"]);
                                        if($bookingmail != "")
                                        {
                                            $message =
                                                '<html><head><title>Tack</title></head><body>Hej, <br/><br/>Du har nyligen anmält dig till ett event via Nätverket 100%.<br/>
Detta mail bekräftar din anmälan. Vi ses där.<br/><br/>Vänliga hälsningar,<br/>
Nätverket 100%</body></html>
                                            ';

                                            $mailreceiver = $bookingmail;

                                            $success = $eventmanager->html_mail($mailreceiver, "info@natverket100procent.se", "Tack för anmälan till $event->name", $message);

                                            if ( $success ):
                                                echo "<p>Du är nu anmäld till eventet och ett bekräftelsemail har skickats till din mailadress. Välkommen!</p>";
                                            else:
                                                echo "<p>Du är nu anmäld till eventet. Välkommen!</p>";
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

                        </div>
                <?php endwhile; ?>

                <?php else : ?>
                        <h2 class="center">Sidan kunde inte hittas.</h2>
                <?php endif; ?>
            </div>

<?php get_footer(); ?>