<?php

namespace app\index\model;

use think\Model;

/**
 * Created by PhpStorm.
 * User: Gilbert
 * Date: 17/2/22
 * Time: 下午7:44
 */
class Musics extends Model
{
    /**
     * @var array
     */

    public function users(){
        return $this->hasMany('Users', 'user_id');
    }

    
}