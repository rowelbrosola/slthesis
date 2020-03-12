<?php
require_once 'init.php';
use App\User;
use App\Policy;
use App\Benefit;
User::isLogged();
$active = 'clients';
$policies = Policy::all();
$benefits = Benefit::all();
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    User::addClient($_POST);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Add Client - Personal Production and Client Monitoring System for Financial Advisors</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
        <link rel="stylesheet" href="font/iconsmind-s/css/iconsminds.css">
        <link rel="stylesheet" href="font/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.rtl.only.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap-datepicker3.min.css">
        <link rel="stylesheet" href="css/vendor/perfect-scrollbar.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/vendor/select2.min.css">
        <link rel="stylesheet" href="css/vendor/select2-bootstrap.min.css">
        <link rel="stylesheet" href="css/add-fa.css">
        <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    </head>
    <body id="app-container" class="menu-default show-spinner">
        <?php include 'partials/header.php' ?>
        <?php include 'partials/sidebar.php' ?>
        <main>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h1>Add a client</h1>
                        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                            <ol class="breadcrumb pt-0">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                            </ol>
                        </nav>
                        <form id="regForm" method="POST">
                            <h1 id="title">Basic Information:</h1>
                            <!-- One "tab" for each step in the form: -->
                            <div class="tab">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="firstName">First Name</label>
                                            <input type="text" class="form-control" name="firstname" oninput="this.className = ''" id="firstName" placeholder="First Name">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="lastName">Last Name</label>
                                            <input type="text" class="form-control" name="lastname" oninput="this.className = ''" id="lastName" placeholder="Last Name">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Gender</label> 
                                            <select class="form-control select2" data-width="100%" name="gender">
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" name="email" oninput="this.className = ''" id="email" placeholder="Email">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group input-group date">
                                            <label>Date of Birth</label>
                                            <input type="text" class="form-control" name="bod" oninput="this.className = ''" style="width: 100%;" placeholder="Date of Birth">
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group input-group date">
                                            <label>Coding Date</label>
                                            <input type="text" class="form-control" oninput="this.className = ''"  name="coding_date" style="width: 100%;" placeholder="Coding Date">
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab">
                                <div class="form-group">
                                    <label>Product</label> 
                                    <select class="form-control select2" data-width="100%" name="product">
                                        <option label="&nbsp;">&nbsp;</option>
                                        <?php foreach($policies as $key => $value): ?>
                                        <option value="<?= $value->id ?>"><?= $value->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Benefits</label> 
                                    <select class="form-control select2-multiple" multiple="multiple" data-width="100%" name="benefits[]">
                                        <?php foreach($benefits as $key => $value): ?>
                                        <option value="<?= $value->id ?>"><?= $value->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="annualPremium">Annual Premium</label>
                                            <input type="text" class="form-control" name="annual_premium" oninput="this.className = ''" id="annualPremium" placeholder="Annual Premium">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Mode of Payment</label> 
                                            <select class="form-control select2" data-width="100%" name="mode_of_payment">
                                                <option value="Annual">Annual</option>
                                                <option value="Semi-Annual">Semi-Annual</option>
                                                <option value="Quarterly">Quarterly</option>
                                                <option value="Monthly">Monthly</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group input-group date">
                                            <label>Issue Date</label>
                                            <input type="text" class="form-control" oninput="this.className = ''"  name="issue_date" style="width: 100%;" placeholder="Issue Date">
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="overflow:auto;">
                                <div style="float:right;">
                                    <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                                    <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
                                </div>
                            </div>
                            <!-- Circles which indicates the steps of the form: -->
                            <div style="text-align:center;margin-top:40px;">
                                <span class="step"></span>
                                <span class="step"></span>
                            </div>
                        </form>
                        <div class="separator mb-5"></div>
                    </div>
                </div>
            </div>
        </main>
        <script src="js/vendor/jquery-3.3.1.min.js"></script>
        <script src="js/vendor/bootstrap.bundle.min.js"></script>
        <script src="js/vendor/perfect-scrollbar.min.js"></script>
        <script src="js/vendor/mousetrap.min.js"></script>
        <script src="js/vendor/bootstrap-datepicker.js"></script>
        <script src="js/vendor/select2.full.js"></script>
        <script src="js/dore.script.js"></script>
        <script src="js/scripts.js"></script>
        <script src="js/add-fa.js"></script>
        
    </body>
</html>
