<?php

namespace App\Konohanaruto\Repositories\Intranet\Menus;

use App\Konohanaruto\Repositories\Intranet\EloquentRepository;
use PhpParser\Builder\Interface_;

class MenusEloquentRepository extends EloquentRepository implements MenusRepositoryInterface
{

    public function getModel()
    {
        return \App\Konohanaruto\Models\Intranet\Menus::class;
    }
    
    /**
     * 得到menu list
     * 
     * @param void
     * @return array
     */
    public function getMenuList()
    {
        $list = $this->getAll();
        return empty($list) ? array() : $list->toArray();
    }
    
    /**
     * 得到顶级Menu
     * 
     * @param void
     * @return array
     */
    public function getTopMenus()
    {
        $menus = $this->model->where('menu_parent_id', 0)->get();
        return empty($menus) ? array() : $menus->toArray();
    }
    
    /**
     * 插入数据
     * 
     * @param array $formData
     * @return boolean
     */
    public function storeMenu($data)
    {
        $insert = array();
        
        $insert['menu_name']      = $data['menu_name'];
        // 确保为顶级Menus才允许写入该值
        $insert['menu_route']     = (empty($data['menu_route']) || $data['menu_parent_id'] ==0) ? '' : $data['menu_route'];
        $insert['permission_id']  = empty($data['permission_id']) ? 0 : $data['permission_id'];
        $insert['menu_parent_id'] = $data['menu_parent_id'];
        
        return $this->model->insert($insert);
    }
    
    /**
     * 得到层级关系的Menu树
     *
     * @param array $menuList
     * @return array
     */
    public function getMenuTree($menuList = array())
    {
        if (empty($menuList)) {
            return array();
        }
    
        $menuTree = array();
        
        foreach ($menuList as $key => $item) {
            if ($item['menu_parent_id'] == 0) {
                $menuTree[$item['menu_id']] = $item;
                unset($menuList[$key]);
            }
        }
    
        foreach ($menuList as $item) {
            $menuTree[$item['menu_parent_id']]['children'][$item['menu_id']] = $item;
        }
    
        return $menuTree;
    }
    
    /**
     * 删除记录
     * 
     * @param array $actionId 相关的menu_id数组
     * @return mixed 返回受影响的行
     */
    public function removeDataByMenuIds($menuIds)
    {
        return $this->model->whereIn('menu_id', $menuIds)->delete();
    }
    
    /**
     * 通过menu_id数组检索记录
     * 
     * @param array $menuIds
     * @return array
     */
    public function getMenuListByIds($menuIds)
    {
        $lists = $this->model->whereIn('menu_id', $menuIds)->get();
        
        return empty($lists) ? array() : $lists->toArray();
    }
    
    /**
     * 通过menu_id得到详细信息
     * 
     * @param integer $menuId
     * @return mixed
     */
    public function getDetailByMenuId($menuId)
    {
        $detail = $this->model->where('menu_id', $menuId)->first();
        
        return empty($detail) ? array() : $detail->toArray();
    }
    
    /**
     * 根据menu_id更新
     * 
     * @return boolean
     */
    public function updateData($data, $menuId)
    {
        return $this->model->where('menu_id', $menuId)->update($data);
    }
    
    /**
     * 通过route name得到父级menu id
     * 
     * @param string $routeName
     * @return mixed
     */
    public function getParentMenuIdByRoute($routeName)
    {
        $detail = $this->model->select('menu_parent_id as menu_id')->where('menu_route', $routeName)->first();
        
        return empty($detail) ? array() : $detail->toArray();
    }

    /**
     * 得到首个父级菜单
     */
    public function getFirstParentMenu()
    {
        $parentMenu = $this->model->select('menu_id')->where('menu_parent_id', 0)->first();

        return empty($parentMenu) ? array() : $parentMenu->toArray();
    }

    /**
     * 得到默认的首个子级menu
     *
     * @param $parentMenuId
     * @return array
     */
    public function getFirstChildrenRouteByParentMenuId($parentMenuId)
    {
        $menu = $this->model->select('menu_route')->where('menu_parent_id', $parentMenuId)->first();

        return empty($menu) ? array() : $menu->toArray();
    }

    public function getMenuInfoByRoute($route)
    {
        return $this->model->select('menu_id')->where('menu_route', $route)->first();
    }
}