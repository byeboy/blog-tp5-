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
use think\Request;
use think\Response;

class Post extends Controller
{
    protected $beforeActionList = [
        'auth' =>  ['except'=>'get'],
    ];

    public function auth()
    {
        if(session('user')===null){
            return $this->error('抱歉，您尚未登录，请登录后再尝试');
        }
    }

    public function guest()
    {
        if(session('user')!==null){
            return $this->error('抱歉，您已登录，请注销后再尝试');
        }
    }

    public function get($id)
    {
        $post = Posts::get($id);
        $tags = Tags::all();
        return view('/post',[
            'post' => $post,
            'tags' => $tags,
        ]);
    }

    public function edit($id=null)
    {
        if(Request::instance()->isPost()){
            if($id===null) {
                $post = new Posts;
                $post->title = input('title');
                $post->intro = input('intro');
                $post->content = input('content');
                $post->user_id = session('user.id');
                // return json([
                //         'message' => $tags,
                //         'input' => input('tags'),
                //     ]);
                if($post->save()) {
                    if(input('tags')!==''&&input('tags')!==null){
                        $tags = explode(',',input('tags'));
                        Posts::get($post->id)->tags()->saveAll($tags);
                    }
                    return json([
                        'status' => 'success',
                        'post' => $post,
                        'message' => '文章发布成功',
                    ]);
                }
                return json([
                    'status' => 'warning',
                    'post' => $post,
                    'message' => '文章发布失败',
                ]);
            } else {
                $post = Posts::get($id);
                $post->title = input('title');
                $post->intro = input('intro');
                $post->content = input('content');
                if($post->save()){
                    $post->tags()->detach();
                    if(input('tags')!==''&&input('tags')!==null){
                        $tags = explode(',',input('tags'));
                        $post->tags()->attach($tags);
                    }
                    return json([
                        'status' => 'success',
                        'post' => $post,
                        'message' => '文章编辑成功',
                    ]);
                }
                return json([
                    'status'=> 'warning',
                    'post'=> $post,
                    'message' => '文章编辑失败',
                ]);
            }
        } else {
            $post = null;
            $tags = Tags::all();
            if($id !== null){
                $post = Posts::get($id);
            }
            return view('/postForm',[
                'post' => $post,
                'tags' => $tags,
            ]);
        }
    }

    public function delete($id)
    {
        if(Posts::destroy($id)){
            // return $this->redirect('/blog');
            return json([
                'status' => 'success',
                'message' => '文章删除成功',
            ]);
        } else {
            // return $this->error('删除失败，请联系管理员');
            return json([
                'status' => 'warning',
                'message' => '文章删除失败',
            ]);
        }
    }
}