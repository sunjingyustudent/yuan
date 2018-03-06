/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var ws;
init();
function init() {
    timeid && window.clearInterval(timeid);
    ws = new WebSocket("ws://127.0.0.1:7272");
    ws.onopen = function () {
        var login_data = JSON.stringify({
            "type": "login",
            "roomid": roomid,
            "userid": userid
        });
        ws.send(login_data);
    };
    ws.onmessage = function (e) {
        var data = JSON.parse(e.data);
        switch (data.type) {
            case 'login':
                login(e.data);
                break;
            case 'initMsg':

                initMsg(e.data);
                break;
            case 'chat':
                chat(e.data);
                break;
            case 'gift':
                Gift(e.data);
                break;
            case 'updateMoney':
                UpdateMoney(e.data);
                break;

            case 'login_out':
                login_out(e.data);
                break;
        }
    };

    ws.onclose = function () {
        window.clearInterval(timeid);
        timeid = window.setInterval(init, 3000)
    };

    ws.onerror = function () {
        window.setTimeout(init, 3000)
    }

}
//接收登录信息
function login(data) {
    var data = JSON.parse(data);
    var html = ' <div class="message_content">'
            + '    <span class="message_content_time">' + data.time + '</span>'
            + ' <img src="/images/logo_icon.png" class="message_content_grade"/>'
            + ' <span class="message_content_name">' + data.username + '：</span>'
            + '  <span class="message_content_chat">进入房间</span>'
            + ' </div>';
    $('.live_show_chat').find('.chat_message_content').append(html);

    $('.live_wrap .live_wrap_renqi').html(data.person_num);
}
//用户退出

function login_out(data) {
    var data = JSON.parse(data);
    $('.live_wrap .live_wrap_renqi').html(data.person_num);
}

//接收初始化信息
function initMsg(data) {
    var data = JSON.parse(data);
    var list = data.message;
    var html = "";
    for (var i = list.length - 1; i >= 0; i--) {
        if (list[i].type == 1) {
            html += ' <div class="message_content">'
                    + ' <span class="message_content_time">' + list[i].time + '</span>'
                    + ' <img src="/images/logo_icon.png" class="message_content_grade"/>'
                    + ' <span class="message_content_name">' + list[i].from_username + '：</span>'
                    + '  <span class="message_content_chat">' + list[i].content + '</span>'
                    + ' </div>';
        }
        if (list[i].type == 2) {
            html += '<div class="message_content">'
                    + ' <span class="message_content_time">' + list[i].time + '</span>'
                    + '<img src="/images/logo_icon.png" class="message_content_grade"/>'
                    + ' <span class="message_content_name">' + list[i].from_username + '</span>'
                    + '<span class="message_content_chat">赠送给主播</span>'
                    + '<img src="' + list[i].content + '" class="message_content_gift"/>'
                    + '<span>X1</span>'
                    + ' </div>';
        }

    }
    $('.live_show_chat').find('.chat_message_content').append(html);
    $('.chat_message_content').scrollTop($('.chat_message_content')[0].scrollHeight);
}

//发送聊天信息     
$('.chat_message .send_messgae').on('click', function () {
    var message = $('.chat_message_box_input').val();
    var message_data = JSON.stringify({
        "type": "chat",
        "content": message
    });
    ws.send(message_data);
    $('.chat_message_box_input').val("");
});



//接收聊天信息
function  chat(data)
{
    var data = JSON.parse(data);
    var html = ' <div class="message_content">'
            + '    <span class="message_content_time">' + data.time + '</span>'
            + ' <img src="/images/logo_icon.png" class="message_content_grade"/>'
            + ' <span class="message_content_name">' + data.username + '：</span>'
            + '  <span class="message_content_chat">' + data.content + '</span>'
            + ' </div>';
    $('.live_show_chat').find('.chat_message_content').append(html);
    $('.chat_message_content').scrollTop($('.chat_message_content')[0].scrollHeight);
}

var giftid = 0;
$(document).on('click', '.live_gift_box', function () {
    giftid = $(this).attr("rel");
    $('.live_gift_box').css("border", "none");
    $(this).css("border", "2px solid orange");
});

$(document).on('click', '.live_show_video_gift .sendGift', function () {
    if (giftid == 0) {
        alert("发送礼物不能为空");
        return;
    }
    var message_data = JSON.stringify({
        "type": "gift",
        "giftid": giftid
    });
    ws.send(message_data);
});

function Gift(data) {
    console.log(data);
    var data = JSON.parse(data);

    if (data.code != 1) {
        alert(data.msg);
    } else {
        var html = '<div class="message_content">'
                + ' <span class="message_content_time">' + data.time + '</span>'
                + '<img src="/images/logo_icon.png" class="message_content_grade"/>'
                + ' <span class="message_content_name">' + data.username + '</span>'
                + '<span class="message_content_chat">赠送给主播</span>'
                + '<img src="' + data.gifturl + '" class="message_content_gift"/>'
                + '<span>X1</span>'
                + ' </div>';

        var pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        var code = "gifttx1";
        var maxPos = pattern.length;
        for (i = 0; i < maxPos; i++)
        {
            code += code.charAt(Math.floor(Math.random() * maxPos));
        }

        var giftTx = '<div class="gifttx ' + code + '"><img src="/images/gift' + data.giftid + '.jpg" class="gifttx_img" /></div>';
        $('.live_wrap').find('.gifttx_box').append(giftTx);
        $('.gifttx').addClass('gifttx1');
        window.setTimeout(function () {
            $("." + code + "").remove();
        }, 2000);

        $('.live_show_chat').find('.chat_message_content').append(html);
        $('.chat_message_content').scrollTop($('.chat_message_content')[0].scrollHeight);

    }
}



function UpdateMoney(data)
{
    var data = JSON.parse(data);
    $.get('/base/reset-userinfo?userid=' + data.userid, function (res) {
        var res = JSON.parse(res);
        $('.send_div .yuanbao_num').html(res.gold);
    });
}




