<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace common\service;

use common\dao\SysFuncDao;

class SysFuncSvc extends BaseSvc {

    /**
     * 获取所有菜单
     * @return array
     */
    public function getMenu() {
        $rows = self::gets(0);
        if(!empty($rows)) {
            foreach ($rows as $k=>$v) {
                $rows[$k]['sub'] = self::gets($v['id']);
            }
        }
        return $rows;
    }

    /**
     * 获取子功能列表
     * @param $pid
     * @return mixed
     */
    public function gets($pid = 0, $status = null) {
        
        $dao            = new SysFuncDao();
        $query['pid']   = $pid;
        $where          = "pid=:pid";

        if($status !== null) {
            $query['status'] = $status;
            $where = $where." and status=:status ";
        }

        $field = [ 'id','`name`','pid','icon','type',
                    'controller', 'action', '`desc`',
                    'is_menu','`status`','`sort`' ];
        return $dao->findAll( $query, $where,0, $field,'','', '`sort` asc, id asc' );
    }

    /**
     * 通过主键ID获取信息
     * @param int $id 主键ID
     * @return array | bool
     */
    public function get($id) {
        if($id) {
            $dao = new SysFuncDao();
            return $dao->findByPkey($id);
        }
        return false;
    }

    /**
     * 添加系统功能
     * @param $item
     * @return bool|mixed
     */
    public function add($item) {
        if(!empty($item)) {
            $dao = new SysFuncDao();
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
        if(!empty($vars)) {
            $dao = new SysFuncDao();
            return $dao->edit($id,$vars);
        }
        return false;
    }

    /**
     * 设置是否菜单显示
     * @param int $id 主键ID
     * @return array | bool
     */
    public function isMenu($id) {
        $funcDao = new SysFuncDao();
        $func = $funcDao->findByPkey($id);
        if($func) {
            $vars['is_menu'] = $func['is_menu']== 1 ? 0 : 1;
            return $this->edit($id,$vars);
        }
        return false;
    }

    /**
     * 升序或降序功能
     * @param int $id 主键ID
     * @param int $asc 是否降序
     * @return bool
     */
    public function sort($id, $down = true){
        $funcDao = new SysFuncDao();
        $func = $funcDao->findByPkey($id);
        if($func) {
            if ($down){
                $vars['sort'] = $func['sort'] - 1;
            } else {
                $vars['sort'] = $func['sort'] + 1;
            }
            return $this->edit($id, $vars);
        }
        return false;
    }

    /**
     * 锁定或解锁功能
     * @param int $id 主键ID
     * @return bool
     */
    public function lock($id){
        $dao = new SysFuncDao();
        $fun = $dao->findByPkey($id);
        if($fun) {
            $vars['status'] = $fun['status'] == 1 ? 0 : 1;
            return $this->edit($id,$vars);
        }
        return false;
    }

    /**
     * 删除数据功能
     * @param int $id 主键ID
     * @return bool
     */
    public function delete($id){
        $dao = new SysFuncDao();
        return $dao->delete($id);
    }
}