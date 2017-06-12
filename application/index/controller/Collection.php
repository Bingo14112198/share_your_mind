<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/4
 * Time: 14:29
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Session;

class Collection extends Controller
{
    public function index()
    {
        if(request()->isPost()){
            $id = input('id');
            $user_name = Session::get('user_name');
            if(!session('user_name')){
                return ['state' => 3, 'mes' => '请先登录'];
            }else{
                $param = Db::name('collection')->where('article_id',$id)->where('user_name',$user_name)->find();
                if($param == null){
                    $data = [
                        'article_id' => $id,
                        'user_name' => $user_name,
                        'user_id' => Session::get('user_id'),
                        'state' => 1,
                        'time' => date('Y-m-d H:i:s',time())
                    ];
                    //查询是否收藏过了
                    $result = Db::name('collection')->insert($data);
                    if($result !== false){
                        return ['state' => 1, 'mes' => '谢谢收藏'];
                    }else{
                        return ['state' => 2, 'mes' => '收藏失败'];
                    }
                }else{
                    return ['state'=>1,'mes'=>'已经收藏过啦！！'];
                }

            }
        }

    }
}