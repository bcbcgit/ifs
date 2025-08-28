@extends('layouts.base')

@section('contents','danwa')
@section('chat_code','danwa')
@section('page_title','談話室')

@section('scripts')
    <script>
        $(function() {
            get_log();

            var reloadTimer;
            reloadTimer = setInterval(function () {
                get_log();
            }, 20000);

            $(".inline").colorbox({
                inline:true,
                maxWidth:"90%",
                maxHeight:"90%",
                opacity: 0.7
            });

            $(".select_color").click(function(){
                console.log('色選択');
                var v = $('input[name=chat_color_select]:checked').val();
                var col = $(this).data('color');
                if(v == 1) {
                    $('#name_color').val(col);
                } else {
                    $('#log_color').val(col);
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

            $("#chat_enter").click(function () {
                chat_send();
                if ($("#name_color").val() !== null) {
                    $("#name_view").css('color', $("#name_color").val());
                }
                if ($("#log_color").val() !== null) {
                    $("#message").css('color', $("#log_color").val());
                }
                $("#name_view").html($("#name").val());
            });

            $("#submit").click(function () {
                var message = $("#in_message").val();
                if(!message) {
                    get_log();
                } else {
                    chat_send();
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

            $("#leave").click(function () {
                chat_send('leave');
            });

            function chat_send($status) {
                const room_code = "@yield('chat_code')";
                let chat_status = $status || $("#chat_status").val();

                let name = $("#name").val();
                let message = $("#in_message").val() || $("#message").val();

                let name_color = $("#name_color").val();
                let log_color = $("#log_color").val();

                let data = 'room_code=' + room_code + '&name=' + name + '&name_color=' + name_color +
                    '&log_color=' + log_color + '&message=' + message + '&status=' + chat_status;
                console.log(data);
                data = encodeURI(data);

                $.ajax({
                    type: "GET",
                    url: "/{{$CHAT_DATA['ID']}}/store",
                    cache: false,
                    data: data,
                    success: function (returnlog) {
                        if (returnlog) {
                            $("#message").val("");
                            if (chat_status === 'leave') {
                                $("#chat_in").css('display', 'none');
                                $("#chat_out").css('display', 'block');
                                $("#form_wrap").css('display', 'none');
                            } else {
                                $("#enter_form").css('display', 'none');
                                $("#send_form").css('display', 'block');
                                $("#leave").css('display', 'inline-block');
                                $("#name").val(name);
                                $("#name_color").val(name_color);
                                $("#log_color").val(log_color);
                                $("#chg_name_color").val(name_color);
                                $("#chg_log_color").val(log_color);
                                $("#chat_status").val("log");
                                $("#in_message").val('');
                            }
                            get_log();
                        } else {
                        }
                    },
                    error: function () {
                        alert('connect error : 101');
                    }
                });
                return false;
            }

            function get_log() {
                var chatid = {{$CHAT_DATA['ID']}};
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
                var chatid = {{$CHAT_DATA['ID']}};
                var limit = 400;
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
@endsection

@section('content')
    <div id="@yield('contents')" class="contents">
        <main id="chat_base">

            <div id="chat_header">
                <div id="form_wrap">
                    <form action="/{{$CHAT_DATA['ID']}}" method="post" id="chat_form">
                        <input type="hidden" name="room_code" value="@yield('chat_code')" id="room_code">
                        <input type="hidden" name="status" value="enter" id="chat_status">

                        <div id="enter_form">
                            <div id="chat_title">{{$CHAT_DATA['CHAT_NAME']}}</div>
                            <ul>
                                <li>
                                    <div>
                                        <span>名前</span>
                                        <span><input type="text" id="name" name="name" value="{{$def_name}}" /></span>
                                    </div>
                                    <div>
                                        <span>名前色</span>
                                        <span><input type="text" id="name_color" name="name_color" value="{{$def_name_color}}"/></span>
                                    </div>
                                    <div>
                                        <span>文字色</span>
                                        <span><input type="text" id="log_color" name="log_color" value="{{$def_message_color}}"/></span>
                                    </div>
                                    <div>
                                        <span>
                                            <a class="inline" href="#color_select_wrap"><span class="small-selector selector_btn">▼Color</span></a>
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <div id="message_wrap">
                                        <span>入室発言</span>
                                        <span><input type="text" id="message" name="message" value=""/></span>
                                    </div>
                                    <div id="chat_enter"><p class="btns">入室</p></div>
                                </li>
                            </ul>
                        </div>

                        <div id="send_form">
                            <input type="hidden" name="name" value="" id="name">
                            <input type="hidden" name="name_color" value="" id="name_color">
                            <input type="hidden" name="log_color" value="" id="log_color">
                            <div id="chat_title">{{$CHAT_DATA['CHAT_NAME']}}</div>
                            <ul>
                                <li>
                                    <div>
                                        <span id="name_view">名前</span>
                                    </div>
                                    <div>
                                        <span>
                                            <a class="inline" href="#color_select_wrap"><span class="small-selector selector_btn">▼Color</span></a>
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <div id="message_wrap">
                                        <span>入室発言</span>
                                        <span><input type="text" id="in_message" name="message" value=""/></span>
                                    </div>
                                    <div id="submit"><p class="btns">発言/更新</p></div>
                                </li>
                            </ul>
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

                    </form>
                    <div id="setting">
                        <div>
                            ｜
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
            </div>
            <div id="log_wrap"></div>
        </main>
    </div>
@endsection
