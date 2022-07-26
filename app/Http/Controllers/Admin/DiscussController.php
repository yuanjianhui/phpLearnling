<?php
namespace App\Http\Controllers\Admin;

use App\Article;
use App\Discuss;
use App\User;
use HTMLPurifier;

class DiscussController extends  CommonController
{
    public  function  index(Discuss $discuss){
        $data = $discuss->orderBy('id', 'ASC')->get();
        $this->assign('discuss',$data);
        return  $this->fetch('admin/discuss_list');
    }
   public  function  save(Discuss $discuss,Article $article,User $user,HTMLPurifier $purifier)
   {
       $id = $this->request->post('id', '');
        $data=$article->where('id',$id)->first();
        $this->assign('article',$data);
        $user =$this->request->post('username','');
        $this->assign('user',$user);
        //$data->$discuss('')

        $content=$this->request->post('content','');
        $content=$purifier->purify($content);
        $this->assign('data',$content);
        return   $this->fetch('admin/discuss_list');
   }
}
