<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/27
 * Time: 14:48
 */

namespace app\admin\controller;

use think\Db;
use think\Session;
use app\common\controller\AdminBase;

class Index extends AdminBase
{
    public function index(){
        $domain = request()->domain();
        $ip = request()->ip();
        //获取登录的用户
        $id = Session::get('admin_id');
        $now = Db::name('admin')->where('id',$id)->find();
        $version = Db::query('SELECT VERSION() AS ver');
        $config = [
            'url' => $_SERVER['HTTP_HOST'],
            'document_root' => $_SERVER['DOCUMENT_ROOT'],
            'server_os' => PHP_OS,
            'server_port' => $_SERVER['SERVER_PORT'],
            'server_soft' => $_SERVER['SERVER_SOFTWARE'],
            'php_version' => PHP_VERSION,
            'mysql_version' => $version[0]['ver'],
            'max_upload_size' => ini_get('upload_max_filesize')
        ];

        $this->assign([
            'domain' => $domain,
            'ip' => $ip,
            'config' => $config,
            'now' => $now
        ]);
        return $this->fetch();
    }
}