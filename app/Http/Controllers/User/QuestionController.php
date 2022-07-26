<?php
namespace App\Http\Controllers\User;

use App\Category;
use App\Question;
use myframe\Page;
use HTMLPurifier;

class QuestionController extends CommonController
{
    public function index(Question $question)
    {
        $data = $question->orderBy('created_at', 'DESC')->get(['id', 'title', 'author', 'show','views','created_at']);
        $this->assign('question', $data);
        return $this->fetch('user/question_list');
    }

    public function delete(Question $question)
    {
        $id = $this->request->get('id', '');
        $question->where('id', $id)->delete();
        $this->success('删除成功');
    }

    public function edit(Question $question, Category $category)
    {
        $id = $this->request->get('id', '');
        if ($id) {
            $data = $question->where('id', $id)->first();
            if (!$data) {
                return '问题不存在';
            }
        } else {
            $data = [
                'title' => '',
                'cid' => 0,
                'author' => '',
                'content' => '',
                'show' => '0'
            ];
        }
        $cgData = $category->orderBy('sort', 'ASC')->get();
        $this->assign('id', $id);
        $this->assign('data', $data);
        $this->assign('category', $cgData);
        return $this->fetch('user/question_edit');
    }
    public function save(Question $question, HTMLPurifier $purifier)
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
        if ($id) {
            $question->where('id', $id)->update($data);
            $this->success('修改成功');
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $question->insert($data);
            $this->success('添加成功');
        }
    }

}