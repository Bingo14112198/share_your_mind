<?php


namespace app\common\controller;
use think\Controller;

class AdminBase extends Controller
{
    public function _initialize()
    {
        if(!session('admin')){
            $this->redirect('admin/login/index');
        }
    }
}