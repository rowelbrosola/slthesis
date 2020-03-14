<?php
require_once 'init.php';
use App\User;
use App\Redirect;

if (isset($_GET['token'])) {
    $user = User::where('token', $_GET['token'])->first();

    if($user) {
        $token_expiry = strtotime($user->token_expiry);
    
        if($token_expiry > time()) {
           $token = true;
        } else {
            Redirect::to('includes/404.php');
        }
    } else {
        Redirect::to('includes/404.php');
    }
} else {
    Redirect::to('includes/404.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    User::resetPassword($_POST);
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Reset your password</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="css/vendor/bootstrap.min.css">

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
    <style>
        html,
        body {
        height: 100%;
        }

        body {
        display: -ms-flexbox;
        display: -webkit-box;
        display: flex;
        -ms-flex-align: center;
        -ms-flex-pack: center;
        -webkit-box-align: center;
        align-items: center;
        -webkit-box-pack: center;
        justify-content: center;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
        }

        .form-signin {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: 0 auto;
        }
        .form-signin .checkbox {
        font-weight: 400;
        }
        .form-signin .form-control {
        position: relative;
        box-sizing: border-box;
        height: auto;
        padding: 10px;
        font-size: 16px;
        }
        .form-signin .form-control:focus {
        z-index: 2;
        }
        .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
        }
        .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        }
        .hidden {
            display:none;
            color: red;
        }

    </style>
  </head>

  <body class="text-center">
    <form class="form-signin" method="POST">
      <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">New Password</h1>
      <label for="inputPassword" class="sr-only">Email address</label>
      <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Type your new password" required autofocus>
      <label for="inputConfirmPassword" class="sr-only">Confirm Password</label>
      <input type="password" id="inputConfirmPassword" class="form-control" placeholder="Confirm your new password" required>
      <input type="hidden" name="user_id" value="<?= $user->id ?>">
      <p class="hidden">Passwords do not matched!</p>
      <div class="checkbox mb-3">
        <!-- <label>
          <input type="checkbox" value="remember-me"> Remember me
        </label> -->
      </div>
      <button class="btn btn-lg btn-primary btn-block change-password" type="button">Change my password</button>
      <p class="mt-5 mb-3 text-muted">&copy; 2019-2020</p>
    </form>

    <script src="js/vendor/jquery-3.3.1.min.js"></script>
    <script>
         $('.change-password').click(function() {
            const password = $("#inputPassword").val();
            const confirmPassword = $("#inputConfirmPassword").val();
            if (password && confirmPassword && password === confirmPassword) {
                $('.form-signin').submit();
            } else {
                $('.hidden').show();
            }
         });
         $("#inputPassword").focus(function() {
            $('.hidden').hide();
         })
         $("#inputConfirmPassword").focus(function() {
            $('.hidden').hide();
         })
    </script>
  </body>
</html>
