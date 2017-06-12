<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/31
 * Time: 10:31
 */

namespace app\admin\controller;

use think\Config;
use think\Db;
use app\common\controller\AdminBase;

class Admin extends AdminBase
{

    //管理员列表
    public function adminList(){
        //查询数据库
        $admins = Db::name('admin')->select();
        $this->assign([
            'admins'=>$admins
        ]);

        return $this->fetch();
    }

    //添加
    public function add(){
        if (request()->isPost()){
            $data = [
                'admin_name'=>trim(input('admin_name')),
                'password'=>  md5(trim(input('post.password')) . Config::get('salt')),
                'time'=> time()
            ];

            if(input('state') == 'true'){
                $data['state'] = 1;
            }else{
                $data['state'] = 0;
            }

            //插入数据库
            $result = Db::name('admin')->insert($data);
            if($result !== false){
                return ['state'=>1,'msg'=>'添加管理员成功'];
            }else{
                return ['state'=>0,'msg'=>'添加管理员失败'];
            }
        }
        return $this->fetch();
    }

    //删除
    public function del(){
        if(request()->isPost()){
            $id = input('id');
            if($id == 1){
                return ['state'=>0,'msg'=>'超级管理员不能操作'];
            }
            $result = Db::name('admin')->where('id',$id)->delete();
            if($result !== false){
                return ['sta`te'=>1,'msg'=>'删除管理员成功'];
            }else{
                return ['state'=>0,'msg'=>'删除管理员失败'];
            }
        }
    }

    //禁用
    public function stop(){
        $id = input('id');
        if($id == 1){
            return ['state'=>0,'msg'=>'超级管理员不能操作'];
        }
        $result = Db::name('admin')->where('id',$id)->update(['state'=>0]);
        if($result !== false){
            return ['state'=>1,'msg'=>'禁用成功'];
        }else{
            return ['state'=>0,'msg'=>'禁用失败'];
        }
    }

    //禁用
    public function start(){
        $id = input('id');
        if($id == 1){
            return ['state'=>0,'msg'=>'超级管理员不能操作'];
        }
        $result = Db::name('admin')->where('id',$id)->update(['state'=>1]);
        if($result !== false){
            return ['state'=>1,'msg'=>'启用成功'];
        }else{
            return ['state'=>0,'msg'=>'启用失败'];
        }
    }
}