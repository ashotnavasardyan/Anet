<?php
if (!array_key_exists('user_id', $_GET) || (array_key_exists('user_id', $_GET) && intval($_GET['user_id']) == 0)) {
    include 'database.php';
    get_last_message_user();
    if (!is_null($_SESSION['last_message_user_id'])) {
        header('Location:/chat.php?user_id=' . $_SESSION['last_message_user_id']);
        exit();
    }
}
include 'header.php';
$text_box_style = (isset($_GET['user_id']) && intval($_GET['user_id']) > 0) ? '' : 'style=\'display:none\'';
$select_user_notice = (isset($_GET['user_id']) && intval($_GET['user_id']) > 0) ? 'style=\'display:none\'' : '';
$users = get_users();
see_all_ms($_GET['user_id']);
if (intval($_GET['user_id']) === intval($_SESSION['user_id'])) {
    header('Location:/chat.php');
    exit();
}
$user_blocked = check_for_blocked_user($_GET['user_id'])['blocked'];
$blocked_by_user = (check_for_blocked_user($_GET['user_id'])['blocked_by_user']);
?>
<body class="row">
<div class="show_image_popup display_none animated"><span class="show_image_popup_close"><i><i
                    class="far fa-window-close"></i></i></span>
    <div class="popup_image_container col-lg-9 col-md-12 col-12">
    </div>
</div>
<style id="style_for_images"></style>
<link rel="stylesheet" href="/css/animate.css">
<link rel="stylesheet" href="/css/select2.min.css">
<?php include 'left_menu.php'; ?>
<div class="users_list col-lg-3 col-md-4 col-10 d-none d-lg-flex d-md-flex">
    <div class="user_search_area">
        <input type="text" id="user_search" name="user_search" placeholder="Search" title="Min 3 characters">
    </div>
    <div class="chat_users" data-empty-text="There is no any user"><?php echo show_chat_users(); ?></div>

    <div class="chat_settings">
        <div><img src="/svg/messenger_active.svg" alt="Messenger" class="messenger_icon icon" id="chat_messenger"></div>
        <div><img src="/svg/online.svg" alt="Online" class="online_icon icon" id="chat_online"></div>
    </div>
</div>
<div class="message_area col-lg-8 col-md-7 col-10" id="message_area">
    <?php
    if (isset($_GET['user_id']) && intval($_GET['user_id']) > 0) {
    $current_user = get_user_by_id($_GET['user_id']);
    $user_name_active = (check_user_active(intval($_GET['user_id']), 600)) ? 'user_name_online' : '';
    $user_name_div_active = (check_user_active(intval($_GET['user_id']), 600)) ? 'user_name_online_div' : '';
    $user_inactive_time = user_inactive_time(intval($_GET['user_id']));
    if ($user_inactive_time <= 60 * 60) {
        $user_inactive_time = "Active " . floor($user_inactive_time / 60) . "m ago";
    } elseif ($user_inactive_time <= 60 * 60 * 24) {
        $user_inactive_time = "Active " . floor($user_inactive_time / 3600) . "h ago";;
    } elseif ($user_inactive_time <= 60 * 60 * 24 * 7) {
        $user_inactive_time = "Active " . floor($user_inactive_time / 86400) . "d ago";
    } else {
        $user_inactive_time = '';
    }

    ?>
    <div class="current_chat_info">
        <div class="back_icon_div"><img src="/svg/back.svg" alt="Back" class="back_icon d-lg-none d-md-none"></div>
        <div class="user_name_header <?php echo $user_name_div_active ?>">
            <div class="<?php echo $user_name_active ?>"></div>
            <p class="people_online_info"><?php echo $current_user['name'] ?></p><span
                    class="user_inactive_time"><?php echo $user_inactive_time ?></span></div>
        <div class="chat_settings_icon">
            <img src="/svg/dots.svg" alt="Settings" class="settings_dots">
        </div>
    </div>
    <div id="message_content" class="message_content">

        <?php
        get_messages_by_packet(20, $_GET['user_id']);
        }
        ?>
    </div>
    <?php if (!$user_blocked) {
        echo "<div class=\"text_box form-group row\" $text_box_style>
        <div id=\"message_images\"></div>
        <div class=\"col-2 col-md-2 col-lg-1 send_box_icon_div\">
            <img src=\"/svg/plus.svg\" alt=\"Plus\" class=\"send_box_icon\" id=\"add_file\">
            <input type=\"file\" id=\"file_source\"
                   accept=\".doc,.docx,.xls,.xlsx,.ppt,.txt,.pdf,.pptx,image/*,video/*,audio/*\" multiple>
        </div>
        <div class=\"col-8 col-md-8 col-lg-10 text_box_div\">
            <textarea class=\"message_textarea col-12\" placeholder=\"Write a message ...\"></textarea>
        </div>
        <!-- <div class=\"col-2 col-md-2 col-lg-1 send_box_icon_div\"></div>-->
        <div class=\"col-2 col-md-2 col-lg-1 send_box_icon_div\">
            <!-- <img src=\"/svg/smile.svg\" alt=\"Smile\" class=\"send_box_icon\" id=\"add_smiles\">-->
            <img src=\"/svg/heart_button.svg\" alt=\"Send\" class=\"send_box_icon heart\" id=\"send_message_heart\">
            <img src=\"/svg/send.svg\" alt=\"Send\" class=\"send_box_icon button display_none\" id=\"send_message_button\">
            <!--<p>Send</p>-->
        </div>
        <input type=\"hidden\" name=\"message_images_send\">
        <form action=\"database.php\" method=\"post\" enctype=\"multipart/form-data\" id=\"message_form\"></form>
    </div>";
    } else {
        echo "<div class=\"unavailable_user\">
                <span>Chat is unavailable</span>
              </div>";
    }

    ?>

</div>
<div class="settings_right_bar animated display_none col-lg-3 col-md-4 col-10">
    <img src="/svg/back.svg" class="rotated_back_icon close_settings_right_bar">
    <div class="settings_user_image_name">
        <div class="settings_user_image_content">
            <div class="settings_user_image user_image_div"
                 style="background: url('/media/<?php echo $current_user['image'] ?>')"></div>
        </div>
        <div class="settings_user_setting_name"><p><?php echo $current_user['name'] ?></p></div>
    </div>
    <div class="accordion">
        <div data-toggle="collapse" data-target="#settings_settings_lisr"
             aria-expanded="true" aria-controls="collapseThree" class="settings_submenu_header">
            <div class="settings_submenu_header_title">
                    <span>
                        Settings
                    </span>
            </div>
            <div class="settings_submenu_header_arrow rotated">
                <i class="fas fa-angle-left"></i>
            </div>
        </div>
        <div id="settings_settings_lisr" class="collapse show" aria-labelledby="headingThree"
             data-parent="#accordionExample">
            <div>
                <div class="settings_list_content">
                    <div class="settings_list_item">
                        <div class="settings_setting_name"><a
                                    href="/user.php?user=<?php echo $current_user['id'] ?>"><span>Show profile</span></a>
                        </div>
                    </div>
                    <div class="settings_list_item block_user" id="submit_block_user_form">
                        <div class="settings_setting_name">
                            <?php
                            $user_id_for_block = $current_user['id'];
                            if ($user_blocked && in_array(strval($_SESSION['user_id']), $blocked_by_user)) {
                                echo "<form action=\"database.php\" method=\"post\" id=\"block_user_form\">
                                <input type=\"hidden\" name=\"blocked_user_id\" value=\"$user_id_for_block\">
                                <input type=\"hidden\" name=\"unblock_user\" value=\"" . true . "\">
                            </form>
                            <span>Unblock user</span>";
                            } else {
                                echo "<form action=\"database.php\" method=\"post\" id=\"block_user_form\">
                                <input type=\"hidden\" name=\"blocked_user_id\" value=\"$user_id_for_block\">
                                <input type=\"hidden\" name=\"block_user\" value=\"" . true . "\">
                            </form>
                            <span>Block user</span>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div data-toggle="collapse" data-target="#settings_shared_files"
             aria-expanded="true" aria-controls="collapseThree" class="settings_submenu_header">
            <div class="settings_submenu_header_title">
                    <span>
                        Shared files
                    </span>
            </div>
            <div class="settings_submenu_header_arrow rotated">
                <i class="fas fa-angle-left"></i>
            </div>
        </div>
        <div id="settings_shared_files" class="collapse show" aria-labelledby="headingThree"
             data-parent="#accordionExample">
            <div>
                <div class="settings_shared_files">
                    <?php
                    $files_result = settings_get_files(5, $_GET['user_id']);
                    echo $files_result['content'];
                    ?>
                    <?php
                    if ($files_result['see_more']) {
                        echo "<div id=\"settings_load_new_files_div\"><span id=\"settings_load_new_files\">See more</span></div>";
                    }

                    ?>
                </div>
            </div>
        </div>
    </div>
    <div data-toggle="collapse" data-target="#settings_shared_photos"
         aria-expanded="true" aria-controls="collapseThree" class="settings_submenu_header">
        <div class="settings_submenu_header_title">
                    <span>
                        Shared photos
                    </span>
        </div>
        <div class="settings_submenu_header_arrow rotated">
            <i class="fas fa-angle-left"></i>
        </div>
    </div>
    <div id="settings_shared_photos" class="collapse show" aria-labelledby="headingThree"
         data-parent="#accordionExample">
        <div>
            <div class="settings_shared_photos row">

                <?php
                $photos_result = settings_get_photos(4, $_GET['user_id']);
                echo $photos_result['content'];
                ?>
            </div>
            <?php
            if ($photos_result['see_more']) {
                echo "<div id=\"settings_load_new_photos_div\"><span id=\"settings_load_new_photos\">See more</span></div>";
            }

            ?>
        </div>
    </div>
</div>
<script src="/js/jquery-3.3.1.min.js"></script>
<script src="/js/select2.min.js"></script>
<script src="/js/chat.js"></script>
<script src="/js/all.js"></script>
<script src="/js/sweetalert.min.js"></script>
</body>
</html>