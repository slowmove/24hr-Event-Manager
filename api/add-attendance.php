<?php

	require_once(dirname(__file__) . '/../../../../wp-config.php');

    $name = $_POST["name"];
    $email = $_POST["email"];
    $nr_to_come = $_POST["nr_to_come"];
    $interested_in_more = $_POST["interested_in_more"] == "on" ? 1 : 0;
    $comment = $_POST["comment"];
    $user_id = $_POST["user_id"];
    $event_id = $_POST["event_id"];
    
    if ( $name && $email && $event_id ) 
    {
            // change status to "Väntar på att skickas"
            $eventmanager = new EventManager();
            $result = $eventmanager->add_user_to_event($event_id, $user_id, $name, $email, $nr_to_come, $interested_in_more, $comment);

        	// get the response
        	$response = $result;
            // write it as json
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/json');              
    }
    else
    {
        $response = array('error' => true, 'message' => 'not enough details');
        header('HTTP/1.1 500 Internal Server Error');
    }
    
    echo json_encode($response);          
?>