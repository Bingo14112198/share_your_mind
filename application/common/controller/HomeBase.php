<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/4
 * Time: 14:32
 */

namespace app\common\controller;
use think\Controller;

class HomeBase extends Controller
{
    public function _initialize()
    {
        if(!session('user_name')){
           $this->redirect('index/login/login');
        }
    }
}