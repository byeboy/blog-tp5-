<?php

namespace app\index\model;

use think\Model;

/**
 * Created by PhpStorm.
 * User: Gilbert
 * Date: 17/2/22
 * Time: 下午7:44
 */
class Pics extends Model
{
    /**
     * @var array
     */

    public function user(){
        return $this->belongsTo('Users', 'user_id');
    }

}