<?php
require_once '../init.php';
use App\Event;
use App\Session;
use App\User;

// if(isset($_POST['title'])) {
    $start_date = $_POST['start'];
    $end_date = $_POST['end'];
    Event::create([
        'user_id'       => Session::get('user_id'),
        'title'         => $_POST['title'],
        'description'   => isset($_POST['description']) ? $_POST['description'] : null,
        'audience'      => isset($_POST['audience']) ? $_POST['audience'] : null,
        'start_date'    => $start_date,
        'end_date'      => $end_date
    ]);
// }
?>

