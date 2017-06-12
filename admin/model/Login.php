<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/27
 * Time: 13:10
 */

namespace app\admin\model;
use think\Db;
use think\Model;
use think\Session;

class Login extends Model
{
    public function check($data){
        //先检查验证码
        $captcha = new \think\captcha\Captcha();
        if(!$captcha->check($data['code'])){
            return 1;
        }else{
            //根据登录的用户查询数据库
            $result = Db::name('admin')->where('admin_name',$data['admin_name'])->find();
            //验证用户是否存在
            if($result && $result['password'] == $data['password'] && $result['state'] == 1){
                //存储管理员
                Session::set('admin',$result['admin_name']);
                Session::set('admin_id',$result['id']);
                Db::name('admin')->where('id',$result['id'])->setInc('count');
                //更新登录时间
                Db::name('admin')->where('id',$result['id'])->update(['time'=>time()]);
                return 2;
            }else{
                return 3;
            }
        }
    }

}