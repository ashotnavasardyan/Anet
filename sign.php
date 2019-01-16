<?php

if (isset($_COOKIE['user_registered_complete'])) {
    $registration_complete_class = ($_COOKIE['user_registered_complete'] === 'false') ? false : true;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="icon" href="images/logo.png" sizes="any" type="image/png">
    <title>Physmath School of Yerevan</title>
</head>
<body id="sign_in_body">
<div class="main_content row">
    <div class="sign_in_box col-lg-3 col-md-4 col-10">
        <div id="sign_in">
            <div class="logo_div">
                <img src="/svg/logo.svg" alt="Logo">
            </div>
            <form action="database.php" method="post">
                <?php
                if (isset($registration_complete_class)) {
                    if ($registration_complete_class) {
                        echo "<div class=\"alert alert-success\" role=\"alert\">
                                    <strong>Well done!</strong> You successfully has been registered
                              </div>";
                    } else {
                        echo "<div class=\"alert alert-danger\" role=\"alert\">
                                    Something went wrong
                              </div>";
                    }
                }
                ?>
                <div class="form-group">
                    <input type="text" name="username" id="enter_username" placeholder="Enter username" required>
                    <div class="form-group">
                        <input type="password" name="password" id="enter_password" placeholder="Enter password"
                               required>
                    </div>
                    <button type="submit" class="registration_submit btn" name="sign_in">Sign in</button>
                    <p class="sign_up_link">Don't have an account? <a href="#" id="change_sign_in_box">Sign up</a></p>
            </form>
        </div>
    </div>
    <div id="registration">
        <div class="logo_div">
            <img src="/svg/logo.svg" alt="Logo">
        </div>
        <form action="database.php" method="post">
            <input type="text" name="username" id="reg_username" placeholder="Enter username" required>
            <input type="password" name="password" id="reg_password" placeholder="Enter password" required>
            <input type="password" name="approve_password" placeholder="Approve password" required>
            <button type="submit" class="registration_submit btn" name="registration">Sign up</button>
        </form>
        <p class="sign_up_link">Already have an account? <a href="#" id="change_sign_up_box">Sign in</a></p>

    </div>
</div>
</div>
<script src="/js/jquery-3.3.1.min.js"></script>
<script src="/js/script.js"></script>
</body>
</html>