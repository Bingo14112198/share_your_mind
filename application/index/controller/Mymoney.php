<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8
 * Time: 12:32
 */

namespace app\index\controller;
use app\common\controller\HomeBase;
use think\Db;
use think\Session;

class Mymoney extends HomeBase
{
    public function index(){
        $cates = Db::name('category')->select();
        $user_id = Session::get('user_id');
        //查看支付宝
        $pay = Db::name('user')->where('id',$user_id)->field('allipay,weixin,money')->find();
        $this->assign([
            'cates'=>$cates,
            'pay' =>$pay
        ]);
     return $this->fetch();
    }

    //我的赏金
    public function mymoney(){
        if(request()->isPost()){
            $money = input('money');
            $user_id = Session::get('user_id');
            $result = Db::name('user')->where('id',$user_id)->update(['money'=>$money]);
            if($result){
                $this->redirect('index/mymoney/index');
            }else{
                $this->redirect('index/mymoney/index');
            }
        }
    }

    public function allipay(){
        if(request()->isPost()){
            $user_id = Session::get('user_id');
            //查看是否有图片
            $allipay = Db::name('user')->where('id',$user_id)->field('allipay')->find();
            $file = request()->file('allipay');
            //将图片移动到public/static/allipay_pic
            $remove = $file->move(ROOT_PATH . 'public/' . DS .'static/allipay');
            $saveName = $remove->getSaveName();
            $path = 'allipay/' . $saveName;
            if($allipay['allipay'] != ''){
                //删除原来的支付宝
                @unlink(ROOT_PATH . 'public/static/' . $allipay['allipay']);
            }

            //将数据存入数据库
            $result = Db::name('user')->where('id',$user_id)->update(['allipay'=>$path]);
            if($result){
                $this->success('更换支付宝图片成功','index/mymoney/index');
            }else{
                $this->error('更换支付宝图片失败','index/mymoney/index');
            }


        }
    }

    public function weixin()
    {
        if(request()->isPost()){
            $user_id = Session::get('user_id');
            //查看是否有图片
            $weixinpay = Db::name('user')->where('id',$user_id)->field('weixin')->find();
            $file = request()->file('weixin');
            //将图片移动到public/static/weixinpay
            $remove = $file->move(ROOT_PATH . 'public/' . DS .'static/weixinpay');
            $saveName = $remove->getSaveName();
            $path = 'weixinpay/' . $saveName;
            if($weixinpay['weixin'] != ''){
                //删除原来的微信
                @unlink(ROOT_PATH . 'public/static/' . $weixinpay['weixin']);
            }

            //将数据存入数据库
            $result = Db::name('user')->where('id',$user_id)->update(['weixin'=>$path]);
            if($result){
                $this->success('更换微信支付图片成功','index/mymoney/index');
            }else{
                $this->error('更换微信支付图片失败','index/mymoney/index');
            }


        }
    }
}