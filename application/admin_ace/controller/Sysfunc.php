<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace  app\controller;


use common\dao\SysFunc as SysFuncDao;

use \common\service\SysSvc;

class Sysfunc extends Base {

    public function __construct($request, $response){
        parent::__construct($request, $response);
        $response->title = '管理员管理';
    }

    public function index($request, $response) {
        $response->stitle='系统功能管理';

        $svc        = new SysSvc();
        $rootMenu   = $svc->getFuncs();

        if(!empty($rootMenu)) {
            foreach ($rootMenu as &$fun) {
                $fun['sub'] = $svc->getFuncs($fun['id']);
            }
        }
        $response->rootMenu = $rootMenu;
        $this->renderLayout();
    }

    public function functionsetmenu($request,$response) {
        $id=$request->id;
        if($id) {
            $dao = new SysFunc();
            $dao->ismenu($id);
        }
        $this->renderString('ok');
    }

    
    public function del($request,$response) {
        $id = $request->id;
        if($id) {
            $svc    = new SysSvc();
            $rows   = $svc->getFunctions($id);
            if(count($rows)==0) {
                $dao = new System_Function();
                $dao->delete($id);
                $this->renderString('ok');
            } else {
                $this->renderString('存在子功能，禁止删除');
            }
        } else {
            $this->renderString('ok');
        }
    }


    public function functionlock($request) {
        $id=$request->id;
        if($id) {
            $dao = new System_Function();
            $dao->lock($id);
        }
        $this->renderString('ok');
    }

    public function add($request,$response) {
        $response->stitle = '功能添加';
        if($request->id) {
            $response->stitle = '功能编辑';
            $id     = $request->id;
            $svc    = new SysSvc();
            $item   = $svc->getFunc($id);
            $response->item = $item;
        }

        if($request->isPost()) {
            $vars = $request->vars;
            if(isset($vars['ismenu'])) {
                $vars['ismenu']=1;
            }

            $vars['type'] = ($vars['pid'] == 0) ? 0 : 1;
            $svc  = new SysSvc();

            if(isset($id)) {
                $svc->editFunc($id,$vars);
            } else {
                $svc->addFunc($vars);
            }

            $this->redirect('/system/functionlist');
        }

        //icon start
        $icon_fa = 'fa-adjust,fa-asterisk,fa-ban,fa-bar-chart-o,fa-barcode,fa-flask,fa-beer,fa-bell-o,fa-bell,fa-bolt,fa-book,fa-bookmark,fa-bookmark-o,fa-briefcase,fa-bullhorn,fa-calendar,fa-camera,fa-camera-retro,fa-certificate,fa-check-square-o,fa-square-o,fa-circle,fa-circle-o,fa-cloud,fa-cloud-download,fa-cloud-upload,fa-coffee,fa-cog,fa-cogs,fa-comment,fa-comment-o,fa-comments,fa-comments-o,fa-credit-card,fa-tachometer,fa-desktop,fa-arrow-circle-o-down,fa-download,fa-pencil-square-o,fa-envelope,fa-envelope-o,fa-exchange,fa-exclamation-circle,fa-external-link,fa-eye-slash,fa-eye,fa-video-camera,fa-fighter-jet,fa-film,fa-filter,fa-fire,fa-flag,fa-folder,fa-folder-open,fa-folder-o,fa-folder-open-o,fa-cutlery,fa-gift,fa-glass,fa-globe,fa-users,fa-hdd-o,fa-headphones,fa-heart,fa-heart-o,fa-home,fa-inbox,fa-info-circle,fa-key,fa-leaf,fa-laptop,fa-gavel,fa-lemon-o,fa-lightbulb-o,fa-lock,fa-unlock';
        $icon_glyphicon = 'glyphicon-asterisk,glyphicon-plus,glyphicon-euro,glyphicon-minus,glyphicon-cloud,glyphicon-envelope,glyphicon-pencil,glyphicon-glass,glyphicon-music,glyphicon-search,glyphicon-heart,glyphicon-star,glyphicon-star-empty,glyphicon-user,glyphicon-film,glyphicon-th-large,glyphicon-th,glyphicon-th-list,glyphicon-ok,glyphicon-remove,glyphicon-zoom-in,glyphicon-zoom-out,glyphicon-off,glyphicon-signal,glyphicon-cog,glyphicon-trash,glyphicon-home,glyphicon-file,glyphicon-time,glyphicon-road,glyphicon-download-alt,glyphicon-download,glyphicon-upload,glyphicon-inbox,glyphicon-play-circle,glyphicon-repeat,glyphicon-refresh,glyphicon-list-alt,glyphicon-lock,glyphicon-flag,glyphicon-headphones,glyphicon-volume-off,glyphicon-volume-down,glyphicon-volume-up,glyphicon-qrcode,glyphicon-barcode,glyphicon-tag,glyphicon-tags,glyphicon-book,glyphicon-bookmark,glyphicon-print,glyphicon-camera,glyphicon-font,glyphicon-bold,glyphicon-italic,glyphicon-text-height,glyphicon-text-width,glyphicon-align-left,glyphicon-align-center,glyphicon-align-right,glyphicon-align-justify,glyphicon-list,glyphicon-indent-left,glyphicon-indent-right,glyphicon-facetime-video,glyphicon-picture,glyphicon-map-marker,glyphicon-adjust,glyphicon-tint,glyphicon-edit,glyphicon-share,glyphicon-check,glyphicon-move,glyphicon-step-backward,glyphicon-fast-backward,glyphicon-backward';
        $iconarray['glyphicon'] = explode(',',$icon_glyphicon);
        $iconarray['fa'] = explode(',',$icon_fa);
        $response->icons = $iconarray;
        //icon end
        //隶属 start
        $svc = new SysSvc();
        $functionrows = $svc->getFuncs(0,1);

        //dump($functionrows);exit;
        $baserow = [ 'id'=>0, 'name' => '根节点' , 'icon' => '', 'type'=>0 , 'description' => '根节点', 'ismenu' => 0];
        array_unshift($functionrows,$baserow);
        $response->functionrows = $functionrows;
  
        $this->renderLayout();
    }
}