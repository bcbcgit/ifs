<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Chat\ChatDanwa;
use App\Models\Chat\ChatLog;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class ChatDanwaController extends Controller
{
    protected const THIS_CHAT_ID = 'danwa';
    protected array $chatData;
    protected array $chatSetting;

    public function __construct()
    {
        $chatData = config('chat_const.' . self::THIS_CHAT_ID);
        if (!$chatData) abort(500, 'chat start error');
        $this->chatData = $chatData;
        $this->chatSetting = config('chat_setting', []);
    }

    public function index(Request $request)
    {
        $cookie = $request->cookie($this->chatData['KEY'].'_chat_setting');
        [$name, $nameColor, $messageColor] = $cookie ? explode('<>', $cookie) : [null, null, null];

        return view('chat.' . $this->chatData['ID'], [
            'CHAT_DATA' => $this->chatData,
            'CHAT_SETTING' => $this->chatSetting,
            'def_name' => $name ?? '',
            'def_name_color' => $nameColor ?? $this->chatData['BASE_NAME_COLOR'],
            'def_message_color' => $messageColor ?? $this->chatData['BASE_MESSAGE_COLOR'],
        ]);
    }

    public function store(Request $request)
    {
        Cookie::queue(cookie(
            $this->chatData['KEY'].'_chat_setting',
            "{$request->name}<>{$request->name_color}<>{$request->log_color}",
            60
        ));

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'nullable|string',
            'status' => 'required|in:enter,leave,log',
            'name_color' => 'nullable|string|max:100',
            'log_color' => 'nullable|string|max:100',
        ]);

        $data = array_merge($validated, [
            'room_code' => self::THIS_CHAT_ID,
            'visible'   => true,
        ]);

        // DB 保存
        ChatLog::create($data);

        return response()->json(['status' => 'ok']);
    }

    public function get_log(Request $request)
    {
        $limit = $request->input('limit', 200);

        $logs = ChatLog::where('room_code', self::THIS_CHAT_ID)
            ->latest()
            ->take($limit)
            ->get();

        $html = '';
        foreach ($logs as $log) {
            $time = $log->created_at?->format('Y/m/d H:i:s');

            switch ($log->status) {
                case 'enter':
                    $html .= "<div class='in'><span style='color:{$log->name_color}'>◆ {$log->name}</span> が入室しました";
                    if($log->message) {
                        $html .= "『<span style='color:{$log->log_color}'>{$log->message}</span>』";
                    }
                    $html .= " <time>{$time}</time>";
                    $html .= "</div>";
                    break;
                case 'leave':
                    $html .= "<div class='out'><span style='color:{$log->name_color}'>◆ {$log->name}</span> が退室しました";
                    $html .= " <time>{$time}</time>";
                    $html .= "</div>";
                    break;
                case 'log':
                    $html .= "<div class='log'><span style='color:{$log->name_color}'>◆ {$log->name}</span> » <span style='color:{$log->log_color}'>{$log->message}</span> <time>{$time}</time></div>";
                    break;
            }
        }

        return response($html);
    }
}
