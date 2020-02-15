<?php
$active = 'users';
require_once 'init.php';
use App\User;
use App\UserProfile;
use App\Session;
$selected_user = User::with('profile', 'role', 'profile.advisor', 'profile.unit', 'profile.status')->find($_GET['id']);
$selected_user_advisor = UserProfile::where('user_id', $_GET['id'])->with('advisor')->get();
$advisors = User::whereIn('role_id', [4, 2, 3])->where('id', '!=', Session::get('user_id'))->with('profile')->get();
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
                        <h1>Dashboard</h1>
                        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                            <ol class="breadcrumb pt-0">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <form>
                            <div class="form-group">
                                <label>Advisor</label> 
                                <select class="form-control select2-single" data-width="100%">
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
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                    <div class="col-3">
                        <h4>History</h4>
                        <ul class="timeline">
                            <li>
                                <a target="_blank" href="https://www.totoprayogo.com/#">New Web Design</a>
                                <a href="#" class="float-right">21 March, 2014</a>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque scelerisque diam non nisi semper, et elementum lorem ornare. Maecenas placerat facilisis mollis. Duis sagittis ligula in sodales vehicula....</p>
                            </li>
                            <li>
                                <a href="#">21 000 Job Seekers</a>
                                <a href="#" class="float-right">4 March, 2014</a>
                                <p>Curabitur purus sem, malesuada eu luctus eget, suscipit sed turpis. Nam pellentesque felis vitae justo accumsan, sed semper nisi sollicitudin...</p>
                            </li>
                            <li>
                                <a href="#">Awesome Employers</a>
                                <a href="#" class="float-right">1 April, 2014</a>
                                <p>Fusce ullamcorper ligula sit amet quam accumsan aliquet. Sed nulla odio, tincidunt vitae nunc vitae, mollis pharetra velit. Sed nec tempor nibh...</p>
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
    </body>
</html>