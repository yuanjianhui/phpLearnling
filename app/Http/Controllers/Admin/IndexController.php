<?php
namespace App\Http\Controllers\Admin;

use myframe\DB;

class IndexController extends CommonController
{
    public function index()
    {
        // 获取系统信息
        $serverInfo = [
            'server_version' => $this->request->server('SERVER_SOFTWARE'),
            'mysql_version' => $this->getMySQLVer(),
            'upload_max_filesize' => ini_get('file_uploads') ? ini_get('upload_max_filesize') : '已禁用',
            'max_execution_time' => ini_get('max_execution_time') . '秒',
            'server_time' => date('Y-m-d H:i:s', time())
        ];
        $this->assign('server_info', $serverInfo);
        return $this->fetch('admin/index');
    }
    protected function getMySQLVer()
    {
        $db = DB::getInstance();
        $res = $db->fetchRow('SELECT VERSION() AS ver');
        return $res ? $res['ver'] : '未知';
    }
}