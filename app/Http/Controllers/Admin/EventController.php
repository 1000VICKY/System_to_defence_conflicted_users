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
    public function index (Request $request, Response $response, int $limit = 5)
    {
        try {
            // GETパラメータの取得
            $parameter = $request->all();
            $keyword = "";
            $event_start = "";

            // 検索条件を加味してSQLを実行
            $event_list = Event::with([
                "attended_events"
            ]);
            if (array_key_exists("keyword", $parameter) && strlen($parameter["keyword"]) > 0) {
                $keyword = $parameter["keyword"];
                $event_list->where("event_name", "like", "%{$keyword}%");
            }
            if (array_key_exists("event_start", $parameter)) {
                $event_start = $parameter["event_start"];
                $date = \DateTime::createFromFormat("Y-m-d", $event_start);
                if ($date !== false && $event_start === $date->format("Y-m-d")){
                    $event_list
                    ->where("event_start", ">=", $event_start." "."00:00:00")
                    ->where("event_start", "<=", $event_start." "."23:59:59");
                }
            }
            $event_list = $event_list
            ->orderBy("event_start", "desc")
            ->paginate($limit);

            $today = date("Y-m-d H:i:s");
            foreach ($event_list as $key => $value) {
                $_event_start = \DateTime::createFromFormat("Y-m-d H:i:s", $value->event_start);
                $today = new \DateTime();
                if ($_event_start <= $today) {
                    $value->future = 0;
                } else {
                    $value->future = 1;
                }
            }
            // rendering
            return view("admin.event.index", [
                "keyword" => $keyword,
                "event_start" => $event_start,
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
            $event_info = Event::findOrFail($event_id);
            if ($event_info === null) {
                throw new \Exception("イベントID[{$event_id}]に紐づくイベント情報の取得に失敗しました。");
            }

            // 現在閲覧中のイベントの全参加者一覧
            $attended_unique_users = AttendedEvent::with([
                "users",
            ])
            // 参加スタータスが1のもののみ
            ->where("is_participated", Config("const.participated_status.is_participated"))
            ->where("event_id", $event_id)
            ->get();

            // print_r($unique_users->toArray());

            // 参加者のユーザーIDのみを配列化
            $unique_user_id_list = [];
            foreach ($attended_unique_users as $key => $value) {
                $unique_user_id_list[] = $value->unique_user_id;
            }
            // 参加者のみのunique_user_id一覧
            // print_r($unique_user_id_list);

            $attended_event_id_list= [];
            $contacted_users = [];
            foreach ($attended_unique_users as $key => $value) {
                $_id = $value->unique_user_id;

                // unique_user_id === $_idのユーザーの過去の参加履歴一覧を取得
                $attended_event_logs = AttendedEvent::with([
                    "events"
                ])
                ->whereHas("events", function ($query) {
                    $query->where("event_start", "<=", date("Y-m-d H:i:s"));
                })
                ->where("is_participated", Config("const.participated_status.is_participated"))
                ->where("unique_user_id", $_id)
                ->where("event_id", "!=", $event_id)
                ->get();

                // print_r($attended_event_logs->toArray());
                $attended_event_id_list[$_id] = [];
                foreach ($attended_event_logs as $in_key => $in_value) {
                    $attended_event_id_list[$_id][] = $in_value->event_id;
                }
                // print_r($attended_event_id_list);
                // print_r($attended_event_id_list);

                $temp = AttendedEvent::with([
                    "users"
                ])
                ->where("unique_user_id", "!=", $_id)
                ->whereIn("unique_user_id", $unique_user_id_list)
                ->whereIn("event_id", $attended_event_id_list[$_id])
                ->where("is_participated", Config("const.participated_status.is_participated"))
                ->get();
                $contacted_users[$_id] = $temp;
            }
            return view("admin.event.detail", [
                "attended_unique_users" => $attended_unique_users,
                "contacted_users" => $contacted_users,
                "event_info" => $event_info,
            ]);
        } catch (\Exception $e) {
            return view("error.index", [
                "error" => $e,
            ]);
        }
    }

    /**
     * これまでの全イベントへの参加履歴を表示
     *
     * @param Request $request
     * @param Response $response
     * @param integer $limit
     * @return void
     */
    public function log(Request $request, Response $response, int $limit = 200)
    {
        try {
            $result = AttendedEvent::with([
                "users",
                "events",
            ])
            ->whereHas("events", function ($query) {
                $query->where("event_start", "<=", date("Y-m-d H:i:s"));
            })
            ->orderBy("unique_user_id", "desc")
            ->orderBy("id", "desc")
            ->paginate($limit);

            return view("admin.event.log", [
                "all_logs" => $result,
            ]);
        } catch (\Exception $e) {
            return view("error.index", [
                "error" => $e,
            ]);
        }
    }
}
