<?php
$active = 'users';
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
$clients = User::with('profile', 'role', 'profile.advisor', 'profile.unit', 'profile.status')->get();
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
                            <button type="button" class="btn btn-outline-primary btn-lg top-right-button mr-1" data-toggle="modal" data-target="#rightModal">ADD NEW</button>
                            <div class="modal fade modal-right" id="rightModal" tabindex="-1" role="dialog" aria-labelledby="rightModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rightModalLabel">Add Client</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Submitting below form will add the data to Data Table and rows will be updateda.</p>
                                            <form class="tooltip-right-top" id="addToDatatableForm" method="POST" novalidate>
                                                <?php if($logged_user->role_id != 2): ?>
                                                <div class="form-group position-relative">
                                                    <label>Role</label>
                                                    <select class="form-control select2-single role" name="role" data-width="100%" required>
                                                        <option value="">Select Role</option>
                                                        <?php foreach($roles as $key => $value): ?>
                                                            <?php if($value->id !== 1): ?>
                                                            <option value="<?= $value->id ?>"><?= $value->name ?></option>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <?php endif; ?>
                                                <?php if($logged_user->role_id != 2): ?>
                                                <div class="form-group position-relative info advisor">
                                                    <label>Advisor Name</label>
                                                    <select class="form-control select2-single" name="advisor" data-width="100%" required>
                                                        <option value="">Select Advisor</option>
                                                        <?php foreach($advisors as $key => $value): ?>
                                                            <option value="<?= $value->id ?>"><?= $value->profile->firstname.' '.$value->profile->lastname ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <?php endif; ?>
                                                <div class="form-group position-relative info">
                                                    <label>Email</label>
                                                    <input type="text" class="form-control" name="email" placeholder="Email" required>
                                                </div>
                                                <div class="form-group position-relative info">
                                                    <label>First Name</label>
                                                    <input type="text" class="form-control" name="firstname" placeholder="First Name" required>
                                                </div>
                                                <div class="form-group position-relative info">
                                                    <label>Last Name</label>
                                                    <input type="text" class="form-control" name="lastname" placeholder="Last Name" required>
                                                </div>
                                                <?php if($logged_user->role_id != 2): ?>
                                                <div class="form-group position-relative info unit-select">
                                                    <label>Unit Name</label>
                                                    <select class="form-control select2-single unit-selection" name="unit" data-width="100%">
                                                        <option value="">Select Unit</option>
                                                        <?php foreach($units as $key => $value): ?>
                                                            <option value="<?= $value->id ?>"><?= $value->name ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group position-relative info">
                                                    <label>Advisor Code</label>
                                                    <input type="text" class="form-control" name="advisor_code" placeholder="Advisor Code" required>
                                                </div>
                                                <?php endif; ?>
                                                <div class="form-group position-relative info">
                                                    <label>Status</label> 
                                                    <select class="form-control select2-single" name="status" data-width="100%" required>
                                                        <option value="">Select Status</option>
                                                        <?php foreach($status as $key => $value): ?>
                                                        <option value="<?= $value->id ?>"><?= $value->name ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="input-group date form-group position-relative info">
                                                    <label>Coding Date</label>
                                                    <input type="text" class="form-control" name="coding_date" style="width: 100%;" placeholder="Coding Date" required>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="#" class="btn btn-primary btn-multiple-state not-active" id="addToDatatable">
                                                <div class="spinner d-inline-block">
                                                    <div class="bounce1"></div>
                                                    <div class="bounce2"></div>
                                                    <div class="bounce3"></div>
                                                </div>
                                                <span class="icon success" data-toggle="tooltip" data-placement="top" title="Everything went right!"><i class="simple-icon-check"></i> </span>
                                                <span class="icon fail" data-toggle="tooltip" data-placement="top" title="Something went wrong!"><i class="simple-icon-exclamation"></i> </span>
                                                <span class="label">Done</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                            <th>Advisor</th>
                                            <th>Unit Name</th>
                                            <th>Status</th>
                                            <th>Role</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        include 'account-data.php';
                                        foreach ($clients as $key => $value):
                                    ?>
                                        <tr>
                                            <td><a href="profile.php?id=<?= $value->id.'&tab=home' ?>"><?= $value->profile->firstname.' '.$value->profile->lastname ?></a></td>
                                            <td><?= $value->email ?></td>
                                            <td><a href="profile.php?id=<?= isset($value->profile->advisor) ? $value->profile->advisor->user_id.'&tab=home' : null ?>"><?=
                                                isset($value->profile->advisor)
                                                ? $value->profile->advisor->firstname.' '.$value->profile->advisor->lastname
                                                : null
                                                ?></a>
                                            </td>
                                            <td><?=
                                                isset($value->profile->unit->name)
                                                ? $value->profile->unit->name
                                                : null
                                                ?>
                                            </td>
                                            <td><?=
                                                isset($value->profile->status)
                                                ? $value->profile->status->name
                                                : null
                                                ?>
                                            </td>
                                            <td><?= $value->role->name ?></td>
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