<?php
/**
 * date：2017年3月2日
 * author：zhaojin
 * encoding：UTF-8
 * file：indexcontroller.php
 * doc：
 *
 **/
namespace web\controller;

use OursPHP\Core\Mvc\Controller\Controller;

class IndexController extends Controller {
    public function __construct($request, &$response) {
        header("Content-type: text/html; charset=utf-8");
        parent::__construct($request, $response);
    }
    public function index($request, $response) {

        $response->title = "这是标题";
        $response->body  = "这是普通模式";
        $this->renderSmarty();
    }

}