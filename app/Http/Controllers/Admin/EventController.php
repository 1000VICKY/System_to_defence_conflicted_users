<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\UniqueUser;
use App\Models\Event;
use App\Models\AttendedEvent;
class EventController extends Controller
{




    /**
     * 登録済みのイベント情報一覧を表示する画面
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function index (Request $request, Response $response)
    {
        try {
            $event_list = Event::with([
                "attended_events",
            ])->orderBy("event_start", "desc")->get();

            // rendering
            return view("admin.event.index", [
                "event_list" => $event_list,
            ]);
        } catch (\Exception $e) {
            return view("error.index", [
                "error" => $e,
            ]);
        }
    }


    /**
     * 引数にイベントIDを持ち、そのイベントに紐づく全ユニークユーザー一覧を取得する
     *
     * @param Request $request
     * @param Response $response
     * @param integer $event_id
     * @return void
     */
    public function user (Request $request, Response $response, int $event_id)
    {
        try {
            $unique_user_list = AttendedEvent::with([
                "users"
            ])->where("event_id", $event_id)->get();

            return view("admin.event.user", [
                "unique_user_list" => $unique_user_list,
            ]);
        } catch (\Exception $e) {
            return view("error.index", [
                "error" => $e,
            ]);
        }
    }
}
