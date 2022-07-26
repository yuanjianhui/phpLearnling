<?php
namespace App\Http\Controllers;

use  myframe\Controller;
use  App\Category;
use  App\Article;
use  myframe\Page;

class  IndexController extends  Controller
{
    public  function  index(Category $category,Article $article)
    {
        $id = $this->request->get('id',0);
        $page = $this->request->get('page',1);
        $size = 2;
        $offset = ($page-1) * $size;
        $where=[];
        if($id){
            $where['cid']=$id;
            $category_name=$category->where('id',$id)->value('name');
            $this->assign('category_name',$category_name);
        }
        $where['show']=1;
        $data = $article->where($where)->orderBy('id','DESC')->limit($offset,$size)->get(['id','title','author','image','created_at']);
        $this->assign('article',$data);
        $this->assign('id',$id);
        $total = $article->where($where)->count();
        $url = "?id=$id&page=";
        $this->assign('page_html',Page::html($url,$total,$page,$size));
        $this->Category($category);
        $this->siderbar($article);
        $this->title($id ? $category_name : '首页');
        return $this->fetch('index');
    }
    protected  function  title($title='')
    {
        $this->assign('title',$title);
    }
    public  function  Category(Category $category)
    {
        $data = $category->orderBy('id','ASC')->get();
        $this->assign('Category',$data);
    }
    protected  function  siderbar(Article $article)
    {
        $data =$article->where('show',1)->orderBy('id','DESC')->limit(5)->get(['id','title']);
        $this->assign('article_new',$data);
        $data = $article->where('show',1)->orderBy('views','DESC')->limit(10)->get(['id','title']);
        $this->assign('article_hot',$data);
    }
    public  function  show(Category $category,Article $article)
    {
        $id = $this->request->get('id');
        $data = $article->where('id',$id)->where('show',1)->first();
        if($data){
            $category_name = $category->where('id',$data['cid'])->value('name');
            $this->assign('category_name',$category_name);
        }
        $this->assign('article',$data);
        $this->assign('id',isset($data['cid']) ? $data['cid'] : 0);
        $this->Category($category);
        $this->siderbar($article);
        $this->title($data ? $data['title'] : '');

        return $this->fetch('show');
        $article->where('id',$id)->where('show',1)->increment('views');
        $data['views'] +=1;
    }

}
