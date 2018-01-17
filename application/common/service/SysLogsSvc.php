<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace common\service;

use common\dao\SysLogsDao;

class SysLogsSvc extends BaseSvc {

    /**
     * 获取分页数列表
     * @param int $page 第几页
     * @param int $pageSize 每页数据
     * @param array $condtion 条件
     * @return array 二维数据
     */
    public function getPageData($page = 1, $pageSize = 10, $condition = NULL) {
        $dao   = new SysLogsDao();
        $where = '1=1';
        $bind  = [];
        if (!empty($condition)) {
            $where = ' 1=1 ';
            foreach ($condition as $key => $value) {
                $where .= 'and ' . $key . '=:' . $key;
            }
            $bind = $condition;
        }

        $offset = $pageSize * ($page - 1);
        $limit  = $offset < 0 ? '0,' . $pageSize : $offset . ',' . $pageSize;

        return $dao->findAll($bind, $where, $limit, [], '', '', 'id desc');
    }

    /**
     * 根据条件统计总数
     * @param array $condtion 条件
     * @return int
     */
    public function getCount($condition = NULL) {
        $dao = new SysLogsDao();

        if (!empty($condition)) {
            $where = ' 1=1 ';
            foreach ($condition as $key => $value) {
                $where .= 'and ' . $key . '=:' . $key;
            }
            $total = $dao->countBy($condition, $where);
        } else {
            $total = $dao->countBy([], ' 1=1 ');
        }

        return $total['num'];
    }

    /**
     * 通过主键ID获取信息
     * @param int $id 主键ID
     * @return array | bool
     */
    public function get($id) {
        if ($id) {
            $dao = new SysLogsDao();
            return $dao->findByPkey($id);
        }
        return false;
    }

    /**
     * 添加数据
     * @param array $item 一维数组
     * @return bool
     */
    public function add($item) {
        if (!empty($item)) {
            $dao = new SysLogsDao();
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
    public function edit($id, $vars) {
        if (!empty($vars)) {
            $dao = new SysLogsDao();
            return $dao->edit($id, $vars);
        }
        return false;
    }

    /**
     * 锁定或解锁功能
     * @param int $id 主键ID
     * @return bool
     */
    public function lock($id) {
        $dao = new SysLogsDao();
        $fun = $dao->findByPkey($id);
        if ($fun) {
            $vars['status'] = $fun['status'] == 1 ? 0 : 1;
            return $dao->edit($id, $vars);
        }
        return false;
    }

    /**
     * 删除数据功能
     * @param int $id 主键ID
     * @return bool
     */
    public function delete($id) {
        $dao = new SysLogsDao();
        return $dao->delete($id);
    }

}