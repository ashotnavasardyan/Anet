jQuery(document).ready(function () {

    var fileArray = new FormData();
    var body = $('body');
    refresh_message_style();
    remove_empty_divs();

    $('.message_image_item').css('height', $('.message_image_item').eq(0).css('width'));

    $('div#settings_load_new_files_div').on('click', function () {
        var last_file_ms_id = parseInt($('.settings_shared_files').find('.settings_shared_files_item_div').last().attr('data-message-id'));
        const urlParams = new URLSearchParams(window.location.search);
        const get_id = parseInt(urlParams.get('user_id'));
        var data = {last_ms_id: last_file_ms_id, load_new_files: true, user_id: get_id};
        $.ajax({
            url: 'database.php',
            data: data,
            type: 'POST',
            success: function (response) {
                response = JSON.parse(response);
                if(response.content){
                    $('.settings_shared_files').find('.settings_shared_files_item_div').last().after(response.content);
                }

                if(!response.see_more){
                    $('#settings_load_new_files_div').remove();
                }
            }
        });
    });

    $('div#settings_load_new_photos_div').on('click', function () {
        var last_photo_ms_id = parseInt($('.settings_shared_photos').find('.settings_shared_photos_item').last().attr('data-message-id'));
        const urlParams = new URLSearchParams(window.location.search);
        const get_id = parseInt(urlParams.get('user_id'));
        var data = {last_ms_id: last_photo_ms_id, load_new_photos: true, user_id: get_id};
        $.ajax({
            url: 'database.php',
            data: data,
            type: 'POST',
            success: function (response) {
                response = JSON.parse(response);
                if(response.content){
                    $('.settings_shared_photos').find('.settings_shared_photos_item').last().after(response.content);
                }

                if(!response.see_more){
                    $('#settings_load_new_photos_div').remove();
                }
            }
        });
    });

    $(window).on('resize', function () {
        $('.message_image_item').css('height', $('.message_image_item').eq(0).css('width'));
        $('.settings_shared_photos_item').css('height', $('.settings_shared_photos_item').eq(0).css('width'));
    });

    $('.settings_shared_photos_item').on('load', function () {
        $('.settings_shared_photos_item').css('height', $(this).css('width'));
    });

    body.on('click', '.remove_image_button', function () {
        let removed_item = $(this).parents('.message_single_image_main_div');
        let item_index = removed_item.attr('data-file-index');
        fileArray.delete(item_index);
        $(this).parents('.message_single_image_main_div').remove();
        var files_count = 0;
        for (var pair of fileArray.entries()) {
            files_count++;
        }
        if (files_count === 0) {
            change_send_button();
        }
    });

    $('#chat_online').on('click', function () {
        $.ajax({
            url: 'database.php',
            data: {'get_online_users': true},
            type: 'POST',
            success: function (response) {
                var response = JSON.parse(response);
                const urlParams = new URLSearchParams(window.location.search);
                const get_id = urlParams.get('user_id');
                $('.chat_users').empty();
                $('#chat_online').attr('src', '/svg/online_active.svg');
                $('#chat_messenger').attr('src', '/svg/messenger.svg');
                if (response.status && response.content.length > 0) {
                    $('.chat_users').append(response.content);
                    $('.chat_users').find('[data-user-id="' + get_id + '"]').addClass('active_chat_user');
                }
            }
        })
    });

    $('#chat_messenger').on('click', function () {
        $.ajax({
            url: 'database.php',
            data: {'get_chat_messenger': true},
            type: 'POST',
            success: function (response) {
                var response = JSON.parse(response);
                const urlParams = new URLSearchParams(window.location.search);
                const get_id = urlParams.get('user_id');
                $('.chat_users').empty();
                $('#chat_online').attr('src', '/svg/online.svg');
                $('#chat_messenger').attr('src', '/svg/messenger_active.svg');
                if (response.status && response.content.length > 0) {
                    $('.chat_users').append(response.content);
                    $('.chat_users').find('[data-user-id="' + get_id + '"]').addClass('active_chat_user');
                }
            }
        })
    });

    $('#add_file').click(function () {
        $('#file_source').trigger('click');
    });

    $('.message_textarea').on('focus', function () {
        $(document).on('keydown', function (e) {
            if (e.keyCode === 13) {
                if (!e.shiftKey) {
                    $('#send_message_button').trigger('click');
                    return false;
                }
            }
        });
    });

    $('.message_textarea').on('blur', function () {
        $(document).off('keydown');
    });

    $('#file_source').on('change', function (e) {
        var cloned_source = $(this).clone();
        $(this).val();
        $('#message_form').append(cloned_source);
        var first_index = $('#message_form').find('input').index(cloned_source);
        for (var i = 0; i <= cloned_source[0].files.length - 1; i++) {
            if (cloned_source[0].files[i]) {
                var reader = new FileReader();
                var file = cloned_source[0].files[i];
                reader.onloadend = (function (file, i, reader, fileArray) {
                    return function () {

                        let file_div = $("<div class=\"message_single_image_div\">");
                        file_div.append("<button class=\"remove_image_button\" title=\"Remove Image\"></button>");
                        var aa = new Image();
                        if (file.type.startsWith('image')) {
                            //Random id for div
                            let rand_id = makeid();
                            //Add css to generated id
                            let style = $('#style_for_images').text();//Alert! Mahvan Goti
                            style += '#' + rand_id + '{background-image:url(' + this.result + ')}';
                            $('#style_for_images').text(style);
                            file_div.attr('id', rand_id);
                        } else if (file.type.startsWith('video')) {
                            file_div.attr('id', 'video_file_icon').addClass('file_icon_div');
                            file_div.append('<p class="message_up_file_name">' + file.name.substring(0, 15) + '...</p>')
                        } else if (file.type.startsWith('audio')) {
                            file_div.attr('id', 'audio_file_icon').addClass('file_icon_div');
                            file_div.append('<p class="message_up_file_name">' + file.name.substring(0, 15) + '...</p>')
                        } else if (file.type.startsWith('application') || file.type.startsWith('text')) {
                            file_div.attr('id', 'application_file_icon').addClass('file_icon_div');
                            file_div.append('<p class="message_up_file_name">' + file.name.substring(0, 15) + '...</p>')
                        }

                        let new_file = $('<div class="message_single_image_main_div" data-file-index="file_' + first_index + '_' + i + '">').append(file_div);
                        fileArray.append('file_' + first_index + '_' + i, file);
                        $('#message_images').append(new_file);
                        $(new_file).addClass('animated slideInUp')

                    }
                })(file, i, reader, fileArray);
                reader.readAsDataURL(cloned_source[0].files[i]);
            }
        }
        $('#message_images').css('display', 'flex');
        change_send_button(2);
    });

    $('#send_message_heart,#send_message_button').on('click', function () {
        var files_count = 0;
        var files_size = 0;
        let message = $(this).parents('.text_box').find('.message_textarea').val();
        const urlParams = new URLSearchParams(window.location.search);
        const get_id = urlParams.get('user_id');
        for (var pair of fileArray.entries()) {
            files_count++;
            files_size += pair[1].size;
        }

        if (files_count > 0 && files_size > 104857600) {
            swal("Your media content is too big!", "Max: 105MB", "warning");
            return false;
        }

        if (files_count > 0 || message.length || $(this).hasClass('heart')) {
            fileArray.append('message', message);
            fileArray.append('send_message', true);
            fileArray.append('get_id', get_id);
            fileArray.append('last_message_id', $('[data-message-id]').last().attr('data-message-id'));
            if ($(this).hasClass('heart')) {
                fileArray.append('emoji', $(this).attr('src'));
            }
            let xhr = new XMLHttpRequest();
            $(document).find('.message_textarea').val('');
            $(document).find('#message_images').empty();
            $(document).find('#style_for_images').empty();
            xhr.open('POST', 'database.php', true);
            xhr.onload = function (response) {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        response = JSON.parse(xhr.response);
                        if (response.sended) {
                            if ($('#message_content').children().last().hasClass('send_message')) {
                                $('#message_content').children().last().append($(response.content).children());
                            } else {
                                $('#message_content').append(response.content);
                            }
                            $('.message_image_item').css('height', $('.message_image_item').eq(0).css('width'));
                            $(".message_content").animate({scrollTop: $('.message_content').prop("scrollHeight")}, 1000);
                            var show_chat_new_message = response.show_chat_new_message;
                            if ($('div[data-user-id="' + show_chat_new_message.user_id + '"]').hasClass('active_chat_user')) {
                                show_chat_new_message.content = $(show_chat_new_message.content).addClass('active_chat_user');
                            }
                            $('div[data-user-id="' + show_chat_new_message.user_id + '"]').remove();
                            $('.chat_users').prepend(show_chat_new_message.content);
                            change_send_button();
                            refresh_message_style();
                            remove_empty_divs();
                            fileArray = new FormData();
                        }
                    } else {
                        console.error(xhr.statusText);
                    }
                }
            };
            xhr.send(fileArray);
        }
    });

    $('#user_search').on('input', function () {
        if ($(this).val().length >= 3) {
            let data = {'user_search': true, 'search_character': $(this).val()};
            $.ajax({
                url: 'database.php',
                data: data,
                type: 'POST',
                success: function (response) {
                    if (response) {
                        var response = JSON.parse(response);
                        var users_list = $('.chat_users');
                        users_list.find('.no_search_result').remove();
                        if (response.length === 0) {
                            users_list.find('.user_search_item_div').remove();
                            users_list.find('.user_item_div').css('display', 'none');
                            users_list.append('<p class="no_search_result">We couldn\'t find anything</p>');
                        } else {
                            users_list.find('.user_item_div').css('display', 'none');
                            users_list.find('.user_search_item_div').remove();
                            for (let i = 0; i <= response.length - 1; i++) {
                                let search_item = $('<div class="user_search_item_div row">');
                                let user_image_div = $("<div class='user_image_div'>").css('background', 'url("/media/' + response[i].image + '")');
                                let search_image = $('<div class="image_div col-2">').append(user_image_div);
                                let search_user_name = $('<div class="user_name_div col-10">');
                                let search_link = $('<a href="?user_id=' + response[i].id + '">').append("<span class=\"user_name\">" + response[i].name + "</span>");
                                search_user_name.append(search_link);
                                search_item.append(search_image);
                                search_item.append(search_user_name);
                                users_list.append(search_item);
                            }
                        }

                    }
                }
            });
        } else {
            var users_list = $('.chat_users');
            users_list.find('.user_item_div').css('display', '');
            users_list.find('.user_search_item_div').remove();
            users_list.find('.no_search_result').remove();
        }
    });

    setInterval(function () {
        const urlParams = new URLSearchParams(window.location.search);
        const get_id = urlParams.get('user_id');
        let last_message_id = $('#message_content').find('[data-message-id]').last().attr('data-message-id');
        var data = {'check_new_message': true, 'last-message-id': last_message_id, 'send_id': get_id};
        $.ajax({
            url: 'database.php',
            data: data,
            type: 'POST',
            success: function (response) {
                if (response) {
                    var response = JSON.parse(response);
                    var new_messages = $(response.messages);
                    if ($('#message_content').children().last().hasClass('get_message')) {
                        new_messages = new_messages.find('.get_message_content').children();
                        $('#message_content').children().last().find('.get_message_content').append(new_messages);
                    } else {
                        $('#message_content').append(new_messages);
                    }
                    refresh_message_style();
                    remove_empty_divs();
                    check_new_ms();
                    var data = {'see_all_ms': true, 'send_id': get_id};
                    $.ajax({
                        url: 'database.php',
                        data: data,
                        type: 'POST'
                    });
                    new_messages.find('.message_image_item').each(function () {
                        $(this).css('height', $(this).css('width'));
                    });
                    $(".message_content").animate({scrollTop: $('.message_content').prop("scrollHeight")}, 1000);
                }
            }
        })
    }, 1000);

    setInterval(check_new_ms, 1000);

    $('#message_content').scroll(function (e) {
        var pos = $('#message_content').scrollTop();
        if (pos === 0) {
            const urlParams = new URLSearchParams(window.location.search);
            const get_id = urlParams.get('user_id');
            var last_id = $('#message_content').find('[data-message-id]').first().attr('data-message-id');
            var data = {'user_id': get_id, 'last_id': last_id, 'limit': 20, 'upload_new_message': true};
            $.ajax({
                url: 'database.php',
                type: 'POST',
                data: data,
                success: function (response) {
                    if (response) {
                        if ($('#message_content').children().first().hasClass('get_message') && $(response).last().hasClass('get_message')) {
                            $('#message_content').children().first().find('.get_message_content').prepend($(response).find('.get_message_content').children());
                        }
                        else if ($('#message_content').children().first().hasClass('send_message') && $(response).last().hasClass('send_message')) {
                            $('#message_content').children().first().prepend($(response).last().children());
                        }
                        else {
                            $('#message_content').prepend(response);
                        }
                        refresh_message_style();
                        remove_empty_divs();
                        $('.message_image_item').css('height', $('.message_image_item').eq(0).css('width'));
                    }
                }
            });
        }
    });

    $('.chat_settings_icon').on('click', function () {
        $('.settings_right_bar').removeClass('fadeOutRight');
        $('.settings_right_bar').addClass('fadeInRight');
    });

    $('.settings_submenu_header').on('click', function () {
        if ($(this).find('.settings_submenu_header_arrow').hasClass('rotated')) {
            $(this).find('.settings_submenu_header_arrow').removeClass('rotated');
        } else {
            $(this).find('.settings_submenu_header_arrow').addClass('rotated');
        }
    });

    $('.chat_settings_icon').on('click', function () {
        let right_bar = $('.settings_right_bar');
        right_bar.removeClass('display_none');
        right_bar.addClass('fadeInRight');
    });

    $('.close_settings_right_bar').on('click', function () {
        $('.settings_right_bar').removeClass('fadeInRight');
        $('.settings_right_bar').addClass('fadeOutRight');
    });

    body.on('click','.message_image_item,.settings_shared_photos_item.photo_item ',function () {
        $('.show_image_popup').removeClass('display_none');
        $('.show_image_popup').removeClass('fadeOutUp');
        $('.show_image_popup').addClass('fadeInDown');
        $('.popup_image_container').empty();
        $('.popup_image_container').css('background-image', $(this).css('background-image'));
    });

    body.on('click','.settings_shared_photos_item.video_item ',function () {
        $('.show_image_popup').removeClass('display_none');
        $('.show_image_popup').removeClass('fadeOutUp');
        $('.show_image_popup').addClass('fadeInDown');
        $('.popup_image_container').css('background-image', 'none');
        $('.popup_image_container').html('<video controls><source src="'+$(this).find('source').attr('src')+'"></video>');
    });

    body.on('click','.show_image_popup_close',function () {
        $('.show_image_popup').addClass('fadeOutUp');
    });


    body.on('click', '.user_search_item_div,.user_item_div',function (e) {
        let href = $(this).find('.name_part a').attr('href');
        window.location = href;
    });

    $('#submit_block_user_form').on('click',function () {
        $('#block_user_form').submit();
    });

    $('.back_icon_div .back_icon').on('click',function () {
        $('#message_area').addClass('d-none');
        $('.users_list').removeClass('d-none');
    });

    $('.message_textarea').on('input', change_send_button);


    function check_new_ms() {
        let data = {'check_chat_new_message': true};
        const urlParams = new URLSearchParams(window.location.search);
        const get_id = urlParams.get('user_id');
        $.ajax({
            url: 'database.php',
            data: data,
            type: 'POST',
            success: function (response) {
                var response = JSON.parse(response);
                if (response.status) {
                    var users = response.users;
                    for (var i = 0; i < users.length; i++) {
                        var user_id = users[i]['user_id'];
                        var user_content = users[i]['user_content']['content'];
                        if (parseInt($('div[data-user-id="' + user_id + '"]').find('.ms_unreaded_count').text()) !== parseInt($(user_content).find('.ms_unreaded_count').text()) || parseInt(user_id) == parseInt(get_id)) {
                            if ($('div[data-user-id="' + user_id + '"]').hasClass('active_chat_user')) {
                                user_content = $(user_content).addClass('active_chat_user');
                            }
                            $('div[data-user-id="' + user_id + '"]').remove();
                            $('.chat_users').prepend($(user_content));
                        }
                    }
                }
            }
        });
    }

    function refresh_message_style() {
        $('.send_message , .get_message').each(function () {
            var send_items = $(this).find('div');
            var text_groups = [];
            for (var i = 0; i < send_items.length; i++) {
                if (send_items.eq(i).hasClass('message_text')) {
                    var group = send_items.eq(i);
                    group = group.add(send_items.eq(i).nextUntil('.media_item'));
                    var group_last_index = send_items.index(group.last());
                    if (group_last_index < 0) break;
                    group = group.add(send_items.eq(i));
                    i = parseInt(group_last_index);
                    text_groups.push(group);
                }
            }
            for (var i = 0; i < text_groups.length; i++) {
                text_groups[i].find('p').removeAttr('class');
                if (text_groups[i].length === 1) {
                    text_groups[i].eq(0).find('p').addClass('single_message');
                } else {
                    if (text_groups[i].find('p').parents().hasClass('get_message')) {
                        text_groups[i].find('p').addClass('inner_get_message');
                        text_groups[i].first().find('p').addClass('first_get_message');
                        text_groups[i].last().find('p').addClass('last_get_message');
                    } else if (text_groups[i].find('p').parents().hasClass('send_message')) {
                        text_groups[i].find('p').addClass('inner_send_message');
                        text_groups[i].first().find('p').addClass('first_send_message');
                        text_groups[i].last().find('p').addClass('last_send_message');
                    }
                }
            }
        });
    }

    function remove_empty_divs() {
        $('.send_message:empty').remove();
        $('.get_message_content:empty').parents('.get_message').remove();
    }

    function change_send_button(file_upload = false) {
        var input_text = $('.message_textarea').val();
        if (input_text.length > 0 || file_upload === 2) {
            $('.send_box_icon.heart').addClass('display_none');
            $('.send_box_icon.button').removeClass('display_none');
        } else {
            $('.send_box_icon.heart').removeClass('display_none');
            $('.send_box_icon.button').addClass('display_none');
        }
    }

    function makeid() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

        for (var i = 0; i < 5; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    function getOrientation(file, callback) {
        var reader = new FileReader();

        reader.onload = function (event) {
            var view = new DataView(event.target.result);

            if (view.getUint16(0, false) != 0xFFD8) return callback(-2);

            var length = view.byteLength,
                offset = 2;

            while (offset < length) {
                var marker = view.getUint16(offset, false);
                offset += 2;

                if (marker == 0xFFE1) {
                    if (view.getUint32(offset += 2, false) != 0x45786966) {
                        return callback(-1);
                    }
                    var little = view.getUint16(offset += 6, false) == 0x4949;
                    offset += view.getUint32(offset + 4, little);
                    var tags = view.getUint16(offset, little);
                    offset += 2;

                    for (var i = 0; i < tags; i++)
                        if (view.getUint16(offset + (i * 12), little) == 0x0112)
                            return callback(view.getUint16(offset + (i * 12) + 8, little));
                }
                else if ((marker & 0xFF00) != 0xFF00) break;
                else offset += view.getUint16(offset, false);
            }
            return callback(-1);
        };

        reader.readAsArrayBuffer(file.slice(0, 64 * 1024));
    }

    function resetOrientation(srcBase64, srcOrientation, callback) {
        var img = new Image();

        img.onload = function () {
            var width = img.width,
                height = img.height,
                canvas = document.createElement('canvas'),
                ctx = canvas.getContext("2d");

            // set proper canvas dimensions before transform & export
            if (4 < srcOrientation && srcOrientation < 9) {
                canvas.width = height;
                canvas.height = width;
            } else {
                canvas.width = width;
                canvas.height = height;
            }

            // transform context before drawing image
            switch (srcOrientation) {
                case 2:
                    ctx.transform(-1, 0, 0, 1, width, 0);
                    break;
                case 3:
                    ctx.transform(-1, 0, 0, -1, width, height);
                    break;
                case 4:
                    ctx.transform(1, 0, 0, -1, 0, height);
                    break;
                case 5:
                    ctx.transform(0, 1, 1, 0, 0, 0);
                    break;
                case 6:
                    ctx.transform(0, 1, -1, 0, height, 0);
                    break;
                case 7:
                    ctx.transform(0, -1, -1, 0, height, width);
                    break;
                case 8:
                    ctx.transform(0, -1, 1, 0, 0, width);
                    break;
                default:
                    break;
            }

            // draw image
            ctx.drawImage(img, 0, 0);

            // export base64
            callback(canvas.toDataURL());
        };

        img.src = srcBase64;
    }

});




