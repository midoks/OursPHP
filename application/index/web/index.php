<?php
/**
 * Created by PhpStorm.
 * User: Jinshang.co
 * Date: 2017/3/8
 * Time: 1:21
 * Doc:
 */
//初始项目相关框架

if (isset($_GET['debug']) && $_GET['debug'] == 'ok'){
	define('OURS_DEBUG', true);
}

require dirname(dirname(__DIR__)).'/bootstrap.php';