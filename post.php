<?php
include 'header.php';

$post = mysqli_fetch_assoc(get_post_by_id($_GET['post_id']));
?>

<body class="row scroll_disable">
<?php include 'left_menu.php';?>

<div class="single_post col-lg-11 col-10">
    <h2 class="title"><?php echo $post['title']?></h2>
    <?php
    if(strpos($post['image'],'.MOV') !== false || strpos($post['image'],'.mp4') !== false){
        echo "<video width='100%' controls>

            <source src=\"/media/".$post['image']."\" type='video/mp4' class=\"post_image\">
            </video>";
    }else{
        echo "<img src=\"/media/".$post['image']."\" alt=\"image\" class=\"post_image\">";
    }
    ?>
<!--    <img src="/media/--><?php //echo $post['image']?><!--" alt="image" class="post_image">-->
    <p class="single_content"><?php echo $post['content'] ?></p>
</div>
</body>
</html>