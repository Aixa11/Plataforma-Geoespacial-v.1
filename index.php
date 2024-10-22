<?php
session_start();
unset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .half {
            display: flex;
            flex-wrap: wrap;
        }

        .bg {
            flex: 1;
            height: 100vh;
            background-size: cover;
            background-position: center center;
        }

        .contents {
            flex: 1;
            padding: 4em;
        }
    </style>
</head>
<body>
    <div class="half">
        <div class="bg order-1 order-md-2" style="background-image: url('mg/bg_1.jpg');"></div>
        <div class="contents order-2 order-md-1">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-5">
                        <div class="form-block">
                            <div class="text-center mb-2">
                                <p class="card-title text-center mb-3 fw-light fs-4">LOG IN</p>
                                <div class="logo-image mb-lg-5" align="center" height="100" width="100px">
                                    <img src="mg/veng.svg" alt="Logo" title="Logo">
                                </div>
                            </div>
                            <form action="login.php" method="post">
                                <?php if (isset($_SESSION['login_error'])): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $_SESSION['login_error']; ?>
                                    </div>
                                    <?php unset($_SESSION['login_error']); ?>
                                <?php endif; ?>
                                <div class="form-group first">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" placeholder="Your User" id="username" name="username">
                                </div>
                                <div class="form-group last mb-3">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" placeholder="Your Password" id="password" name="password">
                                </div>
                                <div class="d-sm-flex mb-5 align-items-center">
                                    <label class="control control--checkbox mb-3 mb-sm-0">
                                        <span class="caption">Remember me</span>
                                        <input type="checkbox" checked="checked"/>
                                        <div class="control__indicator"></div>
                                    </label>
                                    <span class="ml-auto"><a href="#" class="forgot-pass">Forgot Password</a></span>
                                </div>
                                <input type="submit" value="Log In" class="btn btn-block btn-secondary">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
