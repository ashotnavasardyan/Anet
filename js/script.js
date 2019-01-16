$(document).ready(function () {

    $('.post_media').css('height',$('.post_media').eq(0).css('width'));
    $(window).on('resize',function () {
        $('.post_media').css('height',$('.post_media').eq(0).css('width'));
    });

    if (document.getElementById('message_content')) {
        let scrollHeight = document.getElementById('message_content').scrollHeight;
        $('#message_content').scrollTop(scrollHeight);
    }

    $('#change_sign_in_box').on('click', function () {
        $('#sign_in').css('display', 'none');
        $('#registration').css('display', 'block');
    });

    $('#change_sign_up_box').on('click', function () {
        $('#sign_in').css('display', 'block');
        $('#registration').css('display', 'none');
    });

    $('#search_input').on('keyup', function () {
        var input = $(this);
        if (input.val().length === 0) {
            input.addClass('empty');
        } else {
            input.removeClass('empty');
        }
    });

    $('#user_subscribe').on('click', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const user_id = urlParams.get('user_id');
        let data = {'user_subscribe': true, 'user_id': user_id};
        $.ajax({
            url: 'database.php',
            data: data,
            type: 'POST',
            success: function (response) {
                var response = JSON.parse(response);
                if (response['subscribe']) {
                    $('#user_subscribe').text('Subscribed');
                    $('#user_subscribe').prop('disabled', true);
                }
            }
        });
    });


    $('.people_online_info::after').html('fwefew');

    var send_online_request = function () {
        let data = {'update_action':true};
        $.ajax({
            url: 'database.php',
            data: data,
            type: 'POST',
        })
    };
    send_online_request();
    setInterval(send_online_request,600000);

});
