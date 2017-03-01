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
    protected $readonly = ['name','email'];

    public function posts(){
        return $this->hasMany('Posts', 'user_id');
    }
}