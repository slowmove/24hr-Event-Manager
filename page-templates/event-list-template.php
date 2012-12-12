<?php
/**
 * Template Name: Eventlista
 */
?>

<?php get_header() ?>
<?php get_sidebar(); ?>

<div id="content">                                        
    <h1>Event</h1>
    <div id="em-wrapper">
        <div class="contentText">
        <ul class="eventlist">
        <?php
        $eventmanager = new EventManager();
        $events = $eventmanager->get_upcoming_events();
        foreach($events as $e):
        ?>
        <li>
            <a href="<?php echo get_permalink($e->pageid); ?>">
                <span class="details"><?php echo date("d M Y", strtotime($e->time)); ?></span>
                <span class="details"><?php echo $e->name ?></span>
                <span class="details"><?php echo $e->address . ", " . $e->city; ?></span>
            </a>
        </li> 
        <?php endforeach; ?>
        </ul>
        </div>
    </div>

<?php get_footer(); ?>