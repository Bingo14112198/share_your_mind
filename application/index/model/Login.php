<?php

namespace app\index\model;
use think\Db;
use think\Model;
use think\Session;

class Login extends Model
{
    //登录
    public function log($data){
        $result = Db::name('user')->where('user_name',$data['user_name'])->find();

        if($result['state'] == 0){
            return 3;
        }
        if($result && $result['password'] == $data['password']){
            //更新登录次数和时间
            Db::name('user')->where('id',$result['id'])->setInc('count');
            Db::name('user')->where('id',$result['id'])->update(['time' => date('Y-m-d H:i:s')]);
            Session::set('user_name',$result['user_name']);
            Session::set('user_id',$result['id']);
            Session::set('user_head',$result['head']);
            //查看用户的喜欢,关注，收藏
            //查看用户的喜欢
            $likes =Db::name('like')->where('user_id',$result['id'])->field('article_id')->select();
            $collect = Db::name('collection')->where('user_id',$result['id'])->field('article_id')->select();
            $focus = Db::name('focus')->where('user_id',$result['id'])->field('target_id')->select();
            $like_array = [];
            foreach ($likes as $k=>$v){
                $like_array[] = $v['article_id'];
            }
            $collect_array = [];
            foreach ($collect as $k=>$v){
                $collect_array[] = $v['article_id'];
            }
            $focus_array = [];
            foreach ($focus as $k=>$v){
                $focus_array[] = $v['target_id'];
            }

            Session::set('like_array',$like_array);
            Session::set('collect_array',$collect_array);
            Session::set('focus_array',$focus_array);

            return 1;
        }else{
            return 2;
        }
    }
    //注册
    public function reg($data){
        $result = Db::name('user')->insert($data);
        if($result !== false){
            $user = Db::name('user')->where('user_name','=',$data['user_name'])->find();
            Session::set('user_name',$user['user_name']);
            Session::set('user_id',$user['id']);
            Session::set('user_head',$user['head']);
            return 1;
        }else{
            return 2;
        }
    }
}