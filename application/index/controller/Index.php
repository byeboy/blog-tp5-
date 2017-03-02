<?php
namespace app\index\controller;

use app\index\model\Posts;
use think\Controller;

class Index extends Controller
{
    /**
     * Get a list of Posts
     *
     * @return \think\response\View
     */
    public function index()
    {
        $posts = Posts::order('update_time', 'desc')->select();
        return view('/index',[
            'posts' => $posts,
        ]);
    }
}
