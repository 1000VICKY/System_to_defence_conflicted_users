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
            // 現時点で過去のイベント一覧
            $past_event_list = Event::with([
                "attended_events",
            ])
            ->where("event_start", "<=", date("Y-m-d H:i:s"))
            ->orderBy("event_start", "desc")
            ->get();
            // 現時点でまだ開催されていないイベント一覧
            $future_event_list = Event::with([
                "attended_events",
            ])
            ->where("event_start", ">", date("Y-m-d H:i:s"))
            ->orderBy("event_start", "desc")
            ->get();
            // rendering
            return view("admin.event.index", [
                "past_event_list" => $past_event_list,
                "future_event_list" => $future_event_list,
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
    public function detail (Request $request, Response $response, int $event_id)
    {
        try {
            // 指定したevent_idのイベント情報を取得
            $event_info = Event::find($event_id);

            // URLパラメータに指定された、event_idの参加者リストを取得
            $unique_user_list = AttendedEvent::with([
                "users",
            ])
            ->where("event_id", $event_id)
            ->get();
            // print_r($temp->toArray());

            // 参加するユーザーのIDのみを配列化
            $unique_user_id_list = [];
            foreach ($unique_user_list as $key => $value) {
                $unique_user_id_list[] = $value->users->id;
            }

            $contact_logs = [];
            foreach($unique_user_id_list as $key => $value) {
                $attended_events = AttendedEvent::with([
                    "users",
                    "events"
                ])
                ->whereHas("users", function ($query) use ($unique_user_id_list, $value) {
                    $query->where("id", "!=", $value)->whereIn("id", $unique_user_id_list);
                })
                ->where("event_id", "!=", $event_id)
                ->get();

                // ユーザーIDと接触ユーザーをまとめる
                $contact_logs[$value] = $attended_events;
            }



            return view("admin.event.detail", [
                "unique_user_list" => $unique_user_list,
                "contact_logs" => $contact_logs,
                "event_info" => $event_info,
            ]);
        } catch (\Exception $e) {
            return view("error.index", [
                "error" => $e,
            ]);
        }
    }
}
