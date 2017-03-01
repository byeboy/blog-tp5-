<?php

use think\Route;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//return [
//    '__pattern__' => [
//        'name' => '\w+',
//    ],
//    '[hello]'     => [
//        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        ':name' => ['index/hello', ['method' => 'post']],
//    ],
//
//];

Route::get('/', '/index/index');
Route::rule('/registe','user/registe');
Route::rule('/login','user/login');
Route::rule('/profile','user/profile');
Route::rule('/logout','user/logout');

Route::get('/blog/[:id]', 'user/posts');
Route::get('/post/:id', 'post/get');
Route::rule('/edit/post/[:id]', 'post/edit');
Route::rule('/del/post/:id', 'post/delete');

Route::get('/tag/:id', 'tag/getPosts');