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
            $historical_data = AttendedEvent::with([
                "events"
            ])->where("unique_user_id", $unique_user_id)->get();
            print_r($historical_data);
            return view("admin.user.detail", [
                "historical_data" => $historical_data,
            ]);
        } catch (\Exception $e) {
            // エラー画面
            return view("error.index", [
                "error" => $e,
            ]);
        }
    }
}
