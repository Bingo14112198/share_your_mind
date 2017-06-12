<?php


namespace app\index\validate;
use think\Validate;

class Login extends Validate
{
    protected $rule = [
        'user_name' => 'unique:user|max:10',
        'password' => 'require'
    ];

    protected $message = [
        'user_name.unique' => '用户名已经被注册',
        'user_name.max'    => '用户名不得超过15个字符',
        'password.require' => '密码不能为空'
    ];

    protected $scene = [
        'register' => ['user_name']
    ];
}