<?php

namespace app\index\validate;

use think\Validate;
/**
 * Created by PhpStorm.
 * User: Gilbert
 * Date: 17/2/22
 * Time: 下午10:07
 */
class User extends Validate
{
    /**
     * @var array
     */
    protected $rule = [
        'email|电子邮箱' =>  'require|email|unique:users',
        'name|昵称'  =>  'require|max:25|unique:users',
        'pwd|密码' => 'require|min:6|confirm',
    ];

}
