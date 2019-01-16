<?php
$site_url = $_SERVER['REQUEST_URI'];
?>
<div class="left_menu col-lg-1 col-md-1 col-2">
    <div class="new_message_button">
        <div><a href="/new_post.php"><img src="/svg/add_new_post.svg" alt="Add new post" class="add_post_icon icon"></a></div>
    </div>
    <div class="menu_items">
        <div class="menu_item <?php echo (strpos($site_url,'chat.php')!== false)?'active_menu_item':'';?>">
            <div><a href="/chat.php"><img src="/svg/<?php echo (strpos($site_url,'chat.php')!== false)?'message_active.svg':'message.svg';?>" alt="Add new post" class="menu_item_icon icon"></a></div>
        </div>
        <div class="menu_item <?php echo ($site_url==='/')?'active_menu_item':'';?>">
            <div><a href="/"><img src="/svg/<?php echo ($site_url==='/')!== false?'feed_active.svg':'feed.svg';?>" alt="Add new post" class="menu_item_icon icon"></a></div>
        </div>
        <div class="menu_item <?php echo (strpos($site_url,'user.php')!== false)?'active_menu_item':'';?>">
            <div><a href="/user.php?user=<?php echo $_SESSION['user_id']?>"><img src="/svg/<?php echo (strpos($site_url,'user.php')!== false)?'user_active.svg':'user.svg';?>" alt="Add new post" class="menu_item_icon icon"></a></div>
        </div>
    </div>
    <div class="footer_settings">
        <div><img src="/svg/setting.svg" alt="Add new post" class="footer_icon icon"></div>
    </div>
</div>
