<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Models\Log;
use App\Models\UniqueUser;
use App\Models\Event;
use App\Models\AttendedEvent;
use Illuminate\Support\Str;
class ImportController extends Controller
{





    /**
     * CSVファイルインポート画面
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function index(ImportRequest $request, Response $response)
    {
        return view("admin.import.index", []);
    }


    public function upload(ImportRequest $request, Response $response)
    {
        try {
            // トランザクションを実行
            DB::beginTransaction();
            $imported_data = [];
            $input = $request->all();
            // ファイルオブジェクトからイテレータを取得
            $uploaded_file = $input["csv_file"]->openFile();
            // アップロードされた、CSVファイルからヘッダーのみを取得する
            $csv_header_array = explode(",", mb_convert_encoding($uploaded_file->current(), "UTF-8", "CP932"));
            $regulation_header = config("const.csv_header");

            // インポートされたヘッダーの検証
            if (array_intersect($regulation_header, $csv_header_array) !== $regulation_header) {
                throw new \RuntimeException("CSVヘッダーが規定とは異なります 。");
            }

            $headers = [];
            foreach ($csv_header_array as $key => $value) {
                $column_key = array_keys($regulation_header, $value);
                if (count($column_key) === 1) {
                    $headers[$key] = $column_key[0];
                }
            }

            // イテレータをすすめる
            $uploaded_file->next();

            // (1)logsテーブルへのデータ登録
            // インポートされたCSVをlogsテーブルに登録する
            while($value = $uploaded_file->current()) {
                // イテレータをすすめる
                $uploaded_file->next();

                $value = explode(",", mb_convert_encoding($value, "UTF-8", "CP932"));
                if (array_intersect_key($headers, $value) !== $headers) {
                    continue;
                }
                $temp = [];
                foreach ($headers as $_key => $_value) {
                    $temp[$_value] = $value[$_key];
                }
                // CSVファイルのinsert時に重複を防ぐ
                $result = Log::where("reception_number", $temp["reception_number"])->get();
                // 既にinsert済みであればスルーする
                if ($result->count() !== 0) {
                    continue;
                }
                $result = Log::insert($temp);
                $imported_data["logs"][] = join("｜", $temp);
            }


            // (2)unique_usersテーブルへのデータ登録
            // 全ユーザー情報から未登録のユーザーをユニークユーザーとして登録する
            $logs = Log::where("is_registered", 0)->get();
            foreach ($logs as $key => $value) {

                // ユニークユーザーの重複チェック
                $inner_response = UniqueUser::where([
                    ["family_name", "=", $value->family_name],
                    ["given_name", "=", $value->given_name],
                    ["phone_number", "=", $value->phone_number],
                ])->orWhere([
                    ["family_name", "=", $value->family_name],
                    ["given_name", "=", $value->given_name],
                    ["email", "=", $value->email],
                ])->get();


                // 戻り値チェック
                if ($inner_response->count() === 0) {
                    // 未登録のユニークユーザーのみ
                    // ユニークユーザーのマスタデータの追加を行う
                    $create_data = [
                        "family_name" => $value->family_name,
                        "given_name" => $value->given_name,
                        "family_name_sort" => $value->family_name_sort,
                        "given_name_sort" => $value->given_name_sort,
                        "phone_number" => $value->phone_number,
                        "email" => $value->email,
                        "job" => $value->job,
                        "gender" => $value->gender,
                        "age" => $value->age,
                        "reception_number" => $value->reception_number,
                    ];
                    $temp = UniqueUser::create($create_data);
                    // 挿入直後のプライマリキー
                    $unique_user_id = $temp->id;
                    // 戻り値チェック
                    if ($temp === NULL) {
                        throw new \Exception("新規ユニークユーザーの登録に失敗しました。");
                    }
                    $imported_data["unique_users"][] = join("｜", $create_data);
                } else {
                    $unique_user_id = $inner_response->first()->id;
                }
                // Logsテーブルのunique_user_idカラムを更新する
                $value->update(["unique_user_id" => $unique_user_id]);
            }

            // (3)eventsテーブルへのデータ登録
            // ユニークなイベント一覧をDBに登録する
            foreach ($logs as $key => $value) {
                $result = Event::where("event_start", $value->event_start)->get();
                // 重複登録を除外する
                if ($result->count() !== 0) {
                    continue;
                }
                // 未登録のイベントのみ追加する
                $create_data = [
                    "event_name" => $value->event_name,
                    "event_start" => $value->event_start,
                ];

                $temp = Event::create($create_data);
                // 戻り値チェック
                if ($temp === NULL) {
                    throw new \Exception("新規イベントマスターの登録に失敗しました。");
                }
                $imported_data["events"][] = join("｜", $create_data);
            }

            // (4)attended_eventsテーブルへのデータ登録
            // イベント参加履歴テーブルを更新する
            // foreach ($logs as $key => $value) {
            //     print_r($value->toArray());
            //     exit();
            $result = Event::with([
                "logs"
            ])->whereHas("logs", function ($query) use ($value) {
                $query->where("is_registered", 0);
            })->get();
            // print_r($result->toArray());
            // print_r($result->toArray());
            foreach ($result as $event_key => $event_value) {
                foreach ($event_value->logs as $log_key => $log_value) {
                    // print_r($event_value->toArray());
                    $log_data = [
                        "event_id" => $event_value->id,
                        "unique_user_id" => $log_value->unique_user_id,
                    ];

                    $temp = AttendedEvent::where($log_data)->get();
                    if ($temp->count() === 0) {
                        AttendedEvent::create($log_data);
                        $imported_data["attended_events"][] = join("｜", $log_data);
                    }
                }
            }
            DB::commit();
            return view("admin.import.upload", [
                "imported_data" => $imported_data
            ]);
            // ファイルアップロード画面へリダイレクト
            // return redirect()->action("Admin\ImportController@index");
        } catch(\RuntimeException $e) {
            // RuntimeExceptionはDBロジックの外で実行させる
            // エラーページを表示させる
            return view("error.index", [
                "error" => $e,
            ]);
        } catch(\Exception $e) {
            DB::rollback();
            // エラーページを表示させる
            return view("error.index", [
                "error" => $e,
            ]);
        }
    }
}
