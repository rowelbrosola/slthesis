<?php
$active = 'users';
?>
<!DOCTYPE html>
<html lang="en">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->
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
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body id="app-container" class="menu-default show-spinner">
        <?php include 'partials/header.php' ?>
        <?php include 'partials/sidebar.php' ?>
        <main>
            <div class="container bootstrap snippet">
                <div class="row">
                    <div class="col-sm-10">
                        <h1>User info</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <!--left col-->
                        <div class="text-center">
                            <img src="img/profile-pic-l.jpg" class="avatar img-circle img-thumbnail" alt="avatar">
                            <h6>Upload a different photo...</h6>
                            <input type="file" class="text-center center-block file-upload">
                        </div>
                        </hr><br>
                    </div>
                    <!--/col-3-->
                    <div class="col-sm-9">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#home">Profile</a></li>
                            <li><a data-toggle="tab" href="#messages">Policy Info</a></li>
                            <li><a data-toggle="tab" href="#settings">Reports</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="home">
                                <hr>
                                <form class="form" action="##" method="post" id="registrationForm">
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="first_name">
                                                <h4>First name</h4>
                                            </label>
                                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="first name" title="enter your first name if any.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="last_name">
                                                <h4>Last name</h4>
                                            </label>
                                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="last name" title="enter your last name if any.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="phone">
                                                <h4>Phone</h4>
                                            </label>
                                            <input type="text" class="form-control" name="phone" id="phone" placeholder="enter phone" title="enter your phone number if any.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="mobile">
                                                <h4>Mobile</h4>
                                            </label>
                                            <input type="text" class="form-control" name="mobile" id="mobile" placeholder="enter mobile number" title="enter your mobile number if any.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="email">
                                                <h4>Email</h4>
                                            </label>
                                            <input type="email" class="form-control" name="email" id="email" placeholder="you@email.com" title="enter your email.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="email">
                                                <h4>Location</h4>
                                            </label>
                                            <input type="email" class="form-control" id="location" placeholder="somewhere" title="enter a location">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="password">
                                                <h4>Password</h4>
                                            </label>
                                            <input type="password" class="form-control" name="password" id="password" placeholder="password" title="enter your password.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="password2">
                                                <h4>Verify</h4>
                                            </label>
                                            <input type="password" class="form-control" name="password2" id="password2" placeholder="password2" title="enter your password2.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <br>
                                            <button class="btn btn-lg btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                                            <button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                            </div>
                            <!--/tab-pane-->
                            <div class="tab-pane" id="messages">
                                <h2></h2>
                                <hr>
                                <form class="form" action="##" method="post" id="registrationForm">
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="first_name">
                                                <h4>First name</h4>
                                            </label>
                                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="first name" title="enter your first name if any.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="last_name">
                                                <h4>Last name</h4>
                                            </label>
                                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="last name" title="enter your last name if any.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="phone">
                                                <h4>Phone</h4>
                                            </label>
                                            <input type="text" class="form-control" name="phone" id="phone" placeholder="enter phone" title="enter your phone number if any.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="mobile">
                                                <h4>Mobile</h4>
                                            </label>
                                            <input type="text" class="form-control" name="mobile" id="mobile" placeholder="enter mobile number" title="enter your mobile number if any.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="email">
                                                <h4>Email</h4>
                                            </label>
                                            <input type="email" class="form-control" name="email" id="email" placeholder="you@email.com" title="enter your email.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="email">
                                                <h4>Location</h4>
                                            </label>
                                            <input type="email" class="form-control" id="location" placeholder="somewhere" title="enter a location">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="password">
                                                <h4>Password</h4>
                                            </label>
                                            <input type="password" class="form-control" name="password" id="password" placeholder="password" title="enter your password.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="password2">
                                                <h4>Verify</h4>
                                            </label>
                                            <input type="password" class="form-control" name="password2" id="password2" placeholder="password2" title="enter your password2.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <br>
                                            <button class="btn btn-lg btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                                            <button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!--/tab-pane-->
                            <div class="tab-pane" id="settings">
                                <hr>
                                <form class="form" action="##" method="post" id="registrationForm">
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="first_name">
                                                <h4>First name</h4>
                                            </label>
                                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="first name" title="enter your first name if any.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="last_name">
                                                <h4>Last name</h4>
                                            </label>
                                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="last name" title="enter your last name if any.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="phone">
                                                <h4>Phone</h4>
                                            </label>
                                            <input type="text" class="form-control" name="phone" id="phone" placeholder="enter phone" title="enter your phone number if any.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="mobile">
                                                <h4>Mobile</h4>
                                            </label>
                                            <input type="text" class="form-control" name="mobile" id="mobile" placeholder="enter mobile number" title="enter your mobile number if any.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="email">
                                                <h4>Email</h4>
                                            </label>
                                            <input type="email" class="form-control" name="email" id="email" placeholder="you@email.com" title="enter your email.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="email">
                                                <h4>Location</h4>
                                            </label>
                                            <input type="email" class="form-control" id="location" placeholder="somewhere" title="enter a location">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="password">
                                                <h4>Password</h4>
                                            </label>
                                            <input type="password" class="form-control" name="password" id="password" placeholder="password" title="enter your password.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="password2">
                                                <h4>Verify</h4>
                                            </label>
                                            <input type="password" class="form-control" name="password2" id="password2" placeholder="password2" title="enter your password2.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <br>
                                            <button class="btn btn-lg btn-success pull-right" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                                            <!--<button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button>-->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--/tab-pane-->
                    </div>
                    <!--/tab-content-->
                </div>
                <!--/col-9-->
            </div>
            <!--/row-->
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
        <script src="js/scripts.js"></script>
    </body>
</html>