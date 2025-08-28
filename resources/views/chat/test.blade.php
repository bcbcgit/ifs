@extends('layouts.base')

@section('chat_code','danwa')

@section('content')
<main id="chat_base">

    <div id="chat_header">
        <div id="form_wrap">
            <form action="/{{$CHAT_DATA['ID']}}" method="post" id="chat_form">
                <input type="hidden" name="room_code" value="@yield('chat_code')" id="room_code">
                <input type="hidden" name="status" value="enter" id="chat_status">
                <div id="enter_form">
                    <div id="chat_title">{{$CHAT_DATA['CHAT_NAME']}}</div>
                    <input type="hidden" name="act" value="chatin">
                    <label>名前<input type="text" id="name" name="name" value="{{$def_name}}"/></label>
                    <label>名前色<input type="text" id="name_color" name="name_color" value="{{$def_name_color}}"/></label>
                    <label>文字色<input type="text" id="log_color" name="log_color" value="{{$def_message_color}}"/></label>
                    <a class="inline" href="#color_select_wrap"><span class="small-selector selector_btn">▼Color</span></a>
                    <div id="message_wrap">
                        <label class="nowp"><span>入室発言</span>
                            <input type="text" id="message" name="message" value=""/>
                        </label>
                        <div id="chat_enter">
                            <div class="btns">入室</div>
                        </div>
                    </div>

                    <div style="display: none;">
                        <section id="color_select_wrap">
                            <h3>色見本</h3>
                            <div id="select_else">
                                <label><input type="radio" name="chat_color_select" value="1" checked>名前色</label>
                                <label><input type="radio" name="chat_color_select" value="2">文字色</label>
                            </div>
                            <div id="color_list">
                                @foreach($CHAT_SETTING['chat_color'] as $colors)
                                    <span class="select_color" style="color:{{$colors}};" data-color="{{$colors}}">■ {{$colors}}</span>
                                @endforeach
                            </div>
                        </section>
                    </div>

                </div>
                <div id="send_form">
                    <div id="chat_title">{{$CHAT_DATA['CHAT_NAME']}}</div>
                    <label><span id="name_view">名前</span></label>
                    <input type="hidden" name="name" value="" id="name">
                    <input type="hidden" name="name_color" value="" id="name_color">
                    <input type="hidden" name="log_color" value="" id="log_color">
                    <div id="message_wrap">
                        <label><input type="text" id="in_message" name="message" value=""/></label>
                        <div id="submit" class="btns">発言/更新</div>
                    </div>
                </div>
            </form>
            <div id="setting">
                <div>
                    <a href="/">IFSに戻る</a>｜
                    <span id="view_common_log">通常ログ</span>｜
                    <span id="view_all_log">全ログ</span>｜
                    <span id="leave">退出</span>
                </div>
            </div>
        </div>
        <div id="chat_out">
            <div>ご利用ありがとうございました</div>
            <a href="/{{$CHAT_DATA['ID']}}">▶ チャットに戻る</a>
        </div>
        <div id="log_wrap"></div>
    </div>

    <script src="/js/jquery-3.1.1.min.js"></script>
    <script src="/js/jquery.colorbox.js"></script>
    <script src="/js/sweetalert2.all.min.js"></script>
    <script type="text/javascript">
        $(function () {
            get_log();
            var reloadTimer;
            reloadTimer = setInterval(function () {
                get_log();
            }, 20000);
            $("#leave").click(function () {
                Swal.fire({
                    title: "チャットを退出しますか？",
                    showCancelButton: true,
                    confirmButtonText: "退出する",
                }).then((result) => {
                    if (result.isConfirmed) {
                        chat_send('leave','@yield('chat_code')');
                    }
                });
            });
            $("#submit").click(function () {
                var message = $("#in_message").val();
                if(!message) {
                    get_log();
                } else {
                    chat_send();
                }
            });
            $('#chat_form').keypress(function (e) {
                var message = $("#in_message").val();
                if (e.which == 13) {
                    if(message) {
                        chat_send();
                        if ($("#name_color").val() !== null) {
                            $("#name_view").css('color', $("#name_color").val());
                        }
                        if ($("#logcolor").val() !== null) {
                            $("#message").css('color', $("#log_color").val());
                        }
                        $("#name_view").html($("#name").val());
                    } else {
                        get_log();
                    }
                }
            });
            $("#view_all_log").click(function () {
                clearInterval(reloadTimer);
                view_all_log();
            });
            $("#view_common_log").click(function () {
                get_log();
                reloadTimer = setInterval(function () {
                    get_log();
                }, 20000);
            });

            function chat_send($status) {
                var chatid = {{$CHAT_DATA['KEY']}};
                if ($status) {
                    var chat_status = $status;
                } else {
                    var chat_status = $("#chat_status").val();
                }
                var room_code = @yield('chat_code');

                if (chat_status == 'enter') {
                    var name = $("#name").val();
                    var message = $("#message").val();
                    var name_color = $("#name_color").val();
                    var log_color = $("#log_color").val();
                } else {
                    var name = $("#name").val();
                    var message = $("#in_message").val();
                    var name_color = $("#name_color").val();
                    var log_color = $("#log_color").val();
                }
                var data = 'room_code=' + room_code + '&name=' + name + '&name_color=' + name_color + '&log_color=' + log_color + '&message=' + message + '&status=' + chat_status;
                data = encodeURI(data);
                //x_MyClass.getResult(data,reWrite_cb);

                $.ajax({
                    type: "GET",
                    url: "/{{$CHAT_DATA['ID']}}/store",
                    cache: false,
                    data: data,
                    success: function (backerror) {
                        if (backerror) {
                            swal({
                                title: "",
                                text: backerror,
                                type: "error",
                                allowOutsideClick: true,
                            });
                        } else {
                            $("#in_message").val("");
                            if (chat_status === 'leave') {
                                $("#chat_in").css('display', 'none');
                                $("#chat_out").css('display', 'block');
                                $("#form_wrap").css('display', 'none');
                            } else {
                                $("#enter_form").css('display', 'none');
                                $("#send_form").css('display', 'block');
                                $("#name").val(name);
                                $("#name_color").val(name_color);
                                $("#log_color").val(log_color);
                                $("#chg_name_color").val(name_color);
                                $("#chg_log_color").val(log_color);

                                $("#chat_status").val("2");
                                $("#leave").css('display', 'block');
                                $("#open_btn").css('display', 'block');
                            }
                            get_log();
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert('connect error : 101');
                    }
                });
                return false;
            }

            function get_log() {
                var chatid = {{$CHAT_DATA['KEY']}};
                var limit = 20;
                var data = 'chatid=' + chatid + '&limit=' + limit;

                $.ajax({
                    type: "GET",
                    url: "/{{$CHAT_DATA['ID']}}/log",
                    data: data,
                    success: function (html) {
                        $("#log_wrap").html(html);
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        //alert('connect error : 102');
                    }
                });
            }

            function view_all_log() {
                var chatid = {{$CHAT_DATA['KEY']}};
                var limit = '';
                var data = 'chatid=' + chatid + '&limit=' + limit;

                $.ajax({
                    type: "GET",
                    url: "/{{$CHAT_DATA['ID']}}/log",
                    data: data,
                    success: function (html) {
                        $("#log_wrap").html(html);
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        //alert('connect error : 102');
                    }
                });
            }

        });
    </script>
</main>
@endsection
