<?php

namespace app\index\model;

use think\Model;

/**
 * Created by PhpStorm.
 * User: Gilbert
 * Date: 17/2/22
 * Time: 下午7:44
 */
class Tags extends Model
{
    public function posts(){
        return $this->belongsToMany('Posts', 'posts_tags');
    }
}