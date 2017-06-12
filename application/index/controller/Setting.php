<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/6
 * Time: 14:45
 */

namespace app\index\controller;
use app\common\controller\HomeBase;
use think\Config;
use think\Db;
use think\Session;
use think\Loader;

class Setting extends HomeBase
{
    public function index(){
        //查询个人信息
        $user = Db::name('user')->where('id',Session::get('user_id'))->field('user_name,phone,email')->find();
        $cates = Db::name('category')->select();
        $this->assign([
            'user'=>$user,
            'cates'=>$cates
        ]);
        return $this->fetch();
    }

    public function head(){
        if(request()->isPost()){
            //查看用户名
            $user_id = Session::get('user_id');
            //获取之前的头像
            $old_head = Db::name('user')->where('id',$user_id)->field('head')->find();

            $file = request()->file('head');
            $head_move = $file->move(ROOT_PATH . 'public' . DS . 'static/author_cover');
            $user_head_saveName = $head_move->getSaveName();
            $user_head_path = 'author_cover/' . $user_head_saveName;


            $result = Db::name('user')->where('id',$user_id)->update(['head'=>$user_head_path]);
            //更新文章作者头像
            Db::name('article')->where('user_id',$user_id)->update(['author_cover'=>$user_head_path]);
            //更新评论
            Db::name('comment')->where('user_id',$user_id)->update(['user_head'=>$user_head_path]);
            //更新关注
            Db::name('focus')->where('target_id',$user_id)->update(['target_head'=>$user_head_path]);

            if($result){
                //删除旧封面
                @unlink(ROOT_PATH . 'public/static/' . $old_head['head']);
                $head_img = Db::name('user')->where('id',$user_id)->field('head')->find();
                Session::set('user_head',$head_img['head']);
                $this->redirect('index/setting/index');
            }else{
                $this->redirect('index/setting/index');
            }
        }
    }

    //修改个人信息
    public function edit(){
        $user_id = Session::get('user_id');
        if(request()->isPost()){
            $data = [
                'user_name'=>input('user_name'),
                'phone'=>input('phone'),
                'email'=>input('email')
            ];

            //更新数据库
            $validate = Loader::validate('Login');
            if(!$validate->scene('register')->check($data)) {
                return ['state' => 2, 'mes' => $validate->getError()];
            }else{
                $result = Db::name('user')->where('id',$user_id)->update($data);
                $user = Db::name('user')->where('id',$user_id)->field('user_name,id,head')->find();
                Session::set('user_name',$user['user_name']);
                Session::set('user_id',$user['id']);
                Session::set('user_head',$user['head']);
                //更新文章
                Db::name('article')->where('user_id',$user_id)->update(['user_name'=>$user['user_name'],'author'=>$user['user_name']]);
                //更新评论
                Db::name('comment')->where('user_id',$user_id)->update(['user_name'=>$user['user_name']]);
                //更新收藏
                Db::name('collection')->where('user_id',$user_id)->update(['user_name'=>$user['user_name']]);
                //更新喜欢
                Db::name('like')->where('user_id',$user_id)->update(['user_name'=>$user['user_name']]);
                //更新关注
                Db::name('focus')->where('user_id',$user_id)->update(['user_name'=>$user['user_name']]);
                if($result){
                    return ['state'=>1,'mes'=>'修改个人信息成功'];
                }else{
                    return ['state'=>2,'mes'=>'修改个人信息失败'];
                }
            }

        }
    }

    //修改密码
    public function change(){
        if(request()->isPost()){
            $user_id = Session::get('user_id');
            //查询数据库

            $user = Db::name('user')->where('id',$user_id)->field('password')->find();
            $old = trim(input('old'));
            $new = trim(input('new'));
            if($old == '' || $new == ''){
                return ['state'=>3,'mes'=>'输入内容不能为空'];
            }else{
                $old_password = md5($old . Config::get('salt'));
                if($user['password'] == $old_password){
                    //更新密码
                    $new_password = md5($new .Config::get('salt'));
                    //更新数据库
                    $result = Db::name('user')->where('id',$user_id)->update(['password'=>$new_password]);
                    if($result){
                        return ['state'=>1,'mes'=>'修改密码成功，请重新登录'];
                    }else{
                        return ['state'=>2,'mes'=>'修改密码失败'];
                    }
                }
            }

        }

    }

    //修改提供的图片
    public function changeico(){
        $user_id = Session::get('user_id');
        if(request()->isPost()){
            $id = input('id');
            //拼接path
            $path = 'icon/' . $id .'.png';
            //更新数据库

            $result = Db::name('user')->where('id',$user_id)->update(['head'=>$path]);
            //更新文章作者头像
            Db::name('article')->where('user_id',$user_id)->update(['author_cover'=>$path]);
            //更新评论
            Db::name('comment')->where('user_id',$user_id)->update(['user_head'=>$path]);
            //更新关注
            Db::name('focus')->where('target_id',$user_id)->update(['target_head'=>$path]);
            if($result){
                Session::set('user_head',$path);
                return ['state'=>1,'mes'=>'修改头像成功'];
            }else{
                return ['state'=>2,'mes'=>'修改头像失败'];
            }
        }
    }

    //删除账号
    public function delete(){
        //获取user_id
        $id = Session::get('user_id');
        $result = Db::name('user')->where('id',$id)->update(['state'=>0]);
        if($result){
            Session::clear();
            return ['state'=>1,'mes'=>'删除账号,再见！'];
        }else{
            return ['state'=>2,'mes'=>'删除账号失败'];
        }
    }

}