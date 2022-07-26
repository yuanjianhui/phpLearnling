<?php
namespace App\Http\Controllers\User;

use App\Article;
use App\Category;
use myframe\Page;
use HTMLPurifier;

class ArticleController extends CommonController
{
    public function index(Article $article)
    {
        $page = $this->request->get('page', 1);
        $pageSize = 2;
        $offset = ($page - 1) * $pageSize;
        $data = $article->limit($offset, $pageSize)->orderBy('created_at', 'DESC')->get(['id', 'title', 'author', 'show', 'views', 'created_at']);
        $total = $article->count();
        $pageHtml = Page::html('?page=', $total, $page, $pageSize);
        $this->assign('article', $data);
        $this->assign('page_html', $pageHtml);
        return $this->fetch('user/article_list');
    }

    public function delete(Article $article)
    {
        $id = $this->request->get('id', '');
        $article->where('id', $id)->delete();
        $this->success('删除成功');
    }

    public function edit(Article $article, Category $category)
    {
        $id = $this->request->get('id', '');
        if ($id) {
            $data = $article->where('id', $id)->first();
            if (!$data) {
                return '文章不存在';
            }
        } else {
            $data = [
                'title' => '',
                'cid' => 0,
                'author' => '',
                'image' => '',
                'content' => '',
                'show' => '0'
            ];
        }
        $cgData = $category->orderBy('sort', 'ASC')->get();
        $this->assign('id', $id);
        $this->assign('data', $data);
        $this->assign('category', $cgData);
        return $this->fetch('user/article_edit');
    }

    public function save(Article $article, HTMLPurifier $purifier)
    {
        $id = $this->request->post('id', '');
        $data = [
            'title' => $this->request->post('title', ''),
            'cid' => $this->request->post('cid', 0),
            'author' => $this->request->post('author', ''),
            'content' => $this->request->post('content', ''),
            'show' => $this->request->post('show', '0'),
        ];
        $data['content'] = $purifier->purify($data['content']);
        if ($this->request->hasFile('image')) {
            $data['image'] = $this->uploadImage();
        }
        if ($id) {
            $article->where('id', $id)->update($data);
            $this->success('修改成功');
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $article->insert($data);
            $this->success('添加成功');
        }
    }

    protected function uploadImage()
    {
        //获取Upload对象
        $file = $this->request->file('image');
        //判断文件类型
        $allow_ext = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
        $ext = $file->extension();
        if (!in_array(strtolower($ext), $allow_ext)) {
            $this->error('文件上传失败：只允许扩展名：' . implode(', ', $allow_ext));
        }
        //生成文件保存路径并移动到指定目录
        $sub = date('Y-m/d');
        $name = $file->move('./uploads/images/' . $sub);
        //返回文件路径
        return $sub . '/' . $name;
    }
    public function shows(Article $article)
    {
        $data = $article->orderBy('id', 'ASC')->get();
        $this->assign('article',$data);
        return  $this->fetch('user/article_receive');
    }
    public  function  discuss(Article $article)
    {
        $id = $this->request->get('id', '');
        $data = $article->where('id',$id)->first();
        $this->assign('article',$data);
        return  $this->fetch('user/article_discuss');
    }
}