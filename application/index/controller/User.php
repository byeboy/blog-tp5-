<?php
/**
 * Created by PhpStorm.
 * User: Gilbert
 * Date: 17/2/22
 * Time: 下午7:48
 */

namespace app\index\controller;

use app\index\model\Posts;
use app\index\model\Tags;
use think\Controller;
use app\index\model\Users;
use think\Request;
use think\Validate;

class User extends Controller
{
    /**
     * 前置操作，认证作用
     *
     * @var array
     */
    protected $beforeActionList = [
        'auth' =>  ['only'=>'logout,profile,upload'],
        'guest'  =>  ['except'=>'posts,logout,profile,upload'],
    ];

    /**
     * 用户身份认证
     */
    public function auth()
    {
        if(session('user')===null){
            return $this->error('抱歉，您尚未登录，请登录后再尝试');
        }
    }

    /**
     * 游客身份认证
     */
    public function guest()
    {
        if(session('user')!==null){
            return $this->error('抱歉，您已登录，请注销后再尝试');
        }
    }

    /**
     * Registe
     *
     * @return \think\response\Json|\think\response\View
     */
    public function registe()
    {
        if (Request::instance()->isPost()){
            $captcha = input('captcha');
            if(!captcha_check($captcha)){
                //验证失败
                return json([
                    'status' => 'danger',
                    'message' => '验证码错误',
                ]);
            };
            $email = input('email');
            $name = input('name');
            $pwd = input('pwd');
            $pwd_confirm = input('pwd_confirm');
            $data = [
                'email' => $email,
                'name' => $name,
                'pwd' => $pwd,
                'pwd_confirm' => $pwd_confirm,
            ];
            $validate = validate('User');
            if(!$validate->check($data)){
                // dump($validate->getError());
                return json([
                    'status' => 'warning',
                    'message' => $validate->getError(),
                ]);
            } else {
                $user = Users::create([
                    'email' =>  $email,
                    'name'  =>  $name,
                    'pwd' => MD5($pwd),
                ]);
                if($user->id){
                    // return view('/login');
                    return json([
                        'status' => 'success',
                        'message' => '用户【'.$user->name.'】，注册成功',
                    ]);
                }
                // return $this->error('注册失败');
                return json([
                    'status' => 'warning',
                    'message' => '注册失败，请联系网站管理',
                ]);
            }
        }
        return view('/registe');
    }

    /**
     * Login
     *
     * @return \think\response\Json|\think\response\View
     */
    public function login()
    {
        if (Request::instance()->isPost()){
            $email = input('email');
            $user = Users::get(['email'=>$email]);
            $captcha = input('captcha');
            if(!captcha_check($captcha)){
                //验证失败
                return json([
                    'status' => 'danger',
                    'message' => '验证码错误',
                ]);
            };
            if($user === null){
                // return $this->error('该帐号未注册', '/registe');
                return json([
                    'status' => 'warning',
                    'message' => '该帐号未注册',
                ]);
            } else if($user->pwd == MD5(input('pwd'))){
                session('user', $user);
                // return $this->redirect('/');
                return json([
                    'status' => 'success',
                    'message' => '登录成功，欢迎回来',
                ]);
            }
            // return $this->error('登录失败，请检查账户信息');
            return json([
                'status' => 'warning',
                'message' => '登录失败，请检查账户信息',
            ]);
        }
        return view('/login');
    }

    /**
     * Logout
     *
     * @return \think\response\Json
     */
    public function logout()
    {
        session('user', null);
        if(session('user')===null) {
            // return $this->redirect('/');
            return json([
                'status' => 'success',
                'message' => '注销成功',
            ]);
        }
        // return $this->error('注销失败');
        return json([
            'status' => 'warning',
            'message' => '注销失败',
        ]);
    }

    /**
     * Get a list of Posts which belongs to User(Get Blog)
     *
     * @param null $id
     * @return \think\response\View|void
     */
    public function posts($id=null)
    {
        if($id === null) {
            if(session('user') === null){
                return $this->redirect('/');
            } else {
                $id = session('user.id');
            }
        }
        $user = Users::get($id);
        $posts = Posts::where('user_id', $id)->order('update_time', 'desc')->select();
        $tags = Tags::all();
        if($user !== null){
            return view('/blog', [
                'user' => $user,
                'posts' => $posts,
                'tags' => $tags,
            ]);
        } else {
            return $this->error('抱歉，您要访问的博客不存在');
        }
    }

    /**
     * Get a User
     *
     * @return \think\response\Json|\think\response\View
     */
    public function profile()
    {
        if (Request::instance()->isPost()){
            $id = session('user.id');
            $user = Users::get($id);
            $user->pwd = input('pwd');
            $data = [
                'pwd' => $user->pwd,
                'pwd_confirm' => input('pwd_confirm'),
            ];
            $rule = [
                ['pwd','require|min:6|confirm','密码不可为空|密码不得少于6位|确认密码与密码不相同'],
            ];
            $validate = new Validate($rule);
            if(!$validate->check($data)){
                // dump($validate->getError());
                return json([
                        'status' => 'warning',
                        'message' => $validate->getError(),
                    ]);
            } else {
                $user->pwd = MD5($user->pwd);
                if($user->save()){
                    return $this->logout();
                }
                // return $this->error('修改信息失败');
                return json([
                        'status' => 'warning',
                        'message' => '修改信息失败',
                    ]);
            }
        } else {
            return view('/profile',[
                'user' => session('user'),
            ]);
        }
    }

    /**
     * Upload a pic of User
     *
     * @return string|void
     */
    public function upload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'profiles');
        if($info){
            /*// 成功上传后 获取上传信息
            // 输出 文件类型
            echo $info->getExtension();
            // 输出 文件路径
            echo $info->getSaveName();
            // 输出 文件名
            echo $info->getFilename(); */
            $id = session('user.id');
            $user = Users::get($id);
            $user->pic = '/uploads/profiles/'.$info->getSaveName();
            if($user->save()){
                return '头像修改成功';
            }
            return $this->error('头像修改失败');
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
    }
}

}