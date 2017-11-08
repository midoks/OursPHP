<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace  app\controller;


use common\dao\SysFuncDao;

use \common\service\SysSvc;
use \common\service\SysFuncSvc;

class Sysfunc extends Base {


    // public $beforeAction = [
    //     'action'    => ['setMenu', 'sort' , 'add', 'del',  'lock'],
    //     'callback'  => 'beforeRun',
    // ];

    public $afterAction = [
        'action'    => ['setMenu', 'sort' , 'add', 'del',  'lock'],
        'callback'  => 'afterRun',
    ];

    //初始化
    public function __construct($request, $response){
        parent::__construct($request, $response);
        $response->title = '功能管理';
    }

    //列表
    public function index($request, $response) {
        $response->stitle = '列表';

        $response->rootMenu = $this->_menu;
        
        $this->renderLayout();
    }


    public function beforeRun($request, $response){
        echo "beforeRun";

    }

    
    public function afterRun($request, $response){
        $funcSvc    = new SysFuncSvc();
        $funcSvc->cacheClear('admin_func_list');
    }


    //菜单设置功能
    public function setMenu($request,$response) {
        $id = $request->id;
        if($id) {
            $funcSvc    = new SysFuncSvc();
            $funcSvc->isMenu($id);
        }
        $this->renderString('ok');
    }

    //添加编辑功能
    public function add($request,$response) {
        $response->stitle = '功能添加';

        $funcSvc = new SysFuncSvc();
        if($request->id) {
            $response->stitle = '功能编辑';
            $id     = $request->id;
            $item   = $funcSvc->get($id);
            $response->item = $item;
        }

        if($request->isPost()) {
            $vars = $request->vars;
            if(isset($vars['is_menu'])) {
                $vars['is_menu'] = 1;
            }

            $vars['type'] = ($vars['pid'] == 0) ? 0 : 1;

            if(isset($id)) {
                $funcSvc->edit($id,$vars);
            } else {
                $funcSvc->add($vars);
            }

            $this->redirect('/'.$this->_controller.'/index');
        }

        //icon start
        $icon_fa = 'fa-adjust,fa-asterisk,fa-ban,fa-bar-chart-o,fa-barcode,fa-flask,fa-beer,fa-bell-o,fa-bell,fa-bolt,fa-book,fa-bookmark,fa-bookmark-o,fa-briefcase,fa-bullhorn,fa-calendar,fa-camera,fa-camera-retro,fa-certificate,fa-check-square-o,fa-square-o,fa-circle,fa-circle-o,fa-cloud,fa-cloud-download,fa-cloud-upload,fa-coffee,fa-cog,fa-cogs,fa-comment,fa-comment-o,fa-comments,fa-comments-o,fa-credit-card,fa-tachometer,fa-desktop,fa-arrow-circle-o-down,fa-download,fa-pencil-square-o,fa-envelope,fa-envelope-o,fa-exchange,fa-exclamation-circle,fa-external-link,fa-eye-slash,fa-eye,fa-video-camera,fa-fighter-jet,fa-film,fa-filter,fa-fire,fa-flag,fa-folder,fa-folder-open,fa-folder-o,fa-folder-open-o,fa-cutlery,fa-gift,fa-glass,fa-globe,fa-users,fa-hdd-o,fa-headphones,fa-heart,fa-heart-o,fa-home,fa-inbox,fa-info-circle,fa-key,fa-leaf,fa-laptop,fa-gavel,fa-lemon-o,fa-lightbulb-o,fa-lock,fa-unlock';
        $icon_glyphicon = 'glyphicon-asterisk,glyphicon-plus,glyphicon-euro,glyphicon-minus,glyphicon-cloud,glyphicon-envelope,glyphicon-pencil,glyphicon-glass,glyphicon-music,glyphicon-search,glyphicon-heart,glyphicon-star,glyphicon-star-empty,glyphicon-user,glyphicon-film,glyphicon-th-large,glyphicon-th,glyphicon-th-list,glyphicon-ok,glyphicon-remove,glyphicon-zoom-in,glyphicon-zoom-out,glyphicon-off,glyphicon-signal,glyphicon-cog,glyphicon-trash,glyphicon-home,glyphicon-file,glyphicon-time,glyphicon-road,glyphicon-download-alt,glyphicon-download,glyphicon-upload,glyphicon-inbox,glyphicon-play-circle,glyphicon-repeat,glyphicon-refresh,glyphicon-list-alt,glyphicon-lock,glyphicon-flag,glyphicon-headphones,glyphicon-volume-off,glyphicon-volume-down,glyphicon-volume-up,glyphicon-qrcode,glyphicon-barcode,glyphicon-tag,glyphicon-tags,glyphicon-book,glyphicon-bookmark,glyphicon-print,glyphicon-camera,glyphicon-font,glyphicon-bold,glyphicon-italic,glyphicon-text-height,glyphicon-text-width,glyphicon-align-left,glyphicon-align-center,glyphicon-align-right,glyphicon-align-justify,glyphicon-list,glyphicon-indent-left,glyphicon-indent-right,glyphicon-facetime-video,glyphicon-picture,glyphicon-map-marker,glyphicon-adjust,glyphicon-tint,glyphicon-edit,glyphicon-share,glyphicon-check,glyphicon-move,glyphicon-step-backward,glyphicon-fast-backward,glyphicon-backward';
        $iconarray['glyphicon'] = explode(',',$icon_glyphicon);
        $iconarray['fa'] = explode(',',$icon_fa);
        $response->icons = $iconarray;
        //icon end

        $functionrows = $funcSvc->gets(0,1);
        $response->functionrows = $functionrows;
  
        $this->renderLayout();
    }

    //删除数据功能
    public function del($request, $response) {
        $id = $request->id;
        if($id) {
            $funcSvc    = new SysFuncSvc();
            $rows   = $funcSvc->gets($id);
            if( count($rows) == 0 ) {
                $funcSvc->delete($id);
                $this->renderString('ok');
            } else {
                $this->renderString('存在子功能，禁止删除');
            }
        } else {
            $this->renderString('ok');
        }
    }

    //锁定解锁功能
    public function lock($request, $response) {
        $id = $request->id;
        if($id) {
            $funcSvc = new SysFuncSvc();
            $funcSvc->lock($id);
        }

        return $this->renderString('ok');
    }

    //升序降序功能
    public function sort($request, $response){
        $id     = $request->id;
        $type   = $request->type;

        $funcSvc = new SysFuncSvc();

        $ret = false;
        if ($type == 'up'){
            $ret = $funcSvc->sort($id, true);
        } else if ($type == 'down'){
            $ret = $funcSvc->sort($id, false);
        }

        if ($ret){
            return $this->renderString('ok');
        } else {
            return $this->renderString('fail');
        }
    }

    
}