<?php
require_once '../init.php';
use App\Event;
use App\Session;
use App\User;

// if(isset($_POST['title'])) {
    $start_date = $_POST['start'];
    $end_date = $_POST['end'];
    $start = substr($start_date, 0, 10);
    $end = substr($end_date, 0, 10);
    $start = $start.$_POST['am'];
    $end = $start.$_POST['pm'];
    if (isset($_POST['update'])) {
        $event = Event::find($_POST['id']);
        $event->title = $_POST['title'];
        $event->description = $_POST['description'];
        $event->start_date = date('Y-m-d H:i:s', strtotime($start_date));
        $event->end_date = date('Y-m-d H:i:s', strtotime($end_date));
        $event->save();
        return $_POST;
    } else {
        Event::create([
            'user_id'       => Session::get('user_id'),
            'title'         => $_POST['title'],
            'description'   => isset($_POST['description']) ? $_POST['description'] : null,
            'audience'      => isset($_POST['audience']) ? $_POST['audience'] : null,
            'start_date'    => date('Y-m-d H:i:s', strtotime($start)),
            'end_date'    => date('Y-m-d H:i:s', strtotime($end)),
        ]);
    }
// }
?>

