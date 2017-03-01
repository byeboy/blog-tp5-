<?php

namespace app\index\model;

use think\Model;

/**
 * Created by PhpStorm.
 * User: Gilbert
 * Date: 17/2/22
 * Time: 下午7:44
 */
class Posts extends Model
{
    public function user(){
        return $this->belongsTo('Users', 'user_id');
    }
    public function tags(){
        return $this->belongsToMany('Tags');
    }
}