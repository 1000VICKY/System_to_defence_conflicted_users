<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\UniqueUser;
class TopController extends Controller
{





    /**
     * アプリケーションTOPページのレンダリング
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function index(Request $request, Response $response)
    {
        try {
            return view("index", [
            ]);
        } catch (\Exception $e) {
            return view("errors.index", [
                "error" => $e,
            ]);
        }
    }
}
