<?php
$active = 'policies';
require_once 'init.php';
use App\Policy;
$policies = Policy::all();
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    if (isset($_POST['policy_id'])) {
        Policy::updatePolicy($_POST);
    } else {
        Policy::add($_POST);
    }
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
                        <h1>Policies</h1>
                        <div class="top-right-button-container">
                            <button type="button" class="btn btn-outline-primary btn-lg top-right-button mr-1" data-toggle="modal" data-target="#rightModal">ADD NEW</button>
                            <div class="modal fade modal-right" id="rightModal" tabindex="-1" role="dialog" aria-labelledby="rightModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rightModalLabel">Add a Policy</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Submitting below form will add the data to Data Table and rows will be updateda.</p>
                                            <form class="tooltip-right-top" id="addToDatatableForm" method="POST" novalidate>
                                                <div class="form-group position-relative info">
                                                    <label>Policy</label>
                                                    <input type="text" class="form-control" name="policy" placeholder="Enter Policy" required>
                                                </div>
                                                <div class="form-group position-relative info">
                                                    <label>Benefits</label>
                                                    <input type="text" class="form-control" name="benefits" placeholder="Enter Benefits" required>
                                                </div>
                                                <div class="form-group position-relative info">
                                                    <label>Face Amount</label>
                                                    <input type="text" class="form-control" name="face_amount" placeholder="Enter Face Amount" required>
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
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Policy</th>
                                                <th scope="col">Benefits</th>
                                                <th scope="col">Face Amount</th>
                                                <th scope="col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php if($policies->count()): ?>
                                        <?php foreach($policies as $key => $value): ?>
                                            <tr>
                                                <td><?= $value->name ?></td>
                                                <td><?= $value->benefits ?></td>
                                                <td><?= '&#8369;'.number_format($value->face_amount) ?></td>
                                                <td>
                                                    <button class="edit" id="<?= 'edit-'.$value->id ?>"><i class="iconsminds-file-edit">Edit</i></button>
                                                    <button class="delete" id="<?= 'delete-'.$value->id ?>"><i class="iconsminds-folder-delete">Delete</i></button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <h1>No records found.</h1>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal modal-action" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Policy Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="policy_modal">
                            <div class="form-group">
                                <label for="inputPolicy">Policy</label>
                                <input type="text" class="form-control" id="inputPolicy" name="policy" placeholder="Enter Policy">
                            </div>
                            <div class="form-group">
                                <label for="inputBenefits">Benefits</label>
                                <input type="text" class="form-control" id="inputBenefits" name="benefits" placeholder="Enter Benefits">
                            </div>
                            <div class="form-group">
                                <label for="inputFaceAmount">Face Amount</label>
                                <input type="text" class="form-control" id="inputFaceAmount" name="face_amount" placeholder="Enter Face Amount">
                            </div>
                            <input type="hidden" name="policy_id" id="policy_id">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" form="policy_modal">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            $('#addToDatatable').click(function () {
                $('#addToDatatableForm').submit();
            })
            $('.edit').click(function() {
                let id = $(this).attr('id')
                $.ajax({
                    url:"functions/fetch-policy-details.php",
                    type:"POST",
                    data:{id},
                    success:function(data) {
                        var parsed = JSON.parse(data)
                        $('#inputPolicy').val(parsed.name);
                        $('#inputBenefits').val(parsed.benefits);
                        $('#inputFaceAmount').val(parsed.face_amount);
                        $('#policy_id').val(parsed.id); 
                    }
                })
                $('.modal-action').modal('toggle');
            })
            $('.delete').click(function() {
                $('.modal-action').modal('toggle');
            })
            $('.alert-success').fadeIn('fast').fadeOut(8000);
        </script>
    </body>
</html>