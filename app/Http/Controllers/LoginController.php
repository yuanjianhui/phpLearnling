<?php
namespace App\Http\Controllers;

use myframe\Request;
use App\LoginModel;
class LoginController{
    //Request对象
    protected $request;
    public function __construct(Request $request)
    {
        //通过容器类获取Request对象并初始化成员属性
        $this->request = $request;
    }
    public  function  index(){
        $loginModel= new loginModel();
        $data= $loginModel->login();
        return $data;
    }
    public  function  upload(LoginModel $loginModel){
        $data = [
            'username' => $this->request->post('username', ''),
            'userPwd' => $this->request->post('userPwd', '')
            ];
        if(!$data){
            echo "用户名或者密码错误";
            die();
        }
        $_SESSION['user']=[
            'name'=>$data['username'],
            'id'=>$data['id']
        ];
        if($_SESSION['user']){
            header('Location: /story/index');
        }
    }
}