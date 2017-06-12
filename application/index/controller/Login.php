<?php

namespace app\index\controller;
use think\Config;
use think\Controller;
use think\Db;
use think\Loader;
use app\index\model\Login as LoginModel;
use think\Session;

class Login extends Controller
{
    public function login(){
        if(request()->isPost()){
            if(input('user_name') == ''){
                return ['state'=> 2,'mes'=>'用户名不能为空'];
            }else{
                $data['user_name'] = input('user_name');
            }

            if(input('password') == ''){
                return ['state'=> 2,'mes'=>'密码不能为空'];
            }else{
                $data['password'] = md5(trim(input('password')) . Config::get('salt'));
            }

            $user = new LoginModel();
            $result = $user->log($data);
            if($result == 1){
                return ['state' => 1,'mes' => '登录成功'];
            }elseif ($result == 2){
                return ['state' => 2,'mes' => '用户名或密码错误'];
            }else{
                return ['state' => 3,'mes' => '您的账户被禁用'];
            }
        }
        return $this->fetch();
    }

    public function register(){
        if(request()->isPost()){
            $data = [
                'time' => date('Y-m-d H:i:s',time()),
                'head' => 'author_cover/base_author_cover.png',
                'allow_comment' =>1,
                'state' => 1
            ];
            if(input('user_name') == ''){
                return ['state'=> 2,'mes'=>'用户名不能为空'];
            }else{
                $data['user_name'] = input('user_name');
            }

            if(input('password') == ''){
                return ['state'=> 2,'mes'=>'密码不能为空'];
            }else{
                $data['password'] = md5(trim(input('password')) . Config::get('salt'));
            }

            $validate = Loader::validate('Login');
            if(!$validate->scene('register')->check($data)){
                return ['state'=> 2,'mes'=>$validate->getError()];
            }else{
                $model = new LoginModel();
                $result = $model->reg($data);
                if($result == 1){
                    return ['state'=> 1,'mes'=>'注册成功'];
                }else{
                    return ['state'=> 2,'mes'=>'注册失败'];
                }
            }
        }

    }

    public function logout(){
        Session::clear();
        $this->redirect('index/login/login');

    }
}