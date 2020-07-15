<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UniqueUser;
use App\Models\AttendedEvent;
use App\Models\Log;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{






    /**
     * ユニークな会員データ一覧を表示(20件ずつ)する
     *
     * @param Request $request
     * @param Response $response
     * @param integer $limit
     * @return void
     */
    public function index(Request $request, Response $response, $limit = 20)
    {
        try {
            $parameter = $request->all();
            // 検索キーワードが入力されている場合
            if (array_key_exists("keyword", $parameter)) {
                $keyword = $parameter["keyword"];
                $unique_user_list = UniqueUser::where(function ($query) use ($keyword) {
                    $query->where(DB::raw("CONCAT(family_name_sort, given_name_sort)"), "like", mb_convert_kana("%{$keyword}%", "C", "UTF-8"))
                    // カタカナで検索
                    ->orWhere("family_name_sort", "like", mb_convert_kana("%{$keyword}%", "H", "UTF-8"))
                    ->orWhere("given_name_sort", "like", mb_convert_kana("%{$keyword}%", "H", "UTF-8"))
                    // ひらがなで検索
                    ->orWhere("family_name_sort", "like", mb_convert_kana("%{$keyword}%", "C", "UTF-8"))
                    ->orWhere("given_name_sort", "like", mb_convert_kana("%{$keyword}%", "C", "UTF-8"))
                    ->orWhere("family_name", "like", "%{$keyword}%")
                    ->orWhere("given_name", "like", "%{$keyword}%");
                })->paginate($limit);
            } else {
                $unique_user_list = UniqueUser::paginate($limit);
                $keyword = "";
            }
            return view("admin.user.index", [
                "keyword" => $keyword,
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
    public function all (Request $request, Response $response, int $limit = 20)
    {
        try {
            $logs = Log::orderBy("event_start", "desc")->paginate($limit);
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

            // 閲覧現時点で、参加したイベントのevent_idを取得する
            $attended_events = AttendedEvent::with([
                "events",
            ])
            ->whereHas("events", function ($query)  {
                $query->where("event_start", "<=", date("Y-m-d H:i:s"));
            })
            ->where("unique_user_id", $unique_user_id)
            ->get();

            /**
             * @var array $attended_events_id_list 過去の参加したevent_idの配列
             * そのため未来に参加予定のevent_idは含まない
             */
            $attended_events_id_list = [];
            if ($attended_events->count() > 0) {
                foreach ($attended_events as $key => $value) {
                    $attended_events_id_list[] = $value->event_id;
                }
            }
            echo ("<pre>");
            print_r($attended_events_id_list);
            echo ("</pre>");

            // 現時点で、開催前の参加予定の未来のイベント一覧
            $future_events = AttendedEvent::with([
                "events",
            ])
            ->whereHas("events", function ($query)  {
                $query->where("event_start", ">", date("Y-m-d H:i:s"));
            })
            ->where("unique_user_id", $unique_user_id)
            ->get();

            // 未来に参加予定のevent_idを含んだ過去の参加したevent_idの配列
            $attended_events_id_list_including_future = $attended_events_id_list;
            foreach($future_events as $key => $value) {
                $attended_events_id_list_including_future[] = $value->event_id;
            }

            // 過去の全イベントで接触したユーザーのリストを取得
            $contacted_user_id_list = [];
            $contacted_users = AttendedEvent::with([
                "users",
            ])
            ->whereIn("event_id", $attended_events_id_list)
            ->get();
            foreach($contacted_users as $key => $value) {
                $contacted_user_id_list [] = $value->users->id;
            }
            echo ("<pre>");
            print_r(array_unique($contacted_user_id_list));
            // print_r($contacted_users->toArray());
            echo ("</pre>");

            // 現時点で、開催前の参加予定のないの未来のイベント一覧を取得する
            $not_attended_events = Event::with([
                "attended_events",
            ])
            ->whereNotIn("id", $attended_events_id_list_including_future)
            ->where("event_start", ">", date("Y-m-d H:i:s"))
            ->get();

            echo ("<pre>");
            print_r($not_attended_events->toArray());
            echo ("</pre>");

            foreach ($not_attended_events as $key => $value) {
                // 参加予定のユーザーID
                $temp = [];
                foreach ($value->attended_events as $k => $v) {
                    $temp[] = $v->unique_user_id;
                }
                echo ("<pre>");
                print_r($temp);
                echo ("</pre>");
                $conflicted_user_id_list = array_intersect($contacted_user_id_list, $temp);
                // 現在のindexキーのイベントで衝突する人数
                $value->numerator = count($conflicted_user_id_list);
                // 過去に接触した全ユーザー人数
                $value->denominator = count($contacted_user_id_list);
                $value->percentage = (int) (count($conflicted_user_id_list) / count($contacted_user_id_list) * 100);
            }
            print_r($not_attended_events->toArray());
            print_r($attended_events_id_list);
            print_r($not_attended_events->toArray());

            return view("admin.user.detail", [
                // 閲覧中のユーザー情報を取得
                "unique_user_info" => $unique_user_info,
                // 過去の参加したイベント一覧
                "attended_events" => $attended_events,
                // 参加予定のイベント一覧
                "future_events" => $future_events,
                // 参加しない未来のイベント一覧
                "not_attended_events" => $not_attended_events,
            ]);
        } catch (\Exception $e) {
            // エラー画面
            return view("error.index", [
                "error" => $e,
            ]);
        }
    }



    /**
     * 指定したユーザーの接触履歴一覧を取得
     *
     * @param Request $request
     * @param Response $response
     * @param integer $unique_user_id
     * @return void
     */
    public function contact(Request $request, Response $response, int $unique_user_id)
    {
        try {
            // 閲覧中のユーザー情報を取得
            $unique_user_info = UniqueUser::findOrFail($unique_user_id);

            $attended_event_id_list = [];
            // 現時点で、参加した event_idの一覧を取得する
            $event_list = AttendedEvent::with([
                "events"
            ])
            ->whereHas("events", function ($query) {
                $query->where("event_start", "<=", date("Y-m-d H:i:s"));
            })
            ->where("unique_user_id", $unique_user_id)
            ->select("event_id")
            ->get();

            foreach ($event_list as $key => $value) {
                $attended_event_id_list[] = $value->event_id;
            }

            // 自身が参加したイベントに参加した他の会員ユーザー一覧を取得
            $contacted_user_list = AttendedEvent::with([
                "users"
            ])
            ->whereIn("event_id", $attended_event_id_list)
            ->where("unique_user_id", "!=", $unique_user_id)
            ->get();

            return view("admin.user.contact", [
                "unique_user_info" => $unique_user_info,
                "contacted_user_list" => $contacted_user_list,
            ]);
        } catch (\Exception $e) {
            // エラー画面
            return view("error.index", [
                "error" => $e,
            ]);
        }
    }

    /**
     * 指定したユーザーの指定したevent_idの参加状況を変更する
     *
     * @param Request $request
     * @param Response $response
     * @param integer $unique_user_id
     * @param integer $event_id
     * @return void
     */
    public function participate(Request $request, Response $response, int $unique_user_id, int $event_id)
    {
        try {


        } catch (\Exception $e) {
            return view("error.index", [
                "error" => $e,
            ]);
        }
    }
}
