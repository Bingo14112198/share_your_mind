<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/31
 * Time: 16:29
 */

namespace app\admin\controller;

use think\Db;
use think\Session;
use app\common\controller\AdminBase;

class Article extends AdminBase
{

    public function showlist(){
        $result = Db::name('article')->where('role','admin')->select();
        $category = Db::name('category')->field('id,cate_name')->select();
        $this->assign([
            'articles' => $result,
            'cate'=>$category
        ]);
        return $this->fetch();
    }

    public function add(){
        $cates = Db::name('category')->select();
        if(request()->isPost()){
            $data = [
                'article_name' => input('post.article_name'),
                'author' => input('post.author'),
                'from' => input('post.from'),
                'user_name' => Session::get('admin'),
                'user_id' => Session::get('admin_id'),
                'des' => input('post.des'),
                'content' => input('post.content'),
                'cate_id' => input('post.cate_id'),
                'role' => 'admin',
                'time' => date('Y-m-d H:i:s',time())
            ];

            //查看是否推荐
            if(array_key_exists('up',input('post.')) && input('post.up') == 'on'){
                $data['home'] = 1;
            }else{
                $data['home'] = 0;
            }

            //查看是否启用
            if(array_key_exists('state',input('post.')) && input('post.state') == 'on'){
                $data['state'] = 1;
            }else{
                $data['state'] = 1;
            }

            //查看图片封面
            $file = request()->file('cover');
            //移动到public/cover
            $info = $file->move(ROOT_PATH . 'public' . DS . 'static/cover');
            //获取savename
            $saveName = $info->getSaveName();
            //拼接path
            $path = 'cover/' . $saveName;
            $data['cover'] = $path;

            //作者头像
            $head = request()->file('author_cover');
            $info_head = $head->move(ROOT_PATH . 'public' . DS . 'static/author_cover');
            $headSaveName = $info_head->getSaveName();
            $head_cover_path = 'author_cover/' . $headSaveName;
            $data['author_cover'] = $head_cover_path;

            $result = Db::name('article')->insert($data);
            if($result !== false){
                $this->success('添加文章成功','admin/article/showlist');
            }else{
                $this->error('添加文章失败','admin/article/showlist');
            }
        }

        $this->assign([
            'cates'=>$cates
        ]);
        return $this->fetch();
    }

    //禁用
    public function stop(){
        $id = input('id');
        //修改state
        $result = Db::name('article')->where('id',$id)->update(['state'=> 0]);
        if($result !== false){
            return ['state'=>1,'msg'=>'禁用文章成功'];
        }else{
            return ['state'=>0,'msg'=>'禁用文章失败'];
        }
    }

    //禁用
    public function start(){
        $id = input('id');
        //修改state
        $result = Db::name('article')->where('id',$id)->update(['state'=> 1]);
        if($result !== false){
            return ['state'=>1,'msg'=>'启用文章成功'];
        }else{
            return ['state'=>0,'msg'=>'启用文章失败'];
        }
    }

    //从首页撤销
    public function drawback(){
        $id = input('id');
        //修改state
        $result = Db::name('article')->where('id',$id)->update(['home'=> 0]);
        if($result !== false){
            return ['state'=>1,'msg'=>'撤销文章成功'];
        }else{
            return ['state'=>0,'msg'=>'撤销文章失败'];
        }
    }

    //推荐到首页
    public function home(){
        $id = input('id');
        //修改state
        $result = Db::name('article')->where('id',$id)->update(['home'=> 1]);
        if($result !== false){
            return ['state'=>1,'msg'=>'推送文章成功'];
        }else{
            return ['state'=>0,'msg'=>'推送文章失败'];
        }
    }

    //修改文章
    public function edit(){
        $cates = Db::name('category')->select();
        $id = input('id');
        $article = Db::name('article')->where('id',$id)->find();

        if(request()->isPost()){
            $data = [
                'article_name' => input('post.article_name'),
                'author' => input('post.author'),
                'from' => input('post.from'),
                'des' => input('post.des'),
                'cate_id' => input('post.cate_id'),
                'content' => input('post.content'),
                'time' => date('Y-m-d H:i:s',time())
            ];

            //查看是否推荐
            if(array_key_exists('up',input('post.')) && input('post.up') == 'on'){
                $data['home'] = 1;
            }else{
                $data['home'] = 0;
            }

            //查看是否启用
            if(array_key_exists('state',input('post.')) && input('post.state') == 'on'){
                $data['state'] = 1;
            }else{
                $data['state'] = 1;
            }

            //查看图片封面
            $file = request()->file('cover');
            if($file != null){
                //移动到public/cover
                $info = $file->move(ROOT_PATH . 'public' . DS . 'static/cover');
                //获取savename
                $saveName = $info->getSaveName();
                //拼接path
                $path = 'cover/' . $saveName;
                $data['cover'] = $path;
                //删除原来的封面
                $cover = Db::name('article')->where('id',$id)->field('cover')->find();
                @unlink(ROOT_PATH . 'public/static/' . $cover['cover']);
            }else{
                $data['cover'] = $article['cover'];
            }


            //作者头像
            $head = request()->file('author_cover');
            if($head != null){
                $info_head = $head->move(ROOT_PATH . 'public' . DS . 'static/author_cover');
                $headSaveName = $info_head->getSaveName();
                $head_cover_path = 'author_cover/' . $headSaveName;
                $data['author_cover'] = $head_cover_path;
                //删除原来的头像
                @unlink(ROOT_PATH . 'public/static/' . $article['author_cover']);
            }else{
                $data['author_cover'] = $article['author_cover'];
            }

            $result = Db::name('article')->where('id',$id)->update($data);
            if($result !== false){
                $this->success('编辑文章成功','admin/article/showlist');
            }else{
                $this->error('编辑文章失败','admin/article/showlist');
            }
        }

        $this->assign([
            'article'=>$article,
            'cates' => $cates
        ]);
        return $this->fetch();
    }



}