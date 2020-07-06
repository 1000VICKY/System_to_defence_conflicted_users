<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     * CSVファイルのバリデーション処理を実行
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $method = $this->getMethod();

        // リクエスト時のHTTPSメソッドでルールを分ける
        switch($method) {

            case "GET":
                // GET
            break;

            case "POST":
                // POST
                $rules = [
                    "csv_file" => [
                        "required",
                        "file",
                        "mimes:csv,txt",
                    ]
                ];
            break;
        }
        return $rules;
    }


    public function messages()
    {
        return [
            "csv_file.required" => ":attributeは必須項目です。",
            "csv_file.file" => ":attributeは必須項目です。",
            "csv_file.mimes" => ":attributeはCSVのファイル形式である必要があります。"
        ];
    }


    public function attributes ()
    {
        return [
            "csv_file" => "CSVファイル"
        ];
    }

    public function validationData()
    {
        return array_merge($this->all(), $this->route()->parameters());
    }
}
