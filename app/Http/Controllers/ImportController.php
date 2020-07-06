<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\ImportRequest;
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
            if (array_intersect($regulation_header, $csv_header_array) !== $regulation_header) {
                print("CSVヘッダーが規定とは異なります 。");
                exit();
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
            foreach ($uploaded_file as $key => $value) {
                $value = explode(",", mb_convert_encoding($value, "UTF-8", "CP932"));
                if (array_intersect_key($header, $value) !== $header) {
                    continue;
                }
                $temp = [];
                foreach ($header as $_key => $_value) {
                    $temp[$_value] = $value[$_key];
                }
                $imported_data_list[] = $temp;
            }
            print_r($imported_data_list);
        } catch(\Exception $e) {
            var_dump($e->getMessage());
            var_dump($e->getLine());
        }
    }
}
