<?php

namespace app\index\model;

use think\Model;

/**
 * Created by PhpStorm.
 * User: Gilbert
 * Date: 17/2/22
 * Time: 下午7:44
 */
class Users extends Model
{
    /**
     * @var array
     */
    protected $readonly = ['name','email'];

    public function bgms(){
        return $this->hasMany('Musics', 'user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function posts(){
        return $this->hasMany('Posts', 'user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function comments(){
        return $this->hasMany('Comments', 'user_id');
    }
}