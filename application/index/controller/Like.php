<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/6
 * Time: 10:19
 */

namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;

class Like extends Controller
{
    /**
     * @return array
     * state = 3 没有登录
     * 1 喜欢成功或已经喜欢
     * 2 喜欢失败
     */
    public function like(){
        $id = input('id');
        if(!session('user_name')){
            return ['state' => 3, 'mes' => '请先登录'];
        }else{
//            $like = Db::name('article')->where('id',$id)->field('like')->find();
            if(input('action') == 1){
//                $result = Db::name('article')->where('id',$id)->update(['like'=>$like['like'] + 1]);
                $add = $this->add($id);
                if($add == 4){
                    return ['state' => 1,'mes'=>'喜欢过啦'];
                }elseif ( $add == 1){
                    return ['state'=>1,'mes'=>'喜欢就好'];
                }else{
                    return ['state' => 2,'msg'=>'操作失败'];
                }
            }else{
               $remove = $this->remove($id);
                if($remove == 1){
                    return ['state'=>1,'mes'=>'不喜欢也没关系'];
                }else{
                    return ['state' => 2,'msg'=>'取消失败,不喜欢都不行'];
                }
            }
        }


    }

    /**
     * @param $article_id
     * @return int
     * 1 喜欢成功
     * 2 喜欢失败
     * 4 已经喜欢过了
     */
    public function add($article_id){
        $user_name = Session::get('user_name');
        $user_id = Session::get('user_id');
        $data = [
            'article_id'=>$article_id,
            'user_name' => $user_name,
            'user_id' => $user_id,
            'time' => date("Y-m-d H:i:s",time()),
            'state' => 1
        ];
        //查询是否已经收藏
        $para = Db::name('like')->where('article_id',$article_id)->where('user_id',$user_id)->where('state',1)->find();
        if($para == null){
            //将数据存入数据库

            $like = Db::name('article')->where('id',$article_id)->field('like')->find();
            $param = Db::name('article')->where('id',$article_id)->update(['like'=>$like['like'] + 1]);
            $result = Db::name('like')->insert($data);
            if($result && $param){
                return 1;
            }else{
                return 2;
            }
        }else{
            return 4;
        }

    }

    public function remove($article_id){
        $user_name = Session::get('user_name');
        $like = Db::name('article')->where('id',$article_id)->field('like')->find();
        $param = Db::name('article')->where('id',$article_id)->update(['like'=>$like['like'] - 1]);
        $result = Db::name('like')->where('article_id',$article_id)->where('user_name',$user_name)->delete();
        if($result && $param){
            return 1;
        }else{
            return 2;
        }
    }
}