<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Models\AllEndUser;
use App\Models\UniqueUser;
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
                // 重複を防ぐため、ハッシュ化してユニークキーとする
                $hash = hash("sha256", json_encode($temp));
                $temp["hash"] = $hash;
                $temp["uuid"] = (string)Str::uuid();
                $result = AllEndUser::where("hash", $hash)->get();
                if ($result->count() !== 0) {
                    continue;
                }
                $result = AllEndUser::create($temp);
            }

            // 全ユーザー情報から未登録のユーザーをユニークユーザーとして登録する
            $result = AllEndUser::where("is_registered", 0)->get();
            foreach ($result as $key => $value) {
                $inner_response = UniqueUser::where([
                    ["family_name", "=", $value->family_name],
                    ["given_name", "=", $value->given_name],
                    ["phone_number", "=", $value->phone_number],
                ])->orWhere([
                    ["family_name", "=", $value->family_name],
                    ["given_name", "=", $value->given_name],
                    ["email", "=", $value->email],
                ])->get();
                if ($inner_response->count() !== 0) {
                    continue;
                }
                $temp = UniqueUser::create([

                ]);
            }
        } catch(\Exception $e) {
            // エラーページを表示させる
            return view("admin.error.index", [
                "error" => $e,
            ]);
        }
    }
}
