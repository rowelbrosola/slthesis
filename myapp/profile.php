<?php
$active = 'users';
require_once 'init.php';
use App\User;
use App\UserProfile;
use App\Session;
use App\Role;
use App\Status;
use App\Unit;
User::isLogged();
$roles = Role::all();
$status = Status::all();
$units = Unit::all();
$selected_user = User::with('profile', 'role', 'profile.advisor', 'profile.unit', 'profile.status')->find($_GET['id']);
$selected_user_advisor = UserProfile::where('user_id', $_GET['id'])->with('advisor')->get();
$advisors = User::whereIn('role_id', [4, 2, 3])->where('id', '!=', Session::get('user_id'))->with('profile')->get();
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    User::updateUser($_POST);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Profile - Personal Production and Client Monitoring System for Financial Advisors</title>
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
        <style>
            ul.timeline {
                list-style-type: none;
                position: relative;
            }
            ul.timeline:before {
                content: ' ';
                background: #d4d9df;
                display: inline-block;
                position: absolute;
                left: 29px;
                width: 2px;
                height: 100%;
                z-index: 400;
            }
            ul.timeline > li {
                margin: 20px 0;
                padding-left: 20px;
            }
            ul.timeline > li:before {
                content: ' ';
                background: white;
                display: inline-block;
                position: absolute;
                border-radius: 50%;
                border: 3px solid #22c0e8;
                left: 20px;
                width: 20px;
                height: 20px;
                z-index: 400;
            }
        </style>
    </head>
    <body id="app-container" class="menu-default show-spinner">
        <?php include 'partials/header.php' ?>
        <?php include 'partials/sidebar.php' ?>
        <main>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <?php include 'partials/message.php' ?>
                        <h1>Profile</h1>
                        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                            <ol class="breadcrumb pt-0">
                                <li class="breadcrumb-item"><a href="users.php">Users</a></li>
                                <!-- <li class="breadcrumb-item"><a href="#">Library</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Data</li> -->
                            </ol>
                        </nav>
                        <div class="separator mb-5"></div>
                    </div>
                    <div class="col-3">
                        <div class="card" style="width: 18rem;">
                            <img src="img/no-photo.png" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title"><?= $selected_user->profile->firstname.' '.$selected_user->profile->lastname  ?></h5>
                                <p class="card-text"><?= $selected_user->email ?></p>
                                <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Profile Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-policy-tab" data-toggle="pill" href="#pills-policy" role="tab" aria-controls="pills-policy" aria-selected="false">Policy Info</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                <form method="POST">
                                    <div class="form-group">
                                        <label>Advisor</label> 
                                        <select class="form-control select2-single" name="advisor" data-width="100%">
                                            <option label="&nbsp;">&nbsp;</option>
                                            <?php foreach($advisors as $key => $value): ?>
                                                <option
                                                    <?= isset($selected_user_advisor[0]->advisor->user_id) && $value->id === $selected_user_advisor[0]->advisor->user_id
                                                        ? 'selected'
                                                        : null
                                                    ?>
                                                    value="<?= $value->id ?>">
                                                    <?= $value->profile->firstname.' '.$value->profile->lastname ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Unit</label>
                                        <select class="form-control select2-single" name="unit" data-width="100%">
                                            <option label="&nbsp;">&nbsp;</option>
                                            <?php foreach($units as $key => $value): ?>
                                                <option
                                                    <?= isset($selected_user->profile->unit->id) && $value->id === $selected_user->profile->unit->id
                                                        ? 'selected'
                                                        : null
                                                    ?>
                                                    value="<?= $value->id ?>">
                                                    <?= $value->name ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control select2-single" name="status" data-width="100%">
                                            <option label="&nbsp;">&nbsp;</option>
                                            <?php foreach($status as $key => $value): ?>
                                                <option
                                                    <?= isset($selected_user->profile->status->id) && $value->id === $selected_user->profile->status->id
                                                        ? 'selected'
                                                        : null
                                                    ?>
                                                    value="<?= $value->id ?>">
                                                    <?= $value->name ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="clientNumber">Client Number</label>
                                        <input type="text" class="form-control" name="client_number" id="clientNumber" value="<?= $selected_user->profile->client_number ?>">
                                    </div>
                                    <div class="input-group form-group position-relative info">
                                        <label>Coding Date</label>
                                        <input type="text" class="form-control datepicker" name="coding_date" value="<?= date('m/d/Y', strtotime($selected_user->profile->coding_date)) ?>" style="width: 100%;" placeholder="Coding Date" required>
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                    <input type="hidden" value="home" name="action">
                                    <input type="hidden" value="<?= $_GET['id'] ?>" name="user_id">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <form method="POST">
                                    <div class="form-group">
                                        <label for="client-firstname">First Name</label>
                                        <input type="text" class="form-control" name="firstname" id="client-firstname" value="<?= $selected_user->profile->firstname ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="client-lastname">Last Name</label>
                                        <input type="text" class="form-control" name="lastname" id="client-lastname" value="<?= $selected_user->profile->lastname ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="client-email">Email</label>
                                        <input type="email" class="form-control" name="email" id="client-email" value="<?= $selected_user->email ?>">
                                    </div>
                                    <div class="input-group form-group position-relative info">
                                        <label>Date of Birth</label>
                                        <input type="text" class="form-control datepicker" name="clientDob" value="<?= date('m/d/Y', strtotime($selected_user->profile->dob)) ?>" style="width: 100%;" required>
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                    <input type="hidden" value="profile" name="action">
                                    <input type="hidden" value="<?= $_GET['id'] ?>" name="user_id">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="pills-policy" role="tabpanel" aria-labelledby="pills-policy-tab">
                                <input type="hidden" value="policy" name="action">
                                <input type="hidden" value="<?= $_GET['id'] ?>" name="user_id">
                                <h1>No records found.</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <h4>History</h4>
                        <ul class="timeline">
                            <li>
                                <a href="#">Created account</a>
                                <a href="#" class="float-right">14 Feb, 2020</a>
                            </li>
                            <li>
                                <a href="#">Added to a unit</a>
                                <a href="#" class="float-right">14 Feb, 2020</a>
                            </li>
                            <li>
                                <a href="#">Set Status as Rookie</a>
                                <a href="#" class="float-right">14 Feb, 2020</a>
                            </li>
                        </ul>
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
        <script src="js/vendor/select2.full.js"></script>
        <script src="js/dore.script.js"></script>
        <script src="js/scripts.js"></script>
        <script>
            $('.alert-success').fadeIn('fast').fadeOut(8000);
        </script>
    </body>
</html>