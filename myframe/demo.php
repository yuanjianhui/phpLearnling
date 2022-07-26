<?php
namespace  myframe;

use App\Http\Controllers\MemberController;
use ReflectionClass;
//获取类的有关信息；
$reflect = new ReflectionClass('\\App\\Http\\Controllers\\MemberController');
//获取类的构造函数；
$Constructor=$reflect->getConstructor();
//获取参数
$params = $Constructor->getParameters();
$args=[];
foreach ($params as $param){
    $class = $param->getClass();
    $className = $class->getName();
    $args[]=new $className();
}
$mebCon = $reflect->newInstanceArgs($args);