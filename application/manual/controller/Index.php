<?php

namespace app\manual\controller;


class Index extends \think\Controller
{
    public function index()
    {
        $url = 'http://dqu.com';
        $this->assign('url',$url);
        return view('index');
    }
}
