<?php
require_once 'init.php';
use App\User;
use App\UserProfile;
use App\Payment;
use App\Session;
User::isLogged();
$active = 'productions';
$user = User::find(Session::get('user_id'));
// if ($user->role_id !== 1) {
    $clients = User::whereNull('role_id')->with('profile')->get();
// } else {
//     $clients = User::with('profile')->get();
// }
$production = Payment::with('profile', 'unit', 'policy')->get();
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    Payment::add($_POST);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Payments - Personal Production and Client Monitoring System for Financial Advisors</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
        <link rel="stylesheet" href="font/iconsmind-s/css/iconsminds.css">
        <link rel="stylesheet" href="font/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="css/vendor/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="css/vendor/datatables.responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.rtl.only.min.css">
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
                <div class="row app-row">
                    <div class="col-12">
                        <div class="mb-2">
                            <?php include 'partials/message.php' ?>
                            <h1>Production</h1>
                            <div class="top-right-button-container">
                                <button type="button" class="btn btn-outline-primary btn-lg top-right-button mr-1" data-toggle="modal" data-backdrop="static" data-target="#exampleModal">ADD NEW</button>
                                <div class="modal fade modal-right" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Add Production</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" id="production-form">
                                                    <div class="form-group position-relative info">
                                                        <label>Client Name</label> 
                                                        <select class="form-control select2-single" name="client" data-width="100%" id="clients" required>
                                                            <option value="">Select Client</option>
                                                            <?php foreach($clients as $key => $value): ?>
                                                            <option value="<?= $value->id ?>"><?= $value->profile->firstname.' '.$value->profile->lastname ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group position-relative info">
                                                        <label>Policy</label> 
                                                        <select class="form-control select2-single" name="policy" id="policy" data-width="100%" required disabled>
                                                        </select>
                                                    </div>
                                                    <div class="form-group position-relative info">
                                                        <label>Amount</label>
                                                        <input type="number" class="form-control" name="amount" placeholder="Amount" required>
                                                    </div>
                                                    <!-- <div class="form-group"><label>Details</label> <textarea class="form-control" rows="2"></textarea></div> -->
                                                    <!-- <div class="form-group">
                                                        <label>Category</label> 
                                                        <select class="form-control select2-single" data-width="100%">
                                                            <option label="&nbsp;">&nbsp;</option>
                                                            <option value="Flexbox">Flexbox</option>
                                                            <option value="Sass">Sass</option>
                                                            <option value="React">React</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Labels</label> 
                                                        <select class="form-control select2-multiple" multiple="multiple" data-width="100%">
                                                            <option value="New Framework">New Framework</option>
                                                            <option value="Education">Education</option>
                                                            <option value="Personal">Personal</option>
                                                        </select>
                                                    </div> -->
                                                    <!-- <div class="form-group">
                                                        <label>Status</label>
                                                        <div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="customCheck1"> <label class="custom-control-label" for="customCheck1">Completed</label></div>
                                                    </div> -->
                                                </form>
                                            </div>
                                            <div class="modal-footer"><button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button> <button type="button" id="submitProduction" class="btn btn-primary">Submit</button></div>
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
                        <div class="row mb-4">
                            <div class="col-12 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <table class="data-table data-table-feature payment-table">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Policy</th>
                                                    <th>Amount Paid</th>
                                                    <th>Advisor</th>
                                                    <th>Payment Date</th>
                                                    <th>Commission</th>
                                                    <th>Next Due Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($production as $key => $value): ?>
                                                <tr>
                                                    <td><?= isset($value->profile) ? $value->profile->firstname.' '.$value->profile->lastname : null ?></td>
                                                    <td><?= $value->policy->name ?></td>
                                                    <td><?= $value->amount_paid ?></td>
                                                    <td><?= '' ?></td>
                                                    <td><?= $value->created_at ?></td>
                                                    <td><?= '' ?></td>
                                                    <td><?= '' ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-menu">
                <div class="p-4 h-100">
                    <div class="scroll">
                        <!-- <p class="text-muted text-small">Status</p>
                        <ul class="list-unstyled mb-5">
                            <li class="active"><a href="?q=pending"><i class="simple-icon-refresh"></i> Pending <span class="float-right">12</span></a></li>
                            <li><a href="?q=completed"><i class="simple-icon-check"></i> Completed <span class="float-right">24</span></a></li>
                        </ul> -->
                        <!-- <p class="text-muted text-small">Categories</p>
                        <ul class="list-unstyled mb-5">
                            <li>
                                <div class="custom-control custom-checkbox mb-2"><input type="checkbox" class="custom-control-input" id="category1"> <label class="custom-control-label" for="category1">Flexbox</label></div>
                            </li>
                            <li>
                                <div class="custom-control custom-checkbox mb-2"><input type="checkbox" class="custom-control-input" id="category2"> <label class="custom-control-label" for="category2">Sass</label></div>
                            </li>
                            <li>
                                <div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="category3"> <label class="custom-control-label" for="category3">React</label></div>
                            </li>
                        </ul>
                        <p class="text-muted text-small">Labels</p>
                        <div>
                            <p class="d-sm-inline-block mb-1"><a href="#"><span class="badge badge-pill badge-outline-primary mb-1">NEW FRAMEWORK</span></a></p>
                            <p class="d-sm-inline-block mb-1"><a href="#"><span class="badge badge-pill badge-outline-theme-3 mb-1">EDUCATION</span></a></p>
                            <p class="d-sm-inline-block mb-1"><a href="#"><span class="badge badge-pill badge-outline-secondary mb-1">PERSONAL</span></a></p>
                        </div> -->
                    </div>
                </div>
                <a class="app-menu-button d-inline-block d-xl-none" href="#"><i class="simple-icon-options"></i></a>
            </div>
        </main>
        <script src="js/vendor/jquery-3.3.1.min.js"></script>
        <script src="js/vendor/bootstrap.bundle.min.js"></script>
        <script src="js/vendor/perfect-scrollbar.min.js"></script>
        <script src="js/vendor/mousetrap.min.js"></script>
        <script src="js/vendor/datatables.min.js"></script>
        <script src="js/vendor/select2.full.js"></script>
        <script src="js/dore.script.js"></script>
        <script src="js/scripts.js"></script>
        <script>
            $("#clients").change(function() {
                var user_id = $(this).children("option:selected").val();
                $.ajax({
                    url:"functions/fetch-policy.php",
                    type:"POST",
                    data:{user_id},
                    success:function(data) {
                        $("#policy").prop( "disabled", false );
                        $("#policy").html(data);
                    }
                })
            });
            $('#submitProduction').click(function () {
                $('#production-form').submit();
            })
            $('.alert-success').fadeIn('fast').fadeOut(8000);
        </script>
    </body>
</html>