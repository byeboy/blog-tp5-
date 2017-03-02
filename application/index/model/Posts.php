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
    /**
     * @return \think\model\relation\BelongsTo
     */
    public function user(){
        return $this->belongsTo('Users', 'user_id');
    }

    /**
     * @return \think\model\relation\BelongsToMany
     */
    public function tags(){
        return $this->belongsToMany('Tags');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function comments(){
        return $this->hasMany('Comments', 'post_id');
    }
}