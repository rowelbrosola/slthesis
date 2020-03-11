<?php
require_once '../init.php';
use App\User;

if(isset($_POST)) {
    $user = User::with('profile')->find($_POST['id']);

    echo json_encode($user);
}

?>