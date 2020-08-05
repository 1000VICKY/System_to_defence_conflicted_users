<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UserRequest extends FormRequest
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
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $method = strtoupper($this->getMethod());
        $route_name = $this->route()->getName();
        if ($method === "POST") {
            if ($route_name === "admin.user.participated") {
                $rules = [
                    "is_participated" => [
                        "required",
                        "between:0,1",
                    ]
                ];
            } else if ($route_name === "admin.user.postCreate") {
                // 新規会員登録処理
                $rules = [
                    "family_name" => [
                        "required",
                        "between:1, 256",
                    ],
                    "given_name" => [
                        "required",
                        "between:1, 256",
                    ],
                    "family_name_sort" => [
                        "required",
                        "between:1, 256",
                    ],
                    "given_name_sort" => [
                        "required",
                        "between:1, 256",
                    ],
                    "age" => [
                        "required",
                        "integer",
                        "min:16",
                        "max:100",
                    ],
                    "phone_number" => [
                        "required",
                        "between:10,20",
                    ],
                    "email" => [
                        "required",
                        "email:rfc",
                    ],
                    "reception_number" => [
                        "required",
                        "min:9",
                        "max:15",
                    ],
                    "job" => [
                        // "required",
                        "between:0,128",
                    ],
                    "gender" => [
                        "required",
                    ]
                ];
            } else if ($route_name === "admin.user.postUpdate") {
                // print_r($this->validationData());
                // exit();
                // 既存ユーザーの情報変更処理
                $rules = [
                    "unique_user_id" => [
                        "required",
                        Rule::exists("unique_users", "id")
                    ],
                    "family_name" => [
                        "required",
                        "between:1, 256",
                    ],
                    "given_name" => [
                        "required",
                        "between:1, 256",
                    ],
                    "family_name_sort" => [
                        "required",
                        "between:1, 256",
                    ],
                    "given_name_sort" => [
                        "required",
                        "between:1, 256",
                    ],
                    "age" => [
                        "required",
                        "integer",
                        "min:16",
                        "max:100",
                    ],
                    "phone_number" => [
                        "required",
                        "between:10,20",
                    ],
                    "email" => [
                        "required",
                        "email:rfc",
                    ],
                    "reception_number" => [
                        "required",
                        "min:9",
                        "max:9",
                    ],
                    "job" => [
                        // "required",
                        "between:0,128",
                    ],
                    "gender" => [
                        "required",
                    ]
                ];
            } else if ($route_name === "admin.user.delete") {
                $rules = [
                    "unique_user_id" => [
                        "required",
                        Rule::exists("unique_users", "id")
                    ],
                ];
            }
        }
        return $rules;
    }

    public function messages()
    {
        return [
            "is_participated.required" => ":attributesは必須項目です。",
            "is_participated.between" => ":attributeは0か1である必要があります。",
            "family_name.required" => ":attributeは必須項目です。",
            "given_name.required" => ":attributeは必須項目です。",
            "family_name_sort.required" => ":attributeは必須項目です。",
            "given_name_sort.required" => ":attributeは必須項目です。",
            "family_name.required" => ":attributeは必須項目です。",
            "age.required" => ":attributeは必須項目です。",
            "phone_number.required" => ":attributeは必須項目です。",
            "email.required" => ":attributeは必須項目です。",
            "reception_number.required" => ":attributeは必須項目です。",
            "job.required" => ":attributeは必須項目です。",
            "gender.required" => ":attributeは必須項目です。",
        ];
    }

    public function attributes()
    {
        return [
            "is_participated" => "参加状態",
            // 新規会員登録用属性
            "family_name" => "氏名(名字)",
            "given_name" => "氏名(名前)",
            "family_name_sort" => "氏名カナ(名字)",
            "given_name_sort" => "氏名カナ(名前)",
            "age" => "年齢",
            "phone_number" => "電話番号",
            "email" => "メールアドレス",
            "reception_number" => "CSV番号",
            "job" => "職業",
            "gender" => "性別",
        ];
    }

    public function validationData()
    {
        return array_merge(
            $this->all(),
            $this->route()->parameters()
        );
    }
}
