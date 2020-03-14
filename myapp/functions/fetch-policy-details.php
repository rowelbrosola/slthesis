<?php
require_once '../init.php';
use App\Policy;

if(isset($_POST)) {
    $id = $_POST['id'];
    $policy = Policy::find($id);

    echo json_encode($policy);
}

?>