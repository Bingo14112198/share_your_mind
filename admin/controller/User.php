<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/1
 * Time: 15:40
 */

namespace app\admin\controller;

use think\Db;
use think\Config;
use app\common\controller\AdminBase;

class User extends AdminBase
{
    public function userList(){
        $user = Db::name('user')->select();
        $this->assign([
            'user'=>$user
        ]);
        return $this->fetch();
    }

    //添加
    public function add(){
        if (request()->isPost()){
            $data = [
                'user_name'=>trim(input('user_name')),
                'password'=> md5(input('password') . Config::get('salt')),
                'time'=> date('Y-m-d H:i:s',time())
            ];

            if(input('state') == 'true'){
                $data['state'] = 1;
            }else{
                $data['state'] = 0;
            }

            //插入数据库
            $result = Db::name('user')->insert($data);
            if($result !== false){
                return ['state'=>1,'msg'=>'添加用户成功'];
            }else{
                return ['state'=>0,'msg'=>'添加用户失败'];
            }
        }
        return $this->fetch();
    }


    //删除
    public function del(){
        if(request()->isPost()){
            $id = input('id');
            $result = Db::name('user')->where('id',$id)->delete();
            if($result !== false){
                return ['state'=>1,'msg'=>'删除用户成功'];
            }else{
                return ['state'=>0,'msg'=>'删除用户失败'];
            }
        }
    }

    //禁用
    public function stop(){
        $id = input('id');
        $result = Db::name('user')->where('id',$id)->update(['state'=>0]);
        if($result !== false){
            return ['state'=>1,'msg'=>'禁用成功'];
        }else{
            return ['state'=>0,'msg'=>'禁用失败'];
        }
    }

    //启用
    public function start(){
        $id = input('id');
        $result = Db::name('user')->where('id',$id)->update(['state'=>1]);
        if($result !== false){
            return ['state'=>1,'msg'=>'启用成功'];
        }else{
            return ['state'=>0,'msg'=>'启用失败'];
        }
    }

    //禁言
    public function disable(){
        $id = input('id');
        $result = Db::name('user')->where('id',$id)->update(['allow_comment'=>0]);
        if($result){
            return ['state'=>1,'msg'=>'禁用成功'];
        }else{
            return ['state'=>0,'msg'=>'禁用失败'];
        }
    }

    //允许评论
    public function enable(){
        $id = input('id');
        $result = Db::name('user')->where('id',$id)->update(['allow_comment'=>1]);
        if($result){
            return ['state'=>1,'msg'=>'启用成功'];
        }else{
            return ['state'=>0,'msg'=>'启用失败'];
        }
    }



}