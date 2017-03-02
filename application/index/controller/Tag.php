<?php
/**
 * Created by PhpStorm.
 * User: Gilbert
 * Date: 17/2/22
 * Time: 下午7:47
 */

namespace app\index\controller;


use app\index\model\Posts;
use app\index\model\Tags;
use think\Controller;

class Tag extends Controller
{
    /**
     * Get a list of Tags
     *
     * @param $id
     * @return \think\response\View
     */
    public function getPosts($id)
    {
        $tag = Tags::get($id);
        $tags = Tags::all();
        return view('/tag',[
            'tag' => $tag,
            'tags' => $tags,
            'posts' => $tag->posts,
        ]);
    }
}