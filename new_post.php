<?php
include 'header.php';
?>
<body class="row">
<?php include 'left_menu.php';?>
<div class="main_content col-lg-11 col-10">
    <div class="add_new_post col-9">
        <h2 class="new_post_tite">New Post</h2>
        <form action="database.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" rows="8" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary" name="add_new_post">Submit</button>
        </form>
    </div>
</div>
</body>
</html>