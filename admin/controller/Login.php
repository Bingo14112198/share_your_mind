<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/27
 * Time: 10:33
 */

namespace app\admin\controller;
use think\Config;
use think\Controller;
use app\admin\model\Login as LoginModel;
use think\Session;

class Login extends Controller
{
    public function index(){
        if(request()->isPost()){
            $data = [
                'admin_name'=> trim(input('post.admin')),
                'password' => md5(trim(input('post.password')) . Config::get('salt')),
                'code' => input('post.code')
            ];
            $user = new LoginModel();
            $result = $user->check($data);
            if($result == 1){
                $this->error('验证码错误','admin/login/index');
            }elseif ($result == 2){
                $this->redirect('admin/index/index');
            }else{
                $this->error('用户名或密码错误','admin/login/index');
            }
        }
        return $this->fetch();
    }

    //登出
    public function logout(){
        Session::clear();
        $this->redirect('admin/login/index');
    }
}