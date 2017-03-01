<?php
namespace app\index\controller;

use app\index\model\Posts;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $posts = Posts::order('update_time', 'desc')->select();
        return view('/index',[
            'posts' => $posts,
        ]);
    }
    public function registe()
    {
        return view('/registe');
    }
    public function login()
    {
        return view('/login');
    }
}
