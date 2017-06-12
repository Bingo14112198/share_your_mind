<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8
 * Time: 15:45
 */

namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;

class Focus extends Controller
{
    public function add(){
        if(!session('user_name')){
            return ['state'=>4,'mes'=>'请先登录'];
        }
        if(request()->isPost()){
            $author_name = input('author');
            //先查看数据库

            $result = Db::name('focus')->where('target_name',$author_name)->select();

            if($result == null){
                $likes = Db::name('article')->where('author',$author_name)->field('like')->select();
                $like = 0;
                foreach ($likes as $k=>$v){
                    $like = $like + $v['like'];
                }

                //查看文章数
                $article_num = Db::name('article')->where('author',$author_name)->count();
                //查看用户头像
                $author = Db::name('user')->where('user_name',$author_name)->find();
                //查看被关注数
                $focus_num = Db::name('focus')->where('target_name',$author_name)->count();
                //将数据存入数据库
                $data = [
                    'user_name'=>Session::get('user_name'),
                    'user_id' => Session::get('user_id'),
                    'target_name'=>$author_name,
                    'target_head' =>$author['head'],
                    'target_id' => $author['id'],
                    'target_article'=>$article_num,
                    'target_like' => $like,
                    'target_focus' => $focus_num,
                    'state' => 1,
                    'time' => date('Y-m-d H:i:s',time())
                ];

                $info = Db::name('focus')->insert($data);
                if($info){
                    return ['state'=>1,'mes'=>'关注成功'];
                }else{
                    return ['state'=>2,'mes'=>'关注失败'];
                }
            }else{
                return ['state'=>3,'mes'=>'已经关注过了'];
            }

        }
    }

}