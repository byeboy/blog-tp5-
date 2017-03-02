<?php
namespace app\index\controller;

use app\index\model\Comments;
use think\Controller;

class Comment extends Controller
{
    /**
     * Create a Comment
     *
     * @return \think\response\Json
     */
    public function create(){
        $comment = new Comments;
        $comment->content = input('content');
        $comment->post_id = input('post_id');
        $comment->user_id = session('user.id');
//        $comment->user_id = input('user_id');
        if($comment->save()){
            return json([
                'status' => 'success',
                'message' => '评论成功',
            ]);
        }
        return json([
            'status' => 'warning',
            'message' => '评论失败',
        ]);
    }
}
