<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Konohanaruto\Repositories\Intranet\AdminActivity\AdminActivityRepositoryInterface;

class CoreController extends Controller
{
    protected $adminLog;
    
    public function __construct()
    {
        $this->adminLog = app(AdminActivityRepositoryInterface::class);
    }
    
    /**
     * 写入管理员操作表
     * 
     * @param unknown $content
     * @return boolean
     */
    public function writeAdminLog($content, $ip = '')
    {
        return $this->adminLog->saveLog($content, $ip);
    }
    
    public function getCurrentUserInfo()
    {
        return session(config('custom.intranetSessionName'));
    }
    
    public function getCurrentUserId()
    {
        $userInfo = session(config('custom.intranetSessionName'));
        
        if (! empty($userInfo['admin_id'])) {
            return $userInfo['admin_id'];
        }
        
        return false;
    }
}
