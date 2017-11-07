<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace common\service;

use common\dao\SysRoleDao;

class SysRoleSvc extends BaseSvc {


     /**
     * 获取分页数列表
     * @param int $page 第几页
     * @param int $pageSize 每页数据
     * @param array $condtion 条件
     * @return array 二维数据
     */
    public function getPageData($page = 1, $pageSize = 10, $condition = NULL){
        $dao    = new SysRoleDao();
        $where = '1=1';
        $bind = [];
        if (!empty($condition)){
            $where = ' 1=1 ';
            foreach ($condition as $key => $value) {
                $where .= 'and '.$key.'=:'.$key;
            }
            $bind = $condition;
        }

        $offset = $pageSize * ( $page-1 );
        $limit =  $offset < 0 ? '0,'.$pageSize : $offset.','.$pageSize;

        return $dao->findAll( $bind, $where, $limit, [], '' , '' , 'id asc');
    }

    /**
     * 根据条件统计总数
     * @param array $condtion 条件
     * @return int
     */
    public function getCount($condition = NULL){
        $dao    = new SysRoleDao();

        if (!empty($condition)){
            $where = ' 1=1 ';
            foreach ($condition as $key => $value) {
                $where .= 'and '.$key.'=:'.$key;
            }
            $total = $dao->countBy($condition,$where);
        } else {
            $total = $dao->countBy([], ' 1=1 ');
        }

        return $total['num'];
    }

    /**
     * 获取全部角色
     * @param int $status
     * @return mixed
     */
    public function gets($status = null) {
        $dao    = new SysRoleDao();
        $where  = '';
        $query  = [];
        if($status !== null) {
            $query['status'] = $status;
            $where = "status=:status";
        }
        return $dao->findAll( $query, $where, 0, [], '', '', 'id asc');
    }

    /**
     * 通过主键ID获取信息
     * @param int $id 主键ID
     * @return array | bool
     */
    public function get($id) {
        if($id) {
            $dao = new SysRoleDao();
            return $dao->cache(60, 'role_'.$id)->findByPkey($id);
        }
        return false;
    }

    /**
     * 添加数据
     * @param array $item 一维数组
     * @return bool
     */
    public function add($item) {
        if(!empty($item)) {
            $dao = new SysRoleDao();
            return $dao->add($item);
        }
        return false;
    }

    /**
     * 根据主键ID修改信息
     * @param int $id ID
     * @param array $vars 一维数组
     * @return bool
     */
    public function edit($id,$vars) {
        if(!empty($vars)) {
            $dao = new SysRoleDao();
            $dao->clear('role_'.$id);
            return $dao->edit($id,$vars);
        }
        return false;
    }

    /**
     * 锁定或解锁功能
     * @param int $id 主键ID
     * @return bool
     */
    public function lock($id){
        $dao = new SysRoleDao();
        $fun = $dao->findByPkey($id);
        if($fun) {
            $vars['status'] = $fun['status'] == 1 ? 0 : 1;
            return $dao->edit($id,$vars);
        }
        return false;
    }

    /**
     * 删除数据功能
     * @param int $id 主键ID
     * @return bool
     */
    public function delete($id){
        $dao = new SysRoleDao();
        return $dao->delete($id);
    }

}