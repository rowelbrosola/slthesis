<?php
$active = 'clients';
require_once 'init.php';
use App\Status;
use App\Role;
use App\User;
use App\UserProfile;
use App\Unit;
use App\Session;
User::isLogged();
$roles = Role::all();
$status = Status::all();
$units = Unit::all();
$advisors = User::whereIn('role_id', [4, 2, 3])->with('profile')->get();
$user = User::find(Session::get('user_id'));
if ($user->role_id === 2) {
    $clients = User::with('profile', 'role', 'profile.advisor', 'profile.unit', 'profile.status', 'profile.latestPayment')
    ->whereHas('profile', function($q) {
        $q->where('advisor_id', Session::get('user_id'));
    })->where('role_id', 2)->get();
} else if($user->role_id === 3) {
    $clients = User::with('profile', 'role', 'profile.advisor', 'profile.unit', 'profile.status', 'profile.latestPayment')
    ->whereHas('profile', function($q) {
        $q->where('advisor_id', Session::get('user_id'));
    })->whereIn('role_id', [2,3])->get();
} else {
    $clients = User::with('profile', 'role', 'profile.advisor', 'profile.unit', 'profile.status', 'profile.latestPayment')
    ->whereHas('profile', function($q) {
        $q->where('advisor_id', Session::get('user_id'));
    })->whereNull('role_id')->get();
}

$logged_user = User::find(Session::get('user_id'));
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    User::add($_POST);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Clients - Personal Production and Client Monitoring System for Financial Advisors</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
        <link rel="stylesheet" href="font/iconsmind-s/css/iconsminds.css">
        <link rel="stylesheet" href="font/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="css/vendor/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="css/vendor/datatables.responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap-datepicker3.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.min.css">
        <link rel="stylesheet" href="css/vendor/glide.core.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.rtl.only.min.css">
        <link rel="stylesheet" href="css/vendor/component-custom-switch.min.css">
        <link rel="stylesheet" href="css/vendor/perfect-scrollbar.css">
        <link rel="stylesheet" href="css/vendor/select2.min.css">
        <link rel="stylesheet" href="css/vendor/select2-bootstrap.min.css">
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body id="app-container" class="menu-default show-spinner">
        <?php include 'partials/header.php' ?>
        <?php include 'partials/sidebar.php' ?>
        <main>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <?php include 'partials/message.php' ?>
                        <?php include 'partials/error-message.php' ?>
                        <h1>Clients</h1>
                        <div class="top-right-button-container">
                            <a href="add-financial-advisor.php" class="btn btn-outline-primary btn-lg top-right-button mr-1">ADD NEW</a>
                        </div>
                        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                            <ol class="breadcrumb pt-0">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <!-- <li class="breadcrumb-item"><a href="#">Library</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Data</li> -->
                            </ol>
                        </nav>
                        <div class="separator mb-5"></div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <table class="data-table data-table-feature payment-table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Gender</th>
                                            <th>Latest Payment</th>
                                            <th>Date of Birth</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        include 'account-data.php';
                                        foreach ($clients as $key => $value):
                                    ?>
                                        <tr>
                                            <td><a href="profile.php?id=<?= $value->id.'&tab=profile' ?>"><?= $value->profile->firstname.' '.$value->profile->lastname ?></a></td>
                                            <td><?= $value->email ?></td>
                                            <td><?= $value->profile->gender ?></td>
                                            <td><?= $value->profile->lastPayment
                                                ? date('Y-m-d', strtotime($value->profile->lastPayment->payment_date))
                                                : null ?>
                                            </td>
                                            <td><?= $value->profile->dob
                                                ? date('Y-m-d', strtotime($value->profile->dob))
                                                : null ?>
                                            </td>
                                            <td></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
        <script src="js/vendor/jquery-3.3.1.min.js"></script>
        <script src="js/vendor/bootstrap.bundle.min.js"></script>
        <script src="js/vendor/perfect-scrollbar.min.js"></script>
        <script src="js/vendor/jquery.validate/jquery.validate.min.js"></script>
        <script src="js/vendor/jquery.validate/additional-methods.min.js"></script>
        <script src="js/vendor/datatables.min.js"></script>
        <script src="js/vendor/bootstrap-datepicker.js"></script>
        <script src="js/vendor/bootstrap-tagsinput.min.js"></script>
        <script src="js/vendor/typeahead.bundle.js"></script>
        <script src="js/dore.script.js"></script>
        <script src="js/vendor/select2.full.js"></script>
        <script src="js/scripts.js"></script>
        <script>
            $('.unit-select').hide();
            $('#addToDatatable').click(function () {
                $('#addToDatatableForm').submit();
            });
            $("select.role").change(function(){
                var selectedRole = $(this).children("option:selected").val();
                if (selectedRole != 2) {
                    $('.unit-select').show();
                    $(".unit-selection").prop('required',true);
                } else {
                    $('.unit-select').hide();
                }
            });
            $('.alert-success').fadeIn('fast').fadeOut(8000);
            $('.alert-danger').fadeIn('fast').fadeOut(10000);
        </script>
    </body>
</html>