<?php
/**
 * Created by PhpStorm.
 * User: Gilbert
 * Date: 17/4/10
 * Time: 下午12:15
 */

namespace app\index\controller;


use app\index\model\Pics;
use think\Controller;

class Pic extends Controller
{
    /**
     * 前置操作，相当于认证中间件
     *
     * @var array
     */
    protected $beforeActionList = [
        'auth' =>  ['only'=>'delete,upload'],
    ];

    /**
     * 验证用户身份
     */
    public function auth()
    {
        if(session('user')===null){
            return $this->error('抱歉，您尚未登录，请登录后再尝试');
        }
    }

    /**
     * 验证游客身份
     */
    public function guest()
    {
        if(session('user')!==null){
            return $this->error('抱歉，您已登录，请注销后再尝试');
        }
    }

    public function index()
    {
        $pics = Pics::order('update_time', 'desc')->select();
        return view('/pics',[
            'userId' => null,
            'pics' => $pics,
        ]);
    }

    public function getByUser($id)
    {
        $pics = Pics::where('user_id', $id)->order('update_time', 'desc')->select();
        return view('/pics',[
            'userId' => $id,
            'pics' => $pics,
        ]);
    }

    public function delete($id)
    {
        if(Pics::destroy($id)){
            // return $this->redirect('/blog');
            return json([
                'status' => 'success',
                'message' => '图片删除成功',
            ]);
        } else {
            // return $this->error('删除失败，请联系管理员');
            return json([
                'status' => 'warning',
                'message' => '图片删除失败',
            ]);
        }
    }

    public function upload(){
        // 获取表单上传文件 例如上传了001.jpg
        $files = request()->file('images');
        /*$name = $files->getFilename();
        $ext = $files->getExtension(); //文件扩展名
        return $name.'.'.$ext;*/
        $fails = [];
        // 获取原文件信息所用索引
        $i = 0;
        foreach($files as $file){
            // 获取原文件名
            $name = $_FILES['images']['name'][$i];
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->validate(['ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'imgs');
            if($info){
                // 成功上传后 获取上传信息
                /*// 输出 jpg
                echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getFilename();*/
                $data = [
                    'name' => $name,
                    'path' => DS .'uploads'. DS .'imgs'. DS .$info->getSaveName(),
                    'user_id' => session('user.id'),
                ];
                $img = Pics::create($data);
                if(!$img->id){
                    $fails += $name.'存入数据库失败';
                }
            }else{
                // 上传失败获取错误信息
                $fails += $name.'上传至服务器失败';
            }
            $i ++;
        }
        if(count($fails) === 0){
            return json([
                'status' => 'success',
                'message' => '图片保存成功',
            ]);
        } else {
            return json([
                'status' => 'warning',
                'message' => $fails,
            ]);
        }
    }
}