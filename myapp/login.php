<?php
require_once 'init.php';
use App\Redirect;
use App\Session;
use App\User;
// if (Session::exists('user_id')) {
//     Redirect::to('index.php');
// }
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    if (isset($_POST['reset-password'])) {
        User::requestPasswordRequest($_POST);
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        User::login($email, $password);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login - Personal Production and Client Monitoring System for Financial Advisors</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
        <link rel="stylesheet" href="font/iconsmind-s/css/iconsminds.css">
        <link rel="stylesheet" href="font/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.rtl.only.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap-float-label.min.css">
        <link rel="stylesheet" href="css/main.css">
        <style>
            .modal-header .close {
                margin: 0px;
            }
        </style>
    </head>
    <body class="background show-spinner no-footer">
        <div class="fixed-background"></div>
        <main>
            <div class="container">
                <div class="row h-100">
                    <div class="col-12 col-md-10 mx-auto my-auto">
                        <div class="card auth-card">
                            <div class="position-relative image-side">
                            </div>
                            <div class="form-side">
                                <?php include 'partials/error-message.php' ?>
                                <h2>LIVE BRIGHTER UNDER THE SUN</h2>
                                <p>Please use your credentials to login.</p>
                                <h6 class="mb-4">Login</h6>
                                <form method="post">
                                    <label class="form-group has-float-label mb-4">
                                        <input class="form-control" name="email">
                                        <span>E-mail</span>
                                    </label>
                                    <label class="form-group has-float-label mb-4">
                                        <input class="form-control" type="password" name="password" placeholder="">
                                        <span>Password</span>
                                    </label>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="#" data-target="#pwdModal" data-toggle="modal">Forgot password?</a>
                                        <button class="btn btn-primary btn-lg btn-shadow" type="submit">LOGIN</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <div id="pwdModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="text-center">
                                        
                                        <p>If you have forgotten your password you can reset it here.</p>
                                            <div class="panel-body">
                                                <form method="POST">
                                                    <div class="form-group">
                                                        <input class="form-control input-lg" placeholder="E-mail Address" name="email" type="email">
                                                        <input type="hidden" name="reset-password" value="true">
                                                    </div>
                                                    <input class="btn btn-lg btn-primary btn-block" value="Send My Password" type="submit">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type=""button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="js/vendor/jquery-3.3.1.min.js"></script><script src="js/vendor/bootstrap.bundle.min.js"></script><script src="js/dore.script.js"></script><script src="js/scripts.js"></script>
        <script>
             $('.alert-danger').fadeIn('fast').fadeOut(10000);
        </script>
    </body>
</html>