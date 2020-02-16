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
$users = User::with('profile', 'role', 'profile.advisor', 'profile.unit', 'profile.status')->get();
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    User::add($_POST);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Users - Personal Production and Client Monitoring System for Financial Advisors</title>
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
                        <h1>Users</h1>
                        <div class="top-right-button-container">
                            <button type="button" class="btn btn-outline-primary btn-lg top-right-button mr-1" data-toggle="modal" data-target="#rightModal">ADD NEW</button>
                            <div class="modal fade modal-right" id="rightModal" tabindex="-1" role="dialog" aria-labelledby="rightModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rightModalLabel">Add User</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Submitting below form will add the data to Data Table and rows will be updateda.</p>
                                            <form class="tooltip-right-top" id="addToDatatableForm" method="POST" novalidate>
                                                <div class="form-group position-relative">
                                                    <label>Role</label>
                                                    <select class="form-control select2-single" name="role" data-width="100%" required>
                                                        <option value="">Select Role</option>
                                                        <?php foreach($roles as $key => $value): ?>
                                                            <?php if($value->id !== 1): ?>
                                                            <option value="<?= $value->id ?>"><?= $value->name ?></option>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group position-relative info advisor">
                                                    <label>Advisor Name</label>
                                                    <select class="form-control select2-single" name="advisor" data-width="100%" required>
                                                        <option value="">Select Advisor</option>
                                                        <?php foreach($advisors as $key => $value): ?>
                                                            <option value="<?= $value->id ?>"><?= $value->profile->firstname.' '.$value->profile->lastname ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group position-relative info">
                                                    <label>First Name</label>
                                                    <input type="text" class="form-control" name="firstname" placeholder="First Name" required>
                                                </div>
                                                <div class="form-group position-relative info">
                                                    <label>Last Name</label>
                                                    <input type="text" class="form-control" name="lastname" placeholder="Last Name" required>
                                                </div>
                                                <div class="form-group position-relative info">
                                                    <label>Unit Name</label>
                                                    <select class="form-control select2-single" name="unit" data-width="100%" required>
                                                        <option value="">Select Unit</option>
                                                        <?php foreach($units as $key => $value): ?>
                                                            <option value="<?= $value->id ?>"><?= $value->name ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
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
                                        foreach ($users as $key => $value):
                                    ?>
                                        <tr>
                                            <td><a href="profile.php?id=<?= $value->id ?>"><?= $value->profile->firstname.' '.$value->profile->lastname ?></a></td>
                                            <td><?= $value->email ?></td>
                                            <td><?=
                                                isset($value->profile->advisor)
                                                ? $value->profile->advisor->firstname.' '.$value->profile->advisor->lastname
                                                : null
                                                ?>
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
            $(function(){

            })
            var clients = 
                [
                    {
                        name: "May",
                        index: 0,
                        id: "5a8a9bfd8bf389ba8d6bb211"
                    }, {
                        name: "Fuentes",
                        index: 1,
                        id: "5a8a9bfdee10e107f28578d4"
                    }, {
                        name: "Henderson",
                        index: 2,
                        id: "5a8a9bfd4f9e224dfa0110f3"
                    }, {
                        name: "Hinton",
                        index: 3,
                        id: "5a8a9bfde42b28e85df34630"
                    }, {
                        name: "Barrera",
                        index: 4,
                        id: "5a8a9bfdc0cba3abc4532d8d"
                    }, {
                        name: "Therese",
                        index: 5,
                        id: "5a8a9bfdedfcd1aa0f4c414e"
                    }, {
                        name: "Nona",
                        index: 6,
                        id: "5a8a9bfdd6686aa51b953c4e"
                    }, {
                        name: "Frye",
                        index: 7,
                        id: "5a8a9bfd352e2fd4c101507d"
                    }, {
                        name: "Cora",
                        index: 8,
                        id: "5a8a9bfdb5133142047f2600"
                    }, {
                        name: "Miles",
                        index: 9,
                        id: "5a8a9bfdadb1afd136117928"
                    }, {
                        name: "Cantrell",
                        index: 10,
                        id: "5a8a9bfdca4795bcbb002057"
                    }, {
                        name: "Benson",
                        index: 11,
                        id: "5a8a9bfdaa51e9a4aeeddb7d"
                    }, {
                        name: "Susanna",
                        index: 12,
                        id: "5a8a9bfd57dd857535ef5998"
                    }, {
                        name: "Beatrice",
                        index: 13,
                        id: "5a8a9bfd68b6f12828da4175"
                    }, {
                        name: "Tameka",
                        index: 14,
                        id: "5a8a9bfd2bc4a368244d5253"
                    }, {
                        name: "Lowe",
                        index: 15,
                        id: "5a8a9bfd9004fda447204d30"
                    }, {
                        name: "Roth",
                        index: 16,
                        id: "5a8a9bfdb4616dbc06af6172"
                    }, {
                        name: "Conley",
                        index: 17,
                        id: "5a8a9bfdfae43320dd8f9c5a"
                    }, {
                        name: "Nelda",
                        index: 18,
                        id: "5a8a9bfd534d9e0ba2d7c9a7"
                    }, {
                        name: "Angie",
                        index: 19,
                        id: "5a8a9bfd57de84496dc42259"
                    }
                ];
            $("#client").typeahead({
                hint: true,
                highlight: true,
                minLength: 1,
                updater: function (item) {
                    console.log(item)
                    return item;
                },
                source: clients
            })
            $('#addToDatatable').click(function () {
                $('#addToDatatableForm').submit();
            })
            $('.alert-success').fadeIn('fast').fadeOut(8000);
        </script>
    </body>
</html>