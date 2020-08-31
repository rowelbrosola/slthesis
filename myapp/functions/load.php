<?php
require_once '../init.php';
use App\Event;
use App\User;

$data = [];
$results = Event::loadEvents();
$due_events = User::getDueDates();

if ($results) {
    foreach($results as $key => $value) {
        $data[] = [
            'id'   => $value['id'],
            'title'   => $value['title'],
            'start'   => $value['start_date'],
            'end'   => $value['end_date']
        ];
    }
}
if ($due_events) {
    foreach($due_events as $key => $value) {
        $data[] = [
            'id'   => 0,
            'title'   => $value->profile['firstname'].' '.$value->profile['lastname']. '\'s due date',
            'start'   => $value->premium_due_date,
            'end'   => $value->premium_due_date
        ];
    }
}
echo json_encode($data);

?>

