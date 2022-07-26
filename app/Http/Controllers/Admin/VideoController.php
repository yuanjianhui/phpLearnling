<?php
namespace App\Http\Controllers\Admin;

use App\Video;
use App\Category;
use myframe\Page;
use HTMLPurifier;

class VideoController extends CommonController
{


    public function delete(Video $video)
    {
        $id = $this->request->get('id', '');
        $video->where('id', $id)->delete();
        $this->success('删除成功');
    }

    public function edit(Video $video, Category $category)
    {
        $id = $this->request->get('id', '');
        if ($id) {
            $data = $video->where('id', $id)->first();
            if (!$data) {
                return '该视频可能不存在';
            }
        } else {
            $data = [
                'title' => '',
                'cid' => 0,
                'author' => '',
                'video' => '',
                'show' => '0'
            ];
        }
        $cgData = $category->orderBy('sort', 'ASC')->get();
        $this->assign('id', $id);
        $this->assign('data', $data);
        $this->assign('category', $cgData);
        return $this->fetch('admin/video_edit');
    }

    public function save(Video $video)
    {
        $id = $this->request->post('id', '');
        if ($this->request->hasFile('video')) {
            $mp4 = $this->uploadVideo();
        }else{
            $mp4='';
        }
        $data = [
            'title' => $this->request->post('title', ''),
            'cid' => $this->request->post('cid', 0),
            'author' => $this->request->post('author', ''),
            'video'=>$mp4,
            'show' => $this->request->post('show', '0'),
        ];

        if ($id) {
            $video->where('id', $id)->update($data);
            $this->success('修改成功');
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $video->insert($data);
            $this->success('添加成功');
        }
    }

    protected function uploadVideo()
    {
        //获取Upload对象
        $file = $this->request->file('video');
        //判断文件类型
        $allow_ext = ['mp4'];
        $ext = $file->extension();
        if (!in_array(strtolower($ext), $allow_ext)) {
            $this->error('视频上传失败：只允许扩展名：' . implode(', ', $allow_ext));
        }
        //生成文件保存路径并移动到指定目录
        $sub = date('Y-m/d');
        $name = $file->move('./uploads/video/' . $sub);
        //返回文件路径
        return $sub . '/' . $name;
    }
    public function shows(Video $video)
    {
        $data = $video->orderBy('id', 'ASC')->get();
        $this->assign('video',$data);
        return  $this->fetch('admin/video_list');
    }
    public  function  discuss(Video $video)
    {
        $id = $this->request->get('id', '');
        $data = $video->where('id',$id)->first();
        $this->assign('article',$data);
        return  $this->fetch('admin/article_discuss');
    }
}