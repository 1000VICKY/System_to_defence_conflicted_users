<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UniqueUser;
use App\Models\AttendedEvent;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
class UserController extends Controller
{






    /**
     * ユニークな会員データ一覧を表示する
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function index(Request $request, Response $response)
    {
        try {
            $unique_user_list = UniqueUser::all();

            return view("admin.user.index", [
                "unique_user_list" => $unique_user_list,
            ]);
        } catch (\Exception $e) {
            // エラー画面
            return view("error.index", [
                "error" => $e,
            ]);
        }
    }


    /**
     * CSVファイル取り込み分の生データを表示
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function all (Request $request, Response $response)
    {
        try {
            $logs = Log::orderBy("event_start", "desc")->get();
            return view("admin.user.all", [
                "logs" => $logs,
            ]);
        } catch (\Exception $e) {
           // エラー画面
            return view("error.index", [
            "error" => $e,
            ]);
        }
    }


    /**
     * 指定したユニークユーザーの詳細な参加履歴を表示
     *
     * @param Request $request
     * @param Response $response
     * @param integer $unique_user_id
     * @return void
     */
    public function detail(Request $request, Response $response, int $unique_user_id)
    {
        try {
            // 現在選択中のユーザー情報を取得
            $unique_user_info = UniqueUser::findOrFail($unique_user_id);
            // print_r($unique_user_info->toArray());
            // 開催済みかつ参加したevent_idの配列
            $attended_events_id_list = [];
            // 参加したイベントのevent_idを取得する
            $attended_events = AttendedEvent::with([
                "events",
                "users"
            ])
            ->whereHas("events", function ($query)  {
                $query->where("event_start", "<=", date("Y-m-d H:i:s"));
            })
            ->where("unique_user_id", $unique_user_id)
            ->get();
            if ($attended_events->count() > 0) {
                foreach ($attended_events as $key => $value) {
                    $attended_events_id_list[] = $value->event_id;
                }
            }

            // 現時点で、開催前の参加予定イベント一覧
            $before_events = AttendedEvent::with([
                "events",
                "users"
            ])
            ->whereHas("events", function ($query)  {
                $query->where("event_start", ">", date("Y-m-d H:i:s"));
            })
            ->where("unique_user_id", $unique_user_id)
            ->get();

            $contacted_users = AttendedEvent::with([
                "users",
            ])
            ->whereIn("event_id", $attended_events_id_list)
            ->get();
            print_r($contacted_users->toArray());



            return view("admin.user.detail", [
                // 閲覧中のユーザー情報を取得
                "unique_user_info" => $unique_user_info,
                // 過去の参加したイベント一覧
                "attended_events" => $attended_events,
                // 参加予定のイベント一覧
                "before_events" => $before_events,
            ]);
        } catch (\Exception $e) {
            // エラー画面
            return view("error.index", [
                "error" => $e,
            ]);
        }
    }
}
