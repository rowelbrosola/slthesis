<?php
require_once '../init.php';
use App\Event;

if(isset($_POST["id"])) {
    $event = Event::find($_POST['id']);
    $event->delete();
}
?>

