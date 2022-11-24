<?php

namespace app\controller;

use App\AbstractController;
use support\Request;

class Index extends AbstractController
{
    public function index(Request $request)
    {
        return view('index/index');
    }

    public function view(Request $request)
    {
        return view('index/view', ['name' => 'webman']);
    }

    public function json(Request $request)
    {
        return json(['code' => 0, 'msg' => 'ok']);
    }

}
