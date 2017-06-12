<?php

namespace app\admin\controller;

use think\Db;
use app\common\controller\AdminBase;

class Category extends AdminBase
{
    public function index(){
        $result = Db::name('category')->select();
        $this->assign([
            'cates' => $result
        ]);
        return $this->fetch();
    }

    public function add(){
        if (request()->isPost()) {
            $data = [
                'cate_name' => input('cate_name'),
                'des' => input('des'),
                'time' => time()
            ];

            if (input('state') == 'true') {
                $data['state'] = 1;
            } else {
                $data['state'] = 0;
            }

            //插入数据库
            $result = Db::name('category')->insert($data);
            if ($result !== false) {
                return ['state' => 1, 'msg' => '添加类型成功'];
            } else {
                return ['state' => 0, 'msg' => '添加类型失败'];
            }
        }
        return $this->fetch();
    }

    //禁用
    public function stop(){
        $id = input('id');
        $result = Db::name('category')->where('id',$id)->update(['state'=>0]);
        if($result !== false){
            return ['state'=>1,'msg'=>'禁用成功'];
        }else{
            return ['state'=>0,'msg'=>'禁用失败'];
        }
    }

    //启用
    public function start(){
        $id = input('id');
        $result = Db::name('category')->where('id',$id)->update(['state'=>1]);
        if($result !== false){
            return ['state'=>1,'msg'=>'启用成功'];
        }else{
            return ['state'=>0,'msg'=>'启用失败'];
        }
    }

    //删除
    public function del(){
        if(request()->isPost()){
            $id = input('id');
            $result = Db::name('category')->where('id',$id)->delete();
            if($result !== false){
                return ['state'=>1,'msg'=>'删除类型成功'];
            }else{
                return ['state'=>0,'msg'=>'删除类型失败'];
            }
        }
    }

    //编辑
    public function edit(){
        $id = input('id');
        if(request()->isPost()){
            $data = [
                'cate_name' => input('cate_name'),
                'des' => input('des'),
                'time' => time()
            ];
            $cid = input('cid');
            if (input('state') == 'true') {
                $data['state'] = 1;
            } else {
                $data['state'] = 0;
            }

            //插入数据库
            $result = Db::name('category')->where('id',$cid)->update($data);
            if ($result) {
                return ['state' => 1, 'msg' => '修改类型成功'];
            } else {
                return ['state' => 0, 'msg' => '修改类型失败'];
            }
        }
        $cate = Db::name('category')->where('id',$id)->find();
        $this->assign([
            'cate'=>$cate
        ]);
        return $this->fetch();
    }

    //封面管理
    public function cover(){
        $id = input('id');
        $img = Db::name('category')->where('id',$id)->field('cover')->find();
        if(request()->isPost()){
            $file = request()->file('cover');
            //移动图片到public/static/cate_cover 下
            $move = $file->move(ROOT_PATH . 'public/' . DS . 'static/cate_cover');
            $saveName = $move->getSaveName();
            $path = 'cate_cover/' . $saveName;
            //删除原来的图片
            if($img['cover'] != ''){
                @unlink(ROOT_PATH . 'public/static/' . $img['cover']);
            }

            //更新数据库
            $result = Db::name('category')->where('id',$id)->update(['cover'=>$path]);
            if($result){
                $this->success('修改封面成功','admin/category/index');
            }else{
                $this->error('修改封面失败','admin/category/index');
            }
        }
        $this->assign([
            'id'=>$id,
            'cover'=>$img['cover']
        ]);
        return $this->fetch();
    }
}