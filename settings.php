<?php
include 'header.php';
$user = get_user_by_id($_SESSION['user_id']);
if (isset($_COOKIE['user_update_complete'])) {
    $registration_complete_class = ($_COOKIE['user_update_complete'] === 'false') ? false : true;
}
?>

<body class="row scroll_disable">
<?php include 'left_menu.php';?>
<div class="main_content row col-lg-11 col-10">
    <div class="settings_container col-11 col-md-5">
        <?php
        if (isset($registration_complete_class)) {
            if ($registration_complete_class) {
                echo "<div class=\"alert alert-success\" role=\"alert\">
                                    <strong>Well done!</strong> You successfully has been registered
                              </div>";
            } else {
                $message = 'Something went wrong';
                if(isset($_COOKIE['user_update_message'])){
                    $message = $_COOKIE['user_update_message'];
                }
                echo "<div class=\"alert alert-danger\" role=\"alert\">
                                    ".$message."
                              </div>";
            }
        }
        ?>
        <form action="database.php" method="post" enctype="multipart/form-data">
            <div class="user_image">
                <img src="media/<?php echo $user['image']?>" alt="User Image" class="img-thumbnail user_image">
            </div>
            <div class="form-group">
                <label for="user_image">Image</label>
                <input type="file" name="user_image" value="" class="form-control" id="user_image">
                <label for="user_name">Name</label>
                <input type="text" value="<?php echo $user['name']?>" name="name" class="form-control" id="user_name">
                <label for="change_password">Change password</label>
                <input type="password" id="change_password" name="new_password" class="form-control">
                <input type="password" name="approve_setting_password" class="form-control">
                <button type="submit" name="update_user" class=" form-control btn-info">Submit</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>