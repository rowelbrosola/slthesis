<?php
require_once '../init.php';
use App\Event;

if(isset($_POST["id"])) {
    $start_date = $_POST['start'];
    $end_date = $_POST['end'];
    Event::find($_POST['id'])
    ->update([
        'title' => $_POST['title'],
        'start_date' => $start_date,
        'end_date' => $end_date,
    ]);
}

?>