<?php
include 'header.php';

$posts = get_posts();
?>

<body class="row scroll_disable">
<?php include 'left_menu.php'; ?>
<div class="feed_content col-lg-11 col-md-11 col-10 row">
    <!--    --><?php
    //    while ($post = mysqli_fetch_assoc($posts)) {
    //        $post_content = $post['content'];
    //        $post_id = $post['id'];
    //        $user = get_post_user($post['user_id']);
    //        if (strlen($post['content']) > 70) {
    //            $post_content = substr($post['content'], 0, 70) . '...';
    //        }
    //        echo "<div class=\"feed_item\">
    //                                <h3 class=\"title\"><a href='/post.php/?post_id=" . $post_id . "'>" . $post['title'] . "</a></h3>
    //                                <p class=\"content\">" . $post_content . "</p>
    //                                <b class=\"author\">" . $user . "</b>
    //                           </div>";
    //    }
    //    ?>

    <div class="feed_item_main_div col-lg-10 col-md-10 col-12 row">
        <div class="post_main_content_div col-12 row">
            <div class="post_main_content_user_div">
                <div class="post_user_image_div">
                    <div class="post_user_image"></div>
                </div>
                <div class="post_user_name"><p class="post_user">ashot_navasardyan</p></div>
            </div>
            <div class="post_like_div">
                <img src="/svg/heart_like.svg">
            </div>
        </div>
        <div class="post_media col-12 col-lg-5"></div>
        <div class="post_info_div col-12 col-lg-7">
            <div class="post_data_div">
                <p class="post_likes"><span class="post_like_count">Like</span>: 44</p>
                <p class="post_user_text"><span class="post_user">ashot_navasardyan</span>: This is my post</p>
                <p class="post_hashtags"><span>#test</span> <span>#any_test</span> <span>#school</span></p>
            </div>
            <div class="post_comments_div">
                <p class="post_comments_count">Comments(3)</p>
                <div class="post_comments">
                    <div class="post_comment">
                        <span class="post_comment_content"><span class="comment_user">GAGO_DRAGO </span><span
                                    class="comment_text">Amazing Amazing</span></span>
                        <span class="like_post_comment"><img src="/svg/heart_like.svg" alt="" class="like_heart"></span>
                    </div>
                    <div class="post_comment">
                        <span class="post_comment_content"><span class="comment_user">GAGO_DRAGO </span><span
                                    class="comment_text">Amazing Amazing</span></span>
                        <span class="like_post_comment"><img src="/svg/heart_like.svg" alt="" class="like_heart"></span>
                    </div>
                    <div class="post_comment">
                        <span class="post_comment_content"><span class="comment_user">GAGO_DRAGO </span><span
                                    class="comment_text">Amazing Amazing</span></span>
                        <span class="like_post_comment"><img src="/svg/heart_like.svg" alt="" class="like_heart"></span>
                    </div>
                </div>
            </div>
            <div class="post_add_comment_div row">
                <div class="post_user_image_div">
                    <div class="post_user_image add_comment_image"></div>
                </div>
                <div class="add_comment_area" contenteditable></div>
                <div class="add_comment_button"><span>Send</span></div>
            </div>
        </div>
    </div>
    <div class="feed_item_main_div col-lg-9 col-md-10 col-12 row">
        <div class="post_main_content_div col-12 row">
            <div class="post_main_content_user_div">
                <div class="post_user_image_div">
                    <div class="post_user_image"></div>
                </div>
                <div class="post_user_name"><p class="post_user">ashot_navasardyan</p></div>
            </div>
            <div class="post_like_div">
                <img src="/svg/heart_like.svg">
            </div>
        </div>
        <div class="post_media col-12 col-lg-5"></div>
        <div class="post_info_div col-12 col-lg-7">
            <div class="post_data_div">
                <p class="post_likes"><span class="post_like_count">Like</span>: 44</p>
                <p class="post_user_text"><span class="post_user">ashot_navasardyan</span>: This is my post</p>
                <p class="post_hashtags"><span>#test</span> <span>#any_test</span> <span>#school</span></p>
            </div>
            <div class="post_comments_div">
                <p class="post_comments_count">Comments(3)</p>
                <div class="post_comments">
                    <div class="post_comment">
                        <span class="post_comment_content"><span class="comment_user">GAGO_DRAGO </span><span
                                    class="comment_text">Amazing Amazing</span></span>
                        <span class="like_post_comment"><img src="/svg/heart_like.svg" alt="" class="like_heart"></span>
                    </div>
                    <div class="post_comment">
                        <span class="post_comment_content"><span class="comment_user">GAGO_DRAGO </span><span
                                    class="comment_text">Amazing Amazing</span></span>
                        <span class="like_post_comment"><img src="/svg/heart_like.svg" alt="" class="like_heart"></span>
                    </div>
                    <div class="post_comment">
                        <span class="post_comment_content"><span class="comment_user">GAGO_DRAGO </span><span
                                    class="comment_text">Amazing Amazing</span></span>
                        <span class="like_post_comment"><img src="/svg/heart_like.svg" alt="" class="like_heart"></span>
                    </div>
                </div>
            </div>
            <div class="post_add_comment_div row">
                <div class="post_user_image_div">
                    <div class="post_user_image add_comment_image"></div>
                </div>
                <div class="add_comment_area" contenteditable></div>
                <div class="add_comment_button"><span>Send</span></div>
            </div>
        </div>
    </div>
    <div class="feed_item_main_div col-lg-9 col-md-10 col-12 row">
        <div class="post_main_content_div col-12 row">
            <div class="post_main_content_user_div">
                <div class="post_user_image_div">
                    <div class="post_user_image"></div>
                </div>
                <div class="post_user_name"><p class="post_user">ashot_navasardyan</p></div>
            </div>
            <div class="post_like_div">
                <img src="/svg/heart_like.svg">
            </div>
        </div>
        <div class="post_media col-12 col-lg-5"></div>
        <div class="post_info_div col-12 col-lg-7">
            <div class="post_data_div">
                <p class="post_likes"><span class="post_like_count">Like</span>: 44</p>
                <p class="post_user_text"><span class="post_user">ashot_navasardyan</span>: This is my post</p>
                <p class="post_hashtags"><span>#test</span> <span>#any_test</span> <span>#school</span></p>
            </div>
            <div class="post_comments_div">
                <p class="post_comments_count">Comments(3)</p>
                <div class="post_comments">
                    <div class="post_comment">
                        <span class="post_comment_content"><span class="comment_user">GAGO_DRAGO </span><span
                                    class="comment_text">Amazing Amazing</span></span>
                        <span class="like_post_comment"><img src="/svg/heart_like.svg" alt="" class="like_heart"></span>
                    </div>
                    <div class="post_comment">
                        <span class="post_comment_content"><span class="comment_user">GAGO_DRAGO </span><span
                                    class="comment_text">Amazing Amazing</span></span>
                        <span class="like_post_comment"><img src="/svg/heart_like.svg" alt="" class="like_heart"></span>
                    </div>
                    <div class="post_comment">
                        <span class="post_comment_content"><span class="comment_user">GAGO_DRAGO </span><span
                                    class="comment_text">Amazing Amazing</span></span>
                        <span class="like_post_comment"><img src="/svg/heart_like.svg" alt="" class="like_heart"></span>
                    </div>
                </div>
            </div>
            <div class="post_add_comment_div row">
                <div class="post_user_image_div">
                    <div class="post_user_image add_comment_image"></div>
                </div>
                <div class="add_comment_area" contenteditable></div>
                <div class="add_comment_button"><span>Send</span></div>
            </div>
        </div>
    </div>
    <div class="feed_item_main_div col-lg-9 col-md-10 col-12 row">
        <div class="post_main_content_div col-12 row">
            <div class="post_main_content_user_div">
                <div class="post_user_image_div">
                    <div class="post_user_image"></div>
                </div>
                <div class="post_user_name"><p class="post_user">ashot_navasardyan</p></div>
            </div>
            <div class="post_like_div">
                <img src="/svg/heart_like.svg">
            </div>
        </div>
        <div class="post_media col-12 col-lg-5"></div>
        <div class="post_info_div col-12 col-lg-7">
            <div class="post_data_div">
                <p class="post_likes"><span class="post_like_count">Like</span>: 44</p>
                <p class="post_user_text"><span class="post_user">ashot_navasardyan</span>: This is my post</p>
                <p class="post_hashtags"><span>#test</span> <span>#any_test</span> <span>#school</span></p>
            </div>
            <div class="post_comments_div">
                <p class="post_comments_count">Comments(3)</p>
                <div class="post_comments">
                    <div class="post_comment">
                        <span class="post_comment_content"><span class="comment_user">GAGO_DRAGO </span><span
                                    class="comment_text">Amazing Amazing</span></span>
                        <span class="like_post_comment"><img src="/svg/heart_like.svg" alt="" class="like_heart"></span>
                    </div>
                    <div class="post_comment">
                        <span class="post_comment_content"><span class="comment_user">GAGO_DRAGO </span><span
                                    class="comment_text">Amazing Amazing</span></span>
                        <span class="like_post_comment"><img src="/svg/heart_like.svg" alt="" class="like_heart"></span>
                    </div>
                    <div class="post_comment">
                        <span class="post_comment_content"><span class="comment_user">GAGO_DRAGO </span><span
                                    class="comment_text">Amazing Amazing</span></span>
                        <span class="like_post_comment"><img src="/svg/heart_like.svg" alt="" class="like_heart"></span>
                    </div>
                </div>
            </div>
            <div class="post_add_comment_div row">
                <div class="post_user_image_div">
                    <div class="post_user_image add_comment_image"></div>
                </div>
                <div class="add_comment_area" contenteditable></div>
                <div class="add_comment_button"><span>Send</span></div>
            </div>
        </div>
    </div>
</div>
<script src="/js/all.js"></script>
</body>
</html>