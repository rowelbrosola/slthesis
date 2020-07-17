<?php
require_once '../init.php';
use App\Event;

if(isset($_POST)) {
    $event = Event::find($_POST['id']);

    echo json_encode($event);
}

?>