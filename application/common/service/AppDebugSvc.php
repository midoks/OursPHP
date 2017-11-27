<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace common\service;

use common\dao\AppDebugDao;

class AppDebugSvc extends BaseSvc {

    /**
     * 获取分页数列表
     * @param int $page 第几页
     * @param int $pageSize 每页数据
     * @param array $condtion 条件
     * @return array 二维数据
     */
    public function getPageData($page = 1, $pageSize = 10, $condition = NULL){
        $dao    = new AppDebugDao();
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

        return $dao->findAll( $bind, $where, $limit, [], '' , '' , 'id desc');
    }

    /**
     * 根据条件统计总数
     * @param array $condtion 条件
     * @return int
     */
    public function getCount($condition = NULL){
        $dao    = new AppDebugDao();

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
     * 添加数据
     * @param array $item 一维数组
     * @return bool
     */
    public function add($item) {
        if(!empty($item)) {
            $dao = new AppDebugDao();
            $item['add_time'] = time();
            return $dao->add($item);
        }
        return false;
    }

    public function write($msg, $type=0){
        return $this->add([
            'type' => $type,
            'msg'  => $msg,]);
    }

    
}