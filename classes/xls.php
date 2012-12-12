<?php
require_once(dirname(__FILE__). '/../../../../wp-config.php');

$event = isset($_GET["event"]) ? $_GET["event"] : null;
$name = $_GET["name"];

if($event)
{
    # filename for download
    $filename = isset($name) ? "Deltagarlista " . $name . ".xls" : "Deltagarlista event.xls";
    # set header for download
    //header("Content-Type: text/plain; charset=UTF-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Content-Type: application/vnd.ms-excel; charset=ISO-8859-1");
    # get members    
    $eventmanager = new EventManager();
    $users = $eventmanager->get_users_for_event($event);
    # iterate thru members and add them to an array
    $membersArray = array();
    foreach($users as $u):
        array_push($membersArray, array(
                                        "Namn" => $u->name,
                                        "Mail" => $u->email,
                                        "Antal" => $u->nr_to_come,
                                        "Kommentar" => $u->comment
        ));
    endforeach;
    # print all the members
    $flag = false;
    foreach($membersArray as $row)
    {
        if(!$flag)
        {
            # display field/column names as first row
            echo implode("\t", array_keys($row)) . "\r\n";
            $flag = true;
        }
        array_walk($row, 'cleanData');
        echo implode("\t", array_values($row)) . "\r\n";     
    }    
}

function cleanData(&$str)
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"'))
        $str = '"' . str_replace('"', '""', $str) . '"';
    $str = mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
}