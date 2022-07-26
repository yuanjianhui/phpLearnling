<?php
namespace App\Http\Controllers\Admin;

use myframe\Controller;
use myframe\Response;
use myframe\HttpException;

class CommonController extends Controller
{
    //不需要验证登录态的方法名，由各个子类配置
    protected $checkLoginExclude = [];

    /**
     * 验证登录态，父类Controller构造方法中自动调用
     * @throws \myframe\HttpException
     */
    protected function initialize()
    {
        //初始化会话信息
        session_start();
        if (!isset($_SESSION['cms'])) {
            $_SESSION = ['cms' => []];
        }
        //获取当前请求的方法名
        $action = $this->request->action();
        //判断当前请求是否需要验证登录态
        if (in_array($action, $this->checkLoginExclude)) {
            return;
        }
        //未登录则重定向到登录页面
        if (empty($_SESSION['cms']['admin'])) {
            $this->redirect('/admin/login/index');
        } else { //登录则获取用户信息，并分配到模板变量中
            $user = $_SESSION['cms']['admin'];
            $this->assign('user', $user);
        }

        //判断是否为Ajax请求
        if (!$this->request->isAjax()) {
            //返回布局文件
            $data = $this->fetch('admin/layout');
            throw new HttpException(Response::create($data));
        }
    }
}
