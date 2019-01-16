<?php
session_start();
error_reporting(0);
if (isset($_POST['sign_in'])) {
    if (isset($_POST['username']) && isset($_POST['username']) !== '' && isset($_POST['password']) && isset($_POST['password']) !== '') {
        $db = mysqli_connect('localhost', 'root', '', 'network');
        $username = htmlspecialchars($_POST['username']);
        $password = md5($_POST['password']);
        $sign_in_sql = "SELECT * FROM `users` WHERE name='$username' AND password='$password'";
        $result = mysqli_query($db, $sign_in_sql);
        if ($result->num_rows) {
            $result = mysqli_fetch_assoc($result);
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = intval($result['id']);
            header('Location:/');
            exit();
        } else {
            setcookie('user_registered_complete', 'false', time() + 10);
            header('Location:/sign.php');
            exit();
        }
    }
}

if (isset($_POST['registration'])) {
    if (isset($_POST['username']) && strlen($_POST['username']) >= 2 && isset($_POST['password']) && strlen($_POST['password']) >= 5 && $_POST['password'] === $_POST['approve_password']) {
        $db = mysqli_connect('localhost', 'root', '', 'network');
        $username = htmlspecialchars($_POST['username']);
        $password = md5($_POST['password']);
        $add_user_sql = sprintf("INSERT INTO `users` (`id`, `name`, `password`,`last_action`) VALUES (NULL, '%s', '" . $password . "'," . time() . ")", addslashes($username));
        $result = mysqli_query($db, $add_user_sql);
        if ($result) {
            setcookie('user_registered_complete', true, time() + 10);
        } else {
            setcookie('user_registered_complete', 'false', time() + 10);
        }
        header('Location:/sign.php');
        exit();
    } else {
        setcookie('user_registered_complete', 'false', time() + 10);
        header('Location:/sign.php');
        exit();
    }
}

if (isset($_POST['add_new_post'])) {
    if (empty($_POST['title'])) {
        header('Location:/');
        exit();
    } else {
        $db = mysqli_connect('localhost', 'root', '', 'network');
        $title = addslashes(htmlspecialchars($_POST['title']));
        $content = addslashes(htmlspecialchars(stripslashes($_POST['content'])));
        $tmp_image = $_FILES['image']['tmp_name'];
        $image = (isset($_FILES['image'])) ? time() . $_FILES['image']['name'] : NULL;
        $user_id = $_SESSION['user_id'];
        if (isset($_FILES['image']['tmp_name']) && strlen($_FILES['image']['tmp_name']) > 0) {
            var_dump($_FILES['image']['tmp_name']);
            move_uploaded_file($tmp_image, 'media/' . $image);
            correctImageOrientation('media/' . $image);
        }
        $add_post_sql = sprintf("INSERT INTO `posts` (`id`, `title`, `content`, `image`, `user_id`) VALUES (NULL, '%s', '%s', '$image', '$user_id');", $title, $content);
        $result = mysqli_query($db, $add_post_sql);
        header('Location:/');
        exit();
    }
}

if ((((isset($_POST['message']) && strlen($_POST['message']) > 0) || count($_FILES) > 0 || isset($_POST['emoji'])) && isset($_POST['send_message']))) {
    $db = new mysqli('localhost', 'root', '', 'network');
    $message = rtrim((htmlspecialchars($_POST['message'])));
    $send_id = $_SESSION['user_id'];
    $get_id = $_POST['get_id'];
    $message_media = '';
    if (count($_FILES) > 0) {
        $counter = 0;
        foreach ($_FILES as $key => $file) {
            $counter++;
            $file['name'] = preg_replace("/[^a-zA-Z0-9,.]+/", "", $file['name']);
            $file_name = basename(time() . $file['name']);
            $file_name = str_replace('"', '', $file_name);
            $file_name = str_replace('\'', '', $file_name);
            $file_name = str_replace(' ', '_', $file_name);
            $tmp_image = $file['tmp_name'];
            move_uploaded_file($tmp_image, 'media/' . $file_name);
            correctImageOrientation('media/' . $file_name);
            $message_media .= $file_name;
            if (startsWith($file['type'], 'image')) {
                $message_media .= ";;image";
            } else if (startsWith($file['type'], 'video')) {
                $message_media .= ";;video";
            } else if (startsWith($file['type'], 'audio')) {
                $message_media .= ";;audio";
            } else if (startsWith($file['type'], 'application') || startsWith($file['type'], 'text')) {
                $message_media .= ";;application";
            } else {
                $message_media .= ";;unrecognized";
            }
            if (count($_FILES) !== $counter) {
                $message_media .= "::";
            }
        }
    }
    if (isset($_POST['emoji'])) {
        $message_media = $_POST['emoji'] . ';;emoji';
    }
    $send_message_sql = sprintf("INSERT INTO `messages` (`id`, `message`, `send_id`, `get_id`,`media`) VALUES (NULL, '%s', $send_id, $get_id,'$message_media');", addslashes($message));
    $result = $db->query($send_message_sql);
    $last_ms_id = false;
    if (isset($_POST['last_message_id'])) {
        $last_ms_id = intval($_POST['last_message_id']);
    }
    $message_content = get_message_html(get_message_by_id($db->insert_id), false, $last_ms_id);
    $db->close();
//    die(var_dump($send_message_sql));

    $show_chat_new_message = show_single_user($get_id);
    $send = ($result) ? true : false;
    echo json_encode(array('sended' => $send, 'content' => $message_content, 'show_chat_new_message' => $show_chat_new_message));

}

if (isset($_POST['get_online_users']) && $_POST['get_online_users']) {
    return get_online_users();
}

if (isset($_POST['check_new_message']) && $_POST['check_new_message']) {
    $db = mysqli_connect('localhost', 'root', '', 'network');
    $last_id = intval($_POST['last-message-id']);
    $send_id = $_POST['send_id'];
    $get_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM `messages` WHERE id > $last_id AND send_id =$send_id AND get_id=$get_id";
    $results = mysqli_query($db, $sql);
    if ($results->num_rows > 0) {
        $message_content = get_message_html($results);
        echo json_encode(array('messages' => $message_content, 'new_message' => true));
    }
}

if (isset($_POST['update_user'])) {
    $db = mysqli_connect('localhost', 'root', '', 'network');
    $new_name = addslashes(htmlspecialchars($_REQUEST['name']));
    $user_id = $_SESSION['user_id'];
    $sql = sprintf("UPDATE `users` SET `name`='%s'", $new_name);

    if (isset($_REQUEST['new_password']) && isset($_REQUEST['approve_setting_password']) && strlen($_REQUEST['new_password']) != 0 && strlen($_REQUEST['approve_setting_password']) != 0) {
        if ($_REQUEST['new_password'] === $_REQUEST['approve_setting_password']) {
            $new_pass_hash = md5($_REQUEST['new_password']);
            $sql .= ", `password`='$new_pass_hash'";
        } else {
            setcookie('user_update_message', 'Your passwords do not match', time() + 10);
            setcookie('user_update_complete', 'false', time() + 10);

            header('Location:/user.php');
            exit();
        }
    }
    if (isset($_FILES['user_image']) && strlen($_FILES['user_image']['name']) != 0) {
        $new_image_name = time() . $_FILES['user_image']['name'];
        $new_image_tmp = $_FILES['user_image']['tmp_name'];
        $sql .= ", `image`='$new_image_name'";
        move_uploaded_file($new_image_tmp, 'media/' . $new_image_name);
        correctImageOrientation('media/' . $new_image_name);
    }
    $sql .= " WHERE id=$user_id";
    $result = mysqli_query($db, $sql);
    if ($result) {
        setcookie('user_update_complete', true, time() + 10);
    } else {
        setcookie('user_update_complete', 'false', time() + 10);
    }
    header('Location:/user.php');
    exit();
}

if (isset($_POST['user_subscribe'])) {
    $db = mysqli_connect('localhost', 'root', '', 'network');
    $subscribed_id = $_POST['user_id'];
    $user_id = $_SESSION['user_id'];
    $subscribe_sql = "INSERT INTO `friends` (`id`, `user_id`, `subscribed_id`) VALUES (NULL, " . $user_id . "," . $subscribed_id . ");";
    $result = mysqli_query($db, $subscribe_sql);
    if ($result) {
        echo json_encode(array('subscribe' => true));
    } else {
        echo json_encode(array('subscribe' => false));
    }
}

if (isset($_POST['update_action']) && $_POST['update_action']) {
    $db = mysqli_connect('localhost', 'root', '', 'network');
    $update_action_sql = "UPDATE `users` SET `last_action` = " . time() . " WHERE id=" . $_SESSION['user_id'];
    $result = mysqli_query($db, $update_action_sql);
}

if (isset($_POST['user_search'])) {
    $search_character = $_POST['search_character'];
    $db = mysqli_connect('localhost', 'root', '', 'network');
    $find_users_sql = "SELECT `id`,`name`,`image` FROM `users` WHERE `name` LIKE '%$search_character%' AND id!=" . $_SESSION['user_id'];
    $users = [];
    $results = mysqli_query($db, $find_users_sql);
    if ($results) {
        while ($result = mysqli_fetch_assoc($results)) {
            $users[] = $result;
        }
        echo json_encode($users);
    }
}

if (isset($_POST['get_chat_messenger']) && $_POST['get_chat_messenger']) {
    $content = show_chat_users($result);
    echo json_encode(['status' => true, 'content' => $content]);
}

if (isset($_POST['upload_new_message']) && $_POST['upload_new_message']) {
    get_messages_by_packet($_POST['limit'], $_POST['user_id'], $_POST['last_id']);
}

if (isset($_POST['check_chat_new_message']) && $_POST['check_chat_new_message']) {
    $db = new mysqli('localhost', 'root', '', 'network');
    $current_id = intval($_SESSION['user_id']);
    $get_unreaded_users = "SELECT DISTINCT `send_id` FROM `messages` WHERE `get_id`=$current_id AND `seen`=0";
    $get_unreaded_users_result = $db->query($get_unreaded_users);
    if ($get_unreaded_users_result->num_rows > 0) {
        $response_data = [];
        while ($user_id = mysqli_fetch_assoc($get_unreaded_users_result)) {
            $single_user = [];
            $user_id = intval($user_id['send_id']);
            $user_content = show_single_user($user_id);
            $single_user['user_id'] = $user_id;
            $single_user['user_content'] = $user_content;
            array_push($response_data, $single_user);
        }
        echo json_encode(['status' => true, 'users' => $response_data]);
    } else {
        echo json_encode(['status' => false]);
    }
}

function get_message_by_id($message_id)
{
    $db = new mysqli('localhost', 'root', '', 'network');
    $get_mes_sql = "SELECT * FROM `messages` WHERE id=" . $message_id;
    $result = $db->query($get_mes_sql);
    $db->close();
    if ($result) {
        return $result;
    }
}

function get_messages_by_packet($limit, $user_id, $last_id = false)
{
    $db = new mysqli('localhost', 'root', '', 'network');
    $current_id = $_SESSION['user_id'];
    $add_last_id_sql = '';
    if ($last_id) {
        $add_last_id_sql = "AND id < $last_id";
    }
    $get_message_packet = "SELECT * FROM `messages` WHERE (send_id=$user_id AND get_id=$current_id OR get_id=$user_id AND send_id=$current_id) $add_last_id_sql ORDER BY id DESC LIMIT $limit";
    $result = $db->query($get_message_packet);
    $db->close();
    echo get_message_html($result, true);
}

function get_message_html($messages = false, $reverse = false, $last_ms_id = false)
{
    if ($messages) {
        $messages_fetch = $messages;
    } else {
        $messages_fetch = get_current_chat_messages($_GET['user_id']);
    }

    $message_html = '';
    $messages = [];
    while ($message = mysqli_fetch_array($messages_fetch)) {
        $messages[] = $message;
    }

    if ($reverse) {
        $messages = array_reverse($messages);
    }

    $opened_tag = false;
    $message_counter = 0;
    foreach ($messages as $key => $message) {
        $message_media = explode('::', $message['media']);
        $message_image = [];
        $message_video = [];
        $message_audio = [];
        $message_application = [];
        $message_emoji = '';

        foreach ($message_media as $media) {
            $media_array = explode(';;', $media);
            switch ($media_array[1]) {
                case 'image':
                    array_push($message_image, $media_array[0]);
                    break;
                case 'video':
                    array_push($message_video, $media_array[0]);
                    break;
                case 'audio':
                    array_push($message_audio, $media_array[0]);
                    break;
                case 'application':
                    array_push($message_application, $media_array[0]);
                    break;
                case 'emoji':
                    $message_emoji = $media_array[0];
                    break;
            }
        }

        if (!$opened_tag) {
            if (intval($message['send_id']) === intval($_SESSION['user_id'])) {
                $message_html .= "<div class=\"send_message\">";
                $opened_send_tag = true;
                $opened_tag = true;
            } else {
                $user = get_user_by_id($message['send_id']);
                $message_html .= "<div class=\"get_message\"><div><div class='user_image_div message_user_image' style=\"background: url('" .'media/'. $user['image'] . "')\"></div></div><div class='get_message_content'>";
                $opened_get_tag = true;
                $opened_tag = true;
            }
        }
        $before = ($key === 0) ? time() : strtotime($messages[$key - 1]['time']);
        if ($last_ms_id) {
            $db = new mysqli('localhost', 'root', '', 'network');
            $last_ms_time = mysqli_fetch_assoc($db->query("SELECT `time` FROM `messages` WHERE `id`=$last_ms_id"))['time'];
            $before = strtotime($last_ms_time);
        }
        $time_difference = strtotime($message['time']) - $before;
        if (($key === 0)) {
            $time_difference = time() - strtotime($message['time']);
        }
        $message_date = '';
        if (10 * 60 < $time_difference || $message_counter === 10) {
            $time_difference = time() - strtotime($message['time']);
            if ($time_difference < 60 * 60 * 24) {
                $message_date = date('H:i', strtotime($message['time']));
            } elseif ($time_difference < 60 * 60 * 24 * 7) {
                $message_date = date('D H:i', strtotime($message['time']));
            } elseif ($time_difference < 60 * 60 * 24 * 31) {
                $message_date = date('M d', strtotime($message['time']));
            } else {
                $message_date = date('j.m.y', strtotime($message['time']));
            }
            if($opened_send_tag){
                $message_html .= "</div><div class='message_date col-12'><p>" . $message_date . "</p></div><div class=\"send_message\">";
            }elseif($opened_get_tag){
                $message_html .= "</div></div><div class='message_date col-12'><p>" . $message_date . "</p></div><div class=\"get_message\"><div><div class='user_image_div message_user_image' style=\"background: url('" .'media/'. $user['image'] . "')\"></div></div><div class='get_message_content'>";
            }

            $message_counter = 0;
        } else {
            $message_counter += 1;
        }
        $add_attribute = false;
        if (strlen($message['message']) > 0) {
            $message_html .= "<div class='message_text' data-message-id='" . $message['id'] . "'><p>" . nl2br($message['message']) . "</p></div>";
            $add_attribute = true;
        }

        if (!empty($message_image)) {
            $check_set_attribute = ($add_attribute) ? '' : "data-message-id='" . $message['id'] . "'";
            $add_attribute = true;
            $message_html .= "<div class=\"message_images_div media_item col-lg-9\" $check_set_attribute>";
            foreach ($message_image as $image) {
                $message_html .= "<div class=\"message_image_item col-4\" style=\"background-image: url('/media/" . $image . "')\"></div>";
            }
            $message_html .= "</div>";
        }

        if (!empty($message_video)) {
            $check_set_attribute = ($add_attribute) ? '' : "data-message-id='" . $message['id'] . "'";
            $add_attribute = true;
            $message_html .= "<div class=\"message_video_div media_item col-lg-7 col-12\" $check_set_attribute>";
            foreach ($message_video as $video) {
                $message_html .= "<video controls><source src='media/" . $video . "'>Your browser does not support the video.</video>";
            }
            $message_html .= "</div>";
        }

        if (!empty($message_audio)) {
            $check_set_attribute = ($add_attribute) ? '' : "data-message-id='" . $message['id'] . "'";
            $add_attribute = true;
            foreach ($message_audio as $audio) {
                $message_html .= "<div class=\"message_audio_div media_item col-lg-7 col-12\" $check_set_attribute>";
                $message_html .= "<audio controls><source src='media/" . $audio . "'>Your browser does not support the audio.</audio>";
                $message_html .= "</div>";
            }

        }

        if (!empty($message_application)) {
            $check_set_attribute = ($add_attribute) ? '' : "data-message-id='" . $message['id'] . "'";
            $add_attribute = true;
            $message_html .= "<div class=\"message_application_div media_item col-lg-7 col-12\" $check_set_attribute>";

            foreach ($message_application as $application) {
                $message_html .= "<a href='media/$application' download><span><i class=\"fa fa-cloud-download-alt\"></i>" . substr($application, 0, 20) . "...</span></a>";
            }
            $message_html .= "</div>";

        }

        if (!empty($message_emoji)) {
            $check_set_attribute = ($add_attribute) ? '' : "data-message-id='" . $message['id'] . "'";
            $add_attribute = true;
            $message_html .= "<div class=\"message_application_div media_item emoji_item col-7\" $check_set_attribute><img src='/svg/heart.svg' alt='Emoji' class='animated heartBeat'></div>";

        }

        if (intval($messages[$key]['send_id']) !== intval($messages[$key + 1]['send_id'])) {
            if (intval($message['send_id']) === intval($_SESSION['user_id'])) {
                $message_html .= "</div>";
                $opened_tag = false;
                $opened_send_tag = false;
            } else {
                $message_html .= "</div></div>";
                $opened_tag = false;
                $opened_get_tag = false;
            }
        }

    }
    return $message_html;
}

if (isset($_POST['see_all_ms']) && $_POST['see_all_ms']) {
    see_all_ms(intval($_POST['send_id']));
}

function get_online_users()
{
    $db = mysqli_connect('localhost', 'root', '', 'network');
    $time = time();
    $get_online_users_sql = "SELECT `name`,`id`,`image`,`last_action` FROM `users` WHERE $time-`last_action`<600 AND id!=" . $_SESSION['user_id'];
    $result = mysqli_query($db, $get_online_users_sql);
    if ($result) {
        $content = show_chat_users($result);
        echo json_encode(['status' => true, 'content' => $content]);
    } else {
        echo json_encode(['status' => false]);
    }
}

function get_posts()
{
    $db = mysqli_connect('localhost', 'root', '', 'network');
    $posts_sql = "SELECT * FROM `posts` ORDER BY `id` DESC";
    $result = mysqli_query($db, $posts_sql);
    return $result;
}

function get_post_user($user_id)
{
    $db = mysqli_connect('localhost', 'root', '', 'network');
    $posts_sql = "SELECT * FROM `users` WHERE id=$user_id";
    $result = mysqli_fetch_assoc(mysqli_query($db, $posts_sql))['name'];
    return $result;
}

function get_post_by_id($post_id)
{
    $db = mysqli_connect('localhost', 'root', '', 'network');
    $posts_sql = "SELECT * FROM `posts` WHERE id=$post_id";
    $result = mysqli_query($db, $posts_sql);
    return $result;
}

function get_users($current_user = false)
{
    $db = mysqli_connect('localhost', 'root', '', 'network');
    $current_user = $_SESSION['user_id'];
    $users_sql = "SELECT * FROM `users`";
    if ($current_user) {
        $users_sql .= " WHERE id!=$current_user";
    }
    $result = mysqli_query($db, $users_sql);
    return $result;
}

function get_current_chat_messages($get_id)
{
    $db = mysqli_connect('localhost', 'root', '', 'network');
    $send_id = $_SESSION['user_id'];
    $get_messages_sql = "SELECT * FROM `messages` WHERE send_id=$send_id AND get_id=$get_id OR get_id=$send_id AND send_id=$get_id";
    $result = mysqli_query($db, $get_messages_sql);
    return $result;

}

function get_user_by_id($user_id)
{
    $db = mysqli_connect('localhost', 'root', '', 'network');
    $posts_sql = "SELECT * FROM `users` WHERE id=$user_id";
    $result = mysqli_fetch_assoc(mysqli_query($db, $posts_sql));
    return $result;
}

function get_chat_last_message($user_id)
{
    $db = mysqli_connect('localhost', 'root', '', 'network');
    $current_id = $_SESSION['user_id'];
    $get_last_message_sql = "SELECT * FROM `messages` WHERE send_id=$user_id AND get_id=$current_id OR get_id=$user_id AND send_id=$current_id ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($db, $get_last_message_sql);
    if ($result) {
        return array('result' => true, 'message' => mysqli_fetch_assoc($result));
    } else {
        return array('result' => false);
    }
}

function show_chat_users($users = false)
{
    $users_chat_content = [];
    $users_chat_content_html = '';
    $online_search = true;
    if (!$users) {
        $users = get_chat_users();
        $online_search = false;
    }

    while ($user = mysqli_fetch_assoc($users)) {
        $users_chat_content[] = show_single_user($user['id'], $online_search);
    }
    usort($users_chat_content, 'array_sort_by_time');

    foreach ($users_chat_content as $single_content) {
        unset($single_content['date']);
        $users_chat_content_html .= $single_content['content'];
    }
    $_SESSION['last_message_user_id'] = $users_chat_content[0]['user_id'];
    return $users_chat_content_html;
}

function get_chat_users()
{
    $db = new mysqli('localhost', 'root', '', 'network');
    $get_chat_users_sql = "SELECT DISTINCT `users`.`id` , `name`,`image`,`last_action` FROM `users` INNER JOIN `messages` ON `users`.`id` = `messages`.`get_id` OR `users`.`id` = `messages`.`send_id`";
    $result = $db->query($get_chat_users_sql);
    return $result;
}

function get_last_message_user()
{
    $all_users = get_chat_users();
    while ($user = mysqli_fetch_assoc($all_users)) {
        $users_chat_content[] = show_single_user($user['id']);
    }
    usort($users_chat_content, 'array_sort_by_time');
    $_SESSION['last_message_user_id'] = $users_chat_content[0]['user_id'];
}

function show_single_user($user_id, $online_search = false)
{
    $db = mysqli_connect('localhost', 'root', '', 'network');
    $current_id = $_SESSION['user_id'];
    $get_chat_user_last_message = "SELECT * FROM `messages` WHERE send_id=$user_id AND get_id=$current_id OR get_id=$user_id AND send_id=$current_id ORDER BY id DESC LIMIT 1";
    $get_chat_user_last_message_data = mysqli_fetch_assoc(mysqli_query($db, $get_chat_user_last_message));
    if ($get_chat_user_last_message_data) {
        $message_user_id = (intval($get_chat_user_last_message_data['send_id']) === intval($current_id)) ? intval($get_chat_user_last_message_data['get_id']) : intval($get_chat_user_last_message_data['send_id']);
        $user = get_user_by_id($message_user_id);
        $message_time = $get_chat_user_last_message_data['time'];
        if (time() - strtotime($get_chat_user_last_message_data['time']) < 60 * 60 * 24) {
            $message_time_show = date("H:i", strtotime($get_chat_user_last_message_data['time']));
        } elseif (time() - strtotime($get_chat_user_last_message_data['time']) < 60 * 60 * 24 * 7) {
            $message_time_show = date("D", strtotime($get_chat_user_last_message_data['time']));
        } else {
            $message_time_show = date("j.m.y", strtotime($get_chat_user_last_message_data['time']));
        }
        $last_message_part = $get_chat_user_last_message_data['message'];
        $you_word = '';
        $your_message = false;
        if (intval($get_chat_user_last_message_data['send_id']) === intval($current_id)) {
            $your_message = true;
        }
        if ($your_message) {
            $you_word = '(You) ';

        }
        if (strlen($get_chat_user_last_message_data['message']) > 20) {
            $last_message_part = substr($last_message_part, 0, 20) . '...';
        }
        $icon = '';
        $message_media = $get_chat_user_last_message_data['media'];
        $message_media_array = [];
        $message_media = explode('::', $get_chat_user_last_message_data['media']);
        foreach ($message_media as $key => $media) {
            $message_media_array[] = explode(';;', $media);
        }
        $enable_unreaded_message = false;
        $unreader_messages_count_html = '';
        $unreader_messages_count = intval(get_unreaded_messages_count($message_user_id));
        if ($unreader_messages_count > 0) {
            $enable_unreaded_message = true;
        }
        switch ($message_media_array[count($message_media_array) - 1][1]) {
            case 'image':
                $icon = ($enable_unreaded_message) ? "<img src=\"/svg/image.svg\" alt=\"img\">" : "<img src=\"/svg/image_dark.svg\" alt=\"img\">";
                $last_message_part = "sent a photo";
                break;
            case 'video':
                $icon = ($enable_unreaded_message) ? "<img src=\"/svg/video.svg\" alt=\"img\">" : "<img src=\"/svg/video_dark.svg\" alt=\"img\">";
                $last_message_part = "sent a video";
                break;
            case 'audio':
                $icon = ($enable_unreaded_message) ? "<img src=\"/svg/audio.svg\" alt=\"img\">" : "<img src=\"/svg/audio_dark.svg\" alt=\"img\">";
                $last_message_part = "sent an audio file";
                break;
            case 'application':
                $icon = ($enable_unreaded_message) ? "<img src=\"/svg/file.svg\" alt=\"img\">" : "<img src=\"/svg/file_dark.svg\" alt=\"img\">";
                $last_message_part = "sent a file";
                break;
            case 'emoji':
                $last_message_part = "<img src=\"/svg/heart_button.svg\" alt=\"img\">";
                break;
        }
        $unreaded_text_class = '';
        if ($enable_unreaded_message) {
            $icon = "<span class='ms_unreaded_count badge'>$unreader_messages_count</span>";
            $unreaded_text_class = 'unreaded_ms';
        }

        $user_div_class = (isset($_GET['user_id']) && intval($_GET['user_id']) == intval($user_id)) ? 'active_chat_user' : '';
        $user_check_active = check_user_active($user_id, 600) ? 'online' : 'offline';
        return ["content" => "<div class=\"user_item_div row $user_div_class\" data-user-id=\"" . $message_user_id . "\">
            <div class=\"image_div col-2\">
                <div style=\"background: url('media/" . $user['image'] . "')\" class='user_image_div'><span class='$user_check_active'></span></div>
            </div>
            <div class=\"user_name_div col-10\">
                <div class=\"name_part\">
                    <a href=\"?user_id=" . $message_user_id . "\"><span class=\"user_name\">" . $user['name'] . "</span></a>
                    <span class='message_time'>" . $message_time_show . "</span>
                </div>
                <div class=\"message_part\">
                    <span class='$unreaded_text_class'>" . $you_word . $last_message_part . "</span>
                    " . $icon . "
                </div>
            </div>
        </div>", "date" => $message_time, "user_id" => $message_user_id];
    } else if ((intval($user_id) > 0 && !$get_chat_user_last_message_data && $online_search)) {
        $user = get_user_by_id($user_id);
        $user_div_class = (isset($_GET['user_id']) && $_GET['user_id'] == $user_id) ? 'active_chat_user' : '';
        $user_check_active = check_user_active($user_id, 600) ? 'online' : 'offline';
        return ["content" => "<div class=\"user_item_new_user user_item_div row $user_div_class\" data-user-id=\"" . $user_id . "\">
            <div class=\"image_div col-2\">
                <div style=\"background: url('media/" . $user['image'] . "')\" class='user_image_div'><span class='$user_check_active'></span></div>
            </div>
            <div class=\"user_name_div col-10\">
                <div class=\"name_part\">
                    <a href=\"?user_id=" . $user_id . "\"><span class=\"user_name\">" . $user['name'] . "</span></a>
                    <span class='message_time'></span>
                </div>
                <div class=\"message_part\">
                    <span></span>
                                    </div>
            </div>
        </div>", "date" => false, "user_id" => $user_id];
    }
}

function correctImageOrientation($filename)
{
    if (function_exists('exif_read_data')) {
        $exif = exif_read_data($filename);
        if ($exif && isset($exif['Orientation'])) {
            $orientation = $exif['Orientation'];
            if ($orientation != 1) {
                $img = imagecreatefromjpeg($filename);
                $deg = 0;
                switch ($orientation) {
                    case 3:
                        $deg = 180;
                        break;
                    case 6:
                        $deg = 270;
                        break;
                    case 8:
                        $deg = 90;
                        break;
                }
                if ($deg) {
                    $img = imagerotate($img, $deg, 0);
                }
                // then rewrite the rotated image back to the disk as $filename
                imagejpeg($img, $filename, 95);
            } // if there is some rotation necessary
        } // if have the exif orientation info
    } // if function exists
}

function array_sort_by_time($a, $b)
{
    return strtotime($b['date']) - strtotime($a['date']);
}

function check_user_active($user_id, $time_limit)
{
    $db = mysqli_connect('localhost', 'root', '', 'network');
    $get_last_action_sql = "SELECT `last_action` FROM `users` WHERE id=" . $user_id;
    $result = mysqli_query($db, $get_last_action_sql);
    if ($result->num_rows > 0) {
        $result = mysqli_fetch_assoc($result);
        if (is_null($result['last_action'])) {
            return false;
        } elseif (time() - $result['last_action'] < $time_limit) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function user_inactive_time($user_id)
{
    $db = mysqli_connect('localhost', 'root', '', 'network');
    $get_last_action_sql = "SELECT `last_action` FROM `users` WHERE id=" . $user_id;
    $result = mysqli_query($db, $get_last_action_sql);
    if ($result->num_rows > 0) {
        $result = mysqli_fetch_assoc($result);
        return time() - $result['last_action'];
    }
}

function startsWith($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}

function get_unreaded_messages_count($user_id)
{
    $current_user = intval($_SESSION['user_id']);
    $db = new mysqli('localhost', 'root', '', 'network');
    $get_last_readed_id = "SELECT  COUNT(*) AS `count` FROM `messages` WHERE send_id=$user_id AND get_id=$current_user AND seen=0";
    $get_last_seen_id = $db->query($get_last_readed_id);
    if ($get_last_seen_id->num_rows > 0) {
        $get_last_seen_id = mysqli_fetch_assoc($get_last_seen_id)['count'];
    } else {
        $get_last_seen_id = 0;
    }
    return $get_last_seen_id;
}

function see_all_ms($user_id)
{
    $current_user = intval($_SESSION['user_id']);
    $db = new mysqli('localhost', 'root', '', 'network');

    $seen_all_ms = "UPDATE `messages` SET `seen`=1 WHERE send_id=$user_id AND get_id=$current_user AND `seen`=0";

    $db->query($seen_all_ms);


}

?>