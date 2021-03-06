<?php
namespace App\Konohanaruto\Models\Intranet;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'admin_role_permission';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    public function addData($data)
    {
        if (empty($data['granted_permissions'])) {
            return false;
        }
        
        foreach ($data['granted_permissions'] as $permissionId) {
            $status = $this->insert(array(
                'role_id' => $data['role_id'],
                'permission_id' => $permissionId
            ));
        }
        
        return $status;
    }
    
    /**
     * 删除
     *
     * @param string|int $ids 例如: 1 或者 逗号连接的1, 2 ....
     * @return boolean
     */
    public function removeDataByRoleId($ids)
    {
        $ids = explode(',', $ids);
        // 删除方法返回的是受影响的行 0行则返回0
        return $this->whereIn('role_id', $ids)->delete();
    }
    
    /**
     * 根据角色id查看对应的权限
     * 
     * @param integer $roleId
     * @return array
     */
    public function getPermissionsByRoleId($roleId)
    {
        $permissions = static::where('role_id', '=', $roleId)->get();
        
        if (empty($permissions)) {
            return false;
        }
        
        return $permissions->toArray();
    }
    
    /**
     * 根据角色id，权限id删除记录
     */
    public function removeRowsByRolePermission($args)
    {
        $roleId = $args['role_id'];
        $permissionId = $args['permission_id'];
        // 返回受影响的行, integer
        return $this->where(array(
            array('role_id', $roleId),
            array('permission_id', $permissionId)
        ))->delete();
    }
    
    /**
     * 按permission id删除相关记录
     * 
     * @param integer $permission_id
     * @return mixed
     */
    public function removeAllByPermissionId($permissionId)
    {
        return $this->where('permission_id', $permissionId)->delete();
    }
}