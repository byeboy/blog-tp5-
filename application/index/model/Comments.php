<?php

namespace app\index\model;

use think\Model;

/**
 * Created by PhpStorm.
 * User: Gilbert
 * Date: 17/2/22
 * Time: 下午7:44
 */
class Comments extends Model
{
    /**
     * @return \think\model\relation\BelongsTo
     */
    public function post(){
        return $this->belongsTo('Posts', 'post_id');
    }

    public function comments(){
        return $this->hasMany('Comments', 'comment_id');
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function user(){
        return $this->belongsTo('Users', 'user_id');
    }
}