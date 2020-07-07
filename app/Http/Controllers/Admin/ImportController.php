<?php

namespace App\Http\Controllers\Admin;

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
            $input = $request->all();
            // ファイルオブジェクトからイテレータを取得
            $uploaded_file = $input["csv_file"]->openFile();
            // アップロードされた、CSVファイルからヘッダーのみを取得する
            $csv_header_array = explode(",", mb_convert_encoding($uploaded_file->current(), "UTF-8", "CP932"));
            $regulation_header = config("const.csv_header");

            // インポートされたヘッダーの検証
            if (array_intersect($regulation_header, $csv_header_array) !== $regulation_header) {
                throw new \Exception("CSVヘッダーが規定とは異なります 。");
            }

            $header = [];
            foreach ($csv_header_array as $key => $value) {
                $column_key = array_keys($regulation_header, $value);
                if (count($column_key) === 1) {
                    $header[$key] = $column_key[0];
                }
            }
            // イテレータをすすめる
            $uploaded_file->next();
            $imported_data_list = [];

            // インポートされたCSVをall_end_usersテーブルに登録する
            while($value = $uploaded_file->current()) {
                // イテレータをすすめる
                $uploaded_file->next();
                $value = explode(",", mb_convert_encoding($value, "UTF-8", "CP932"));
                if (array_intersect_key($header, $value) !== $header) {
                    continue;
                }
                $temp = [];
                foreach ($header as $_key => $_value) {
                    $temp[$_value] = $value[$_key];
                }
                // CSVファイルのinsert時に重複を防ぐ
                $result = Log::where("reception_number", $temp["reception_number"])->get();
                // 既にinsert済みであればスルーする
                if ($result->count() !== 0) {
                    continue;
                }
                $result = Log::create($temp);
            }

            // 全ユーザー情報から未登録のユーザーをユニークユーザーとして登録する
            $result = Log::where("is_registered", 0)->get();
            foreach ($result as $key => $value) {

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
                    $temp = UniqueUser::create([
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
                    ]);
                    // 挿入直後のプライマリキー
                    $unique_user_id = $temp->id;
                    // 戻り値チェック
                    if ($temp === NULL) {
                        throw new \Exception("新規ユニークユーザーの登録に失敗しました。");
                    }
                } else {
                    $unique_user_id = $inner_response->first()->id;
                }
                // イベントの重複チェック
                $result = Event::where("event_start", $value->event_start)->get();
                    if ($result->count() === 0) {
                    // 未登録のイベントのみ追加する
                    $temp = Event::create([
                        "event_name" => $value->event_name,
                        "event_start" => $value->event_start,
                    ]);
                    // 戻り値チェック
                    if ($temp === NULL) {
                        throw new \Exception("新規イベントマスターの登録に失敗しました。");
                    }
                }

                // インポート済みのLogレコードは is_registered = 1 と更新する
                $_ = $value->update([
                    "unique_user_id" => $unique_user_id,
                ]);
            }

            // イベント参加履歴テーブルを更新する
            $event_update = [];
            $result = Event::with([
                "logs" => function ($query) {
                    $query->where("is_registered", 0);
                }
            ])->get();
            foreach ($result as $key => $value) {
                foreach($value->logs as $k => $v) {
                    $event_update[] = [
                        "event_id" => $value->id,
                        "unique_user_id" => $v->unique_user_id,
                    ];
                }
            }
            print_r($event_update);
            print_r(array_unique($event_update));
            $result = AttendedEvent::insert($event_update);
        } catch(\Exception $e) {
            // エラーページを表示させる
            return view("admin.error.index", [
                "error" => $e,
            ]);
        }
    }
}
