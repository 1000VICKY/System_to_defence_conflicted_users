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
            $limit = 5;
            $event_list = Event::with([
                "attended_events",
            ])
            ->orderBy("event_start", "desc")
            ->paginate($limit);
            $today = date("Y-m-d H:i:s");
            foreach ($event_list as $key => $value) {
                $event_start = \DateTime::createFromFormat("Y-m-d H:i:s", $value->event_start);
                $today = new \DateTime();
                if ($event_start <= $today) {
                    $value->future = 0;
                } else {
                    $value->future = 1;
                }
            }
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
    public function detail (Request $request, Response $response, int $event_id)
    {
        try {
            // 指定したevent_idのイベント情報を取得
            $event_info = Event::find($event_id);
            if ($event_info === null) {
                throw new \Exception("イベントID[{$event_id}]に紐づくイベント情報の取得に失敗しました。");
            }

            // URLパラメータに指定された、event_idの参加者リストを取得
            $unique_user_list = AttendedEvent::with([
                "users",
            ])
            ->where("event_id", $event_id)
            ->get();

            print_r($unique_user_list->toArray());

            // 参加するユーザーのIDのみを配列化
            $unique_user_id_list = [];
            foreach ($unique_user_list as $key => $value) {
                $unique_user_id_list[] = $value->users->id;
            }

            print_r($unique_user_id_list);

            $contact_logs = [];
            foreach($unique_user_id_list as $key => $value) {
                $temp = AttendedEvent::with([
                    "users",
                ])
                ->whereHas("users", function ($query) use ($unique_user_id_list, $value) {

                    $query->where("id", "!=", $value)->whereIn("id", $unique_user_id_list);
                })
                ->where("event_id", "!=", $event_id)
                ->get();

                $contacted_user = [];
                foreach ($temp as $k => $v) {
                    $contacted_user[$v->users->id] = $v->users;
                }
                // ユーザーIDと接触ユーザーをまとめる
                $contact_logs[$value] = $contacted_user;
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
