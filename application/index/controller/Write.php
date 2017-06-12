<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/4
 * Time: 13:17
 */

namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;

class Write extends Controller
{
    public function index(){
        if(!session('user_name')){
            $this->redirect('index/login/login');
        }else{
            $cates = Db::name('category')->select();


            $this->assign([
                'cates'=>$cates,
            ]);
            return $this->fetch();
        }

    }

    public function add(){
        if(request()->isPost()){
            $user_id = Session::get('user_id');
            $data = [
                'article_name' => input('post.article_name'),
                'author' => Session::get('user_name'),
                'user_name' => Session::get('user_name'),
                'user_id' => Session::get('user_id'),
                'des' => input('post.des'),
                'cate_id' => 2,
                'content' => input('post.content'),
                'role' => 'user',
                'from' => '原创',
                'home' => 0,
                'state' => 0,
                'time' => date('Y-m-d H:i:s',time())

            ];

            //文章封面
            $cover = request()->file('cover');
            if($cover != null){
                $cover_move = $cover->move(ROOT_PATH . 'public' . DS . 'static/cover');
                $user_cover_saveName = $cover_move->getSaveName();
                $user_cover_path = 'cover/' . $user_cover_saveName;
                $data['cover'] = $user_cover_path;
            }else{
                $data['cover'] = 'cover/cover_base.jpg';
            }

            $user_session_head = Db::name('user')->where('id',$user_id)->field('head')->find();
            $data['author_cover'] = $user_session_head['head'];

            //将数据存入数据库
            $result = Db::name('article')->insert($data);
            if($result !== false){
                $this->success('添加文章成功','index/myhome/index');
            }else{
                $this->error('添加文章失败');
            }

        }
    }
}