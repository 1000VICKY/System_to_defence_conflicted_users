<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UniqueUser;
use App\Models\AttendedEvent;
use App\Models\Log;
use App\Models\Event;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{



    private $gender_list  = [
        "男性" => "女性",
        "女性" => "男性",
    ];


    /**
     * ユニークな会員データ一覧を表示(20件ずつ)する
     *
     * @param Request $request
     * @param Response $response
     * @param integer $limit
     * @return void
     */
    public function index(UserRequest $request, Response $response, $limit = 200)
    {
        try {
            $parameter = $request->all();
            $keyword = "";
            $email = "";
            // 検索用キーワードによってSQLを変化させる
            $unique_user_list = UniqueUser::where("is_displayed", Config("const.display_status.is_displayed"))
            ->where("is_deleted", Config("const.display_status.not_displayed"));
            // 検索用フリーワードが入力されている場合
            if (array_key_exists("keyword", $parameter)) {
                $keyword = $parameter["keyword"];
                $unique_user_list = $unique_user_list->where(function ($query) use ($keyword) {
                    $query->where(DB::raw("CONCAT(family_name_sort, given_name_sort)"), "like", mb_convert_kana("%{$keyword}%", "C", "UTF-8"))
                    // カタカナで検索
                    ->orWhere("family_name_sort", "like", mb_convert_kana("%{$keyword}%", "H", "UTF-8"))
                    ->orWhere("given_name_sort", "like", mb_convert_kana("%{$keyword}%", "H", "UTF-8"))
                    // ひらがなで検索
                    ->orWhere("family_name_sort", "like", mb_convert_kana("%{$keyword}%", "C", "UTF-8"))
                    ->orWhere("given_name_sort", "like", mb_convert_kana("%{$keyword}%", "C", "UTF-8"))
                    ->orWhere("family_name", "like", "%{$keyword}%")
                    ->orWhere("given_name", "like", "%{$keyword}%");
                });
            }
            $unique_user_list->orderBy("id", "desc");
            // 検索用emailアドレスが入力されている場合
            if (array_key_exists("email", $parameter)) {
                $email = $parameter["email"];
                $unique_user_list = $unique_user_list->where("email", "like", "%{$email}%");
            }
            // 以上の条件でSQLを絞り込む
            $unique_user_list = $unique_user_list->paginate($limit);
            return view("admin.user.index", [
                "keyword" => $keyword,
                "email" => $email,
                "unique_user_list" => $unique_user_list,
            ]);
        } catch (\Exception $e) {
            // エラー画面
            return view("errors.index", [
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
    public function all (UserRequest $request, Response $response, int $limit = 200)
    {
        try {
            $logs = Log::orderBy("event_start", "desc")
            ->orderBy("reception_date", "desc")
            ->paginate($limit);
            return view("admin.user.all", [
                "logs" => $logs,
            ]);
        } catch (\Exception $e) {
           // エラー画面
            return view("errors.index", [
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
    public function detail(UserRequest $request, Response $response, int $unique_user_id)
    {
        try {
            // 現在選択中のユーザー情報を取得
            $unique_user_info = UniqueUser::find($unique_user_id);
            if ($unique_user_info === NULL) {
                throw new \Exception("指定したユーザーの詳細情報が取得できませんでした。");
            }

            // 閲覧現時点で、参加したイベントのevent_idを取得する
            $attended_events = Event::with([
                "attended_events"
            ])
            ->whereHas("attended_events", function ($query) use ($unique_user_id) {
                $query->where("is_participated", Config("const.participated_status.is_participated"))
                ->where("unique_user_id", $unique_user_id);
            })
            ->where("event_start", "<=", date("Y-m-d H:i:s"))
            ->orderBy("event_start", "desc")
            ->get();

            // var_dump($attended_events);

            // 参加済みのevent_idの配列(※未来のevent_idは含まない)
            $attended_events_id_list = [];
            if ($attended_events->count() > 0) {
                foreach ($attended_events as $key => $value) {
                    $attended_events_id_list[] = $value->id;
                }
            }
            // echo ("<pre>");
            // print_r($attended_events_id_list);
            // echo ("</pre>");


            // 現時点で、開催前の参加予定の未来のイベント一覧
            $future_events = Event::with([
                "attended_events" => function ($query) use ($unique_user_id) {
                    $query->where("is_participated", Config("const.participated_status.is_participated"))
                    ->where("unique_user_id", $unique_user_id);
                },
            ])
            ->whereHas("attended_events", function ($query) use ($unique_user_id) {
                $query->where("is_participated", Config("const.participated_status.is_participated"))
                ->where("unique_user_id", $unique_user_id);
            })
            ->where("event_start", ">", date("Y-m-d H:i:s"))
            ->orderBy("event_start", "desc")
            ->get();
            // print_r($future_events->toArray());



            // var_dump($attended_events_id_list);
            // 未来に参加予定のevent_idを含んだ過去の参加したevent_idの配列
            $attended_events_id_list_including_future = $attended_events_id_list;
            foreach($future_events as $key => $value) {
                $attended_events_id_list_including_future[] = $value->id;
            }
            // print_r($attended_events_id_list_including_future);


            $self = $this;
            // 過去の全イベントで接触したユーザーのリストを取得
            $contacted_user_id_list = [];
            $contacted_users = UniqueUser::with([
                "attended_events"
            ])
            ->whereHas("attended_events", function ($query) use ($attended_events_id_list) {
                $query->where("is_participated", Config("const.participated_status.is_participated"))
                ->whereIn("event_id", $attended_events_id_list);
            })
            ->where("gender", $this->gender_list[$unique_user_info->gender])
            ->get();
            // print_r($contacted_users->toArray());


            foreach($contacted_users as $key => $value) {
                $contacted_user_id_list [] = $value->id;
            }
            // print_r($contacted_user_id_list);
            // echo ("<pre>");
            // print_r(array_unique($contacted_user_id_list));
            // print_r($contacted_users->toArray());
            // echo ("</pre>");
            // print_r($attended_events_id_list_including_future);

            // 現時点で、開催前の参加予定のないの未来のイベント一覧を取得する
            $not_attended_events = Event::with([
                // "attended_events" => function ($query) {
                //     // $query->where("is_participated", Config("const.participated_status.is_participated"));
                // }
            ])
            // ->whereHas("attended_events", function ($query) {
            //     // $query->where("is_participated", Config("const.participated_status.is_participated"));
            // })
            ->whereNotIn("id", $attended_events_id_list_including_future)
            ->where("event_start", ">", date("Y-m-d H:i:s"))
            ->orderBy("event_start", "desc")
            ->get();
            //print_r($not_attended_events->toArray());

            // echo ("<pre>");
            // print_r($not_attended_events->toArray());
            // echo ("</pre>");

            foreach ($not_attended_events as $key => $value) {
                // 参加予定のユーザーID
                $temp = [];
                foreach ($value->attended_events as $k => $v) {
                    $temp[] = $v->unique_user_id;
                }
                // echo ("<pre>");
                // print_r($temp);
                // echo ("</pre>");
                $conflicted_user_id_list = array_intersect($contacted_user_id_list, $temp);
                // 現在のindexキーのイベントで衝突する人数
                $value->numerator = count($conflicted_user_id_list);
                // 過去に接触した全ユーザー人数
                $value->denominator = count($contacted_user_id_list);
                if (count($contacted_user_id_list) !== 0 ) {
                    $value->percentage = (int) (count($conflicted_user_id_list) / count($contacted_user_id_list) * 100);
                } else {
                    $value->percentage =  0;
                }
            }
            // print_r($attended_events_id_list);

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
            return view("errors.index", [
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
    public function contact(UserRequest $request, Response $response, int $unique_user_id)
    {
        try {
            // 閲覧中のユーザー情報を取得
            $unique_user_info = UniqueUser::find($unique_user_id);
            if ($unique_user_info === NULL) {
                throw new \Exception("指定した会員情報がみつかりません。");
            }

            // 現時点で、参加した event_idの一覧を取得する
            $event_list = AttendedEvent::with([
                "events"
            ])
            ->whereHas("events", function ($query) {
                $query->where("event_start", "<=", date("Y-m-d H:i:s"));
            })
            ->where("is_participated", Config("const.participated_status.is_participated"))
            ->where("unique_user_id", $unique_user_id)
            ->select("event_id")
            ->get();

            // 参加済みevent_idの配列
            $attended_event_id_list = [];
            foreach ($event_list as $key => $value) {
                $attended_event_id_list[] = $value->event_id;
            }

            // $self = $this;
            // 自身が参加したイベントに参加した他の会員ユーザー一覧を取得
            // $contacted_user_list = AttendedEvent::with([
            //     "users"
            // ])
            // ->whereHas("users", function ($query) use ($self, $unique_user_info) {
            //     $query->where("gender", $self->gender_list[$unique_user_info->gender]);
            // })
            // ->whereIn("event_id", $attended_event_id_list)
            // ->where("is_participated", Config("const.participated_status.is_participated"))
            // ->where("unique_user_id", "!=", $unique_user_id)
            // ->get();
            $contacted_user_list = UniqueUser::with([
                "attended_events",
            ])
            ->whereHas("attended_events", function ($query) use ($attended_event_id_list) {
                $query->whereIn("event_id", $attended_event_id_list)
                ->where("is_participated", Config("const.participated_status.is_participated"));
            })
            ->where("gender", $this->gender_list[$unique_user_info->gender])
            ->where("id", "!=", $unique_user_id)
            ->get();
            // print_r($contacted_user_list->toArray());


            return view("admin.user.contact", [
                "unique_user_info" => $unique_user_info,
                "contacted_user_list" => $contacted_user_list,
            ]);
        } catch (\Exception $e) {
            // エラー画面
            return view("errors.index", [
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
    public function participate(UserRequest $request, Response $response, int $unique_user_id, int $event_id)
    {
        try {
            $posted_data = $request->all();
            // 指定した、組み合わせでイベントの参加履歴を参照
            $attended_event = AttendedEvent::where("unique_user_id", $unique_user_id)
            ->where("event_id", $event_id)
            ->get()
            ->first();

            // レコードが存在しない場合
            if ($attended_event === NULL) {
                $result = AttendedEvent::create($posted_data);
            } else {
                $result = $attended_event->fill($posted_data)->save();
                if ($result !== true) {
                    throw new \Exception("指定した参加履歴のアップデートに失敗しました。");
                }
            }
            return redirect()->action("Admin\UserController@detail", [
                "unique_user_id" => $unique_user_id,
            ]);
        } catch (\Exception $e) {
            return view("errors.index", [
                "error" => $e,
            ]);
        }
    }

    /**
     * 新規会員データの登録画面
     *
     * @param UserRequest $request
     * @param Response $response
     * @return void
     */
    public function create(UserRequest $request, Response $response)
    {
        try {
            $age_list = [];
            foreach (range(16, 100) as $key => $value) {
                $age_list[$value] = $value."歳";
            };
            $gender_list = [
                "男性" => "男性",
                "女性" => "女性",
            ];
            return view("admin.user.create", [
                "age_list" => $age_list,
                "gender_list" => $gender_list,
            ]);
        } catch (\Exception $e) {
            return view("errors.index", [
                "error" => $e,
            ]);
        }
    }

    public function postCreate(UserRequest $request, Response $response)
    {
        try {
            $posted_data = $request->all();
            // メールアドレスまたは電話番号のの重複チェック
            $result = UniqueUser::where("email", $posted_data["email"])
            ->orWhere("phone_number", $posted_data["phone_number"])
            ->orWhere("reception_number", $posted_data["reception_number"])
            ->get()
            ->first();
            if ($result !== NULL) {
                throw new \Exception("メールアドレス[{$posted_data["email"]}]または電話番号[{$posted_data["phone_number"]}]のユーザーは既に存在します。");
            }

            // ユニークユーザーの重複チェック
            $unique_user = UniqueUser::where("reception_number", "!=", $posted_data["reception_number"])
            ->where([
                ["family_name", "=", $posted_data["family_name"]],
                ["given_name", "=", $posted_data["given_name"]],
                ["phone_number", "=", $posted_data["phone_number"]],
            ])->orWhere([
                ["family_name", "=", $posted_data["family_name"]],
                ["given_name", "=", $posted_data["given_name"]],
                ["email", "=", $posted_data["email"]],
            ])
            ->get();
            if ($unique_user->count() !== 0) {
                throw new \Exception("指定したユーザー情報が既に存在します。");
            }
            $result = UniqueUser::create($posted_data);
            if ($result->id > 0) {
                return redirect()->action("Admin\UserController@detail", [
                    "unique_user_id" => $result->id,
                ]);
            } else {
                throw new \Exception("会員情報の登録に失敗しました。");
            }
        } catch (\Exception $e) {
            return view("errors.index", [
                "error" => $e,
            ]);
        }
    }

    /**
     * 指定したユーザーの会員情報を更新する
     *
     * @param Request $request
     * @param Response $response
     * @param integer $unique_user_id
     * @return void
     */
    public function update(Request $request, Response $response, int $unique_user_id)
    {
        try {
            $age_list = [];
            foreach (range(16, 100) as $key => $value) {
                $age_list[$value] = $value."歳";
            };
            $gender_list = [
                "男性" => "男性",
                "女性" => "女性",
            ];
            $result = UniqueUser::findOrFail($unique_user_id);
            if ($result === NULL) {
                throw new \Exception ("指定したユーザーが存在しません。");
            }
            return view("admin.user.update", [
                "unique_user_info" => $result,
                "age_list" => $age_list,
                "gender_list" => $gender_list,
            ]);
        } catch (\Exception $e) {
            return view("errors.index", [
                "error" => $e,
            ]);
        }
    }

    public function postUpdate(UserRequest $request, Response $response, int $unique_user_id)
    {
        try {
            $posted_data = $request->all();
            // メールアドレスまたは電話番号のの重複チェック
            // $result = UniqueUser::where(function ($query) use ($posted_data) {
            //     $query->where([
            //         ["family_name", "=", $posted_data["family_name"]],
            //         ["given_name", "=", $posted_data["given_name"]],
            //         ["phone_number", "=", $posted_data["phone_number"]]
            //     ])
            //     ->orWhere([
            //         ["family_name", "=", $posted_data["family_name"]],
            //         ["given_name", "=", $posted_data["given_name"]],
            //         ["email", "=", $posted_data["email"]],
            //     ]);
            // })
            // ->where("reception_number", "!=", $posted_data["reception_number"])
            // ->where("id", "!=", $unique_user_id)
            // ->get()
            // ->first();

            // if ($result !== NULL) {
            //     throw new \Exception("お名前[{$posted_data["family_name"]} {$posted_data["given_name"]}]とメールアドレス[{$posted_data["email"]}]または電話番号[{$posted_data["phone_number"]}]のユーザーは既に存在します。");
            // }
            // ユーザーの重複検証
            $unique_user = UniqueUser::findOrFail($unique_user_id);
            // ユニークユーザーの重複チェック
            $temp = UniqueUser::where("reception_number", "!=", $posted_data["reception_number"])
            ->where([
                ["family_name", "=", $posted_data["family_name"]],
                ["given_name", "=", $posted_data["given_name"]],
                ["phone_number", "=", $posted_data["phone_number"]],
            ])->orWhere([
                ["family_name", "=", $posted_data["family_name"]],
                ["given_name", "=", $posted_data["given_name"]],
                ["email", "=", $posted_data["email"]],
            ])
            ->where("id", "!=", $unique_user_id)
            ->get()
            ->first();
            if ($temp !== NULL) {
                throw new \Exception("指定したユーザー情報が既に存在します。");
            }
            // ユーザー情報のアップデート処理
            $result = $unique_user->fill($posted_data)->save();

            if ($result) {
                return redirect()->action("Admin\UserController@detail", [
                    "unique_user_id" => $unique_user_id,
                ]);
            } else {
                throw new \Exception("会員情報の更新処理に失敗しました。");
            }
        } catch (\Exception $e) {
            return view("errors.index", [
                "error" => $e,
            ]);
        }
    }

    /**
     * 指定したユーザー情報を物理削除する
     * 関連テーブル3つ全て削除が成功した場合のみcommitする
     *
     * @param UserRequest $request
     * @param Response $response
     * @param integer $unique_user_id
     * @return void
     */
    public function delete (UserRequest $request, Response $response, int $unique_user_id)
    {
        try {
            DB::beginTransaction();
            // POSTデータの取得
            $posted_data = $request->all();
            $unique_user_id = $posted_data["unique_user_id"];

            // (1)ユーザーのマスター情報を削除
            $result = UniqueUser::destroy($unique_user_id);
            if ($result !== 1) {
                throw new \Exception ("指定したユーザーのマスターデータの削除に失敗しました。");
            }
            // (2)ユーザの参加履歴を削除
            $attended_events = AttendedEvent::where("unique_user_id", $unique_user_id);
            $result = $attended_events->delete();
            if ($result === 0) {
                throw new \Exception ("指定したユーザーの参加履歴データの削除に失敗しました。");
            }
            // (3)CSVのログデータの削除
            $logs = Log::where("unique_user_id", $unique_user_id);
            $result = $logs ->delete();
            if ($result === 0) {
                throw new \Exception ("指定したユーザーのログデータの削除に失敗しました。");
            }
            // コミット
            DB::commit();
            // 関連テーブルを全て削除成功したら、再度ユーザー一覧画面に遷移する
            return redirect()->action("Admin\UserController@index");
        } catch (\Exception $e) {
            DB::rollback();
            return view("errors.index", [
                "error" => $e,
            ]);
        }
    }
}
