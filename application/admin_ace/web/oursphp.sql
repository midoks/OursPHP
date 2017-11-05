-- phpMyAdmin SQL Dump
-- version 4.1.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-11-05 23:49:14
-- 服务器版本： 5.6.34
-- PHP Version: 5.5.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- 表的结构 `sys_func`
--

CREATE TABLE IF NOT EXISTS `sys_func` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父ID 0顶级',
  `controller` varchar(100) NOT NULL,
  `action` varchar(100) DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '类型：0controller 1action',
  `is_menu` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否是菜单：0不是1是',
  `icon` varchar(100) DEFAULT NULL,
  `desc` varchar(200) DEFAULT NULL COMMENT '介绍',
  `sort` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态：0无效1有效-1软删除',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='权限列表' AUTO_INCREMENT=33 ;

--
-- 转存表中的数据 `sys_func`
--

INSERT INTO `sys_func` (`id`, `name`, `pid`, `controller`, `action`, `type`, `is_menu`, `icon`, `desc`, `sort`, `status`, `create_time`, `update_time`) VALUES
(1, '系统设置', 0, 'system', 'index', 0, 1, 'fa fa-cog', '系统相关参数设置11111', 0, 1, '2017-03-13 17:28:12', '2017-11-04 14:52:02'),
(2, '管理员管理', 1, 'sysuser', 'index', 1, 1, 'fa fa-users', '添加、删除、编辑系统管理员的权限。', 0, 1, '2017-03-13 17:51:41', '2017-11-05 15:35:51'),
(3, '系统功能添加', 1, 'sysfunc', 'add', 1, 0, 'glyphicon glyphicon-th', '系统功能添加', 6, 1, '2017-03-13 18:01:14', '2017-11-05 15:39:00'),
(5, '功能管理', 1, 'sysfunc', 'index', 1, 1, '', '功能列表', 7, 1, '2017-03-13 18:03:39', '2017-11-05 15:39:20'),
(6, '系统功能删除', 1, 'sysfunc', 'del', 1, 0, '', '系统功能删除', 8, 1, '2017-03-13 18:04:20', '2017-11-05 15:39:32'),
(7, '添加管理员', 1, 'sysuser', 'add', 1, 0, 'glyphicon glyphicon-user', '添加管理员', 1, 1, '2017-03-13 18:15:48', '2017-11-05 15:36:11'),
(9, '管理员删除', 1, 'sysuser', 'del', 1, 0, '', '管理员删除', 2, 1, '2017-03-13 18:17:31', '2017-11-05 15:37:55'),
(11, '重置管理员密码', 1, 'sysuser', 'repwd', 1, 0, '', '重置管理员密码', 3, 1, '2017-03-13 18:23:05', '2017-11-05 15:38:11'),
(12, '锁定管理员', 1, 'sysuser', 'lock', 1, 0, '', '锁定管理员', 4, 1, '2017-03-13 18:23:59', '2017-11-05 15:38:21'),
(13, '系统功能锁定', 1, 'sysfunc', 'lock', 1, 0, '', '系统功能锁定', 9, 1, '2017-03-13 18:24:54', '2017-11-05 15:39:41'),
(14, '角色管理', 1, 'sysrole', 'index', 1, 1, 'fa fa-users', '系统功能锁定', 10, 1, '2017-03-13 18:26:19', '2017-11-05 15:39:50'),
(15, '添加角色', 1, 'sysrole', 'add', 1, 0, 'fa fa-users', '添加角色', 11, 1, '2017-03-13 18:26:56', '2017-11-05 15:40:09'),
(17, '删除角色', 1, 'sysrole', 'del', 1, 0, 'fa fa-users', '删除角色', 12, 1, '2017-03-13 18:28:03', '2017-11-05 15:40:21'),
(18, '锁定角色', 1, 'sysrole', 'lock', 1, 0, 'fa fa-users', '锁定角色', 13, 1, '2017-03-13 18:28:34', '2017-11-05 15:41:25'),
(19, '功能设置菜单', 1, 'sysfunc', 'setmenu', 1, 0, '', '功能设置菜单', 9, 1, '2017-03-13 18:50:05', '2017-11-05 15:42:08'),
(22, '加密相关', 0, 'mima', 'init', 0, 1, 'fa fa-bell', '', 0, 1, '2017-11-03 09:40:51', '2017-11-05 13:07:01'),
(23, 'authcode', 22, 'encrypt', 'index', 1, 1, '', 'discuz加密解密方案', 0, 1, '2017-11-04 02:30:44', '2017-11-04 15:33:07'),
(30, '功能升降序', 1, 'sysfunc', 'sort', 1, 0, '', '功能升降序', 5, 1, '2017-11-05 13:05:54', '2017-11-05 15:38:34'),
(31, '后台模版事例', 0, 'tpl', 'index', 0, 1, 'glyphicon glyphicon-star-empty', '后台模版事例', 0, 1, '2017-11-05 13:11:36', '2017-11-05 13:11:36'),
(32, '异步上传', 31, 'tpl', 'index', 1, 1, '', '', 0, 1, '2017-11-05 13:12:17', '2017-11-05 13:12:41');

-- --------------------------------------------------------

--
-- 表的结构 `sys_logs`
--

CREATE TABLE IF NOT EXISTS `sys_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `msg` text NOT NULL,
  `add_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `sys_role`
--

CREATE TABLE IF NOT EXISTS `sys_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '角色名称',
  `desc` varchar(200) DEFAULT NULL COMMENT '角色介绍',
  `list` text NOT NULL COMMENT '权限列表JSON',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态1有效0无效',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='管理员权限表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `sys_role`
--

INSERT INTO `sys_role` (`id`, `name`, `desc`, `list`, `status`, `create_time`, `update_time`) VALUES
(1, '系统管理员', '系统总管理员', '2,7,9,11,12,19,30,3,5,6,13,14,15,17,18,23,32', 1, '2017-03-15 07:39:20', '2017-11-05 13:12:27'),
(3, '编辑', '普通编辑人员', '2,23', 1, '2017-11-03 09:35:17', '2017-11-05 06:17:11');

-- --------------------------------------------------------

--
-- 表的结构 `sys_user`
--

CREATE TABLE IF NOT EXISTS `sys_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) NOT NULL COMMENT '登录名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `nick` varchar(50) DEFAULT NULL COMMENT '昵称',
  `sex` tinyint(4) DEFAULT '0' COMMENT '1男0女',
  `mail` varchar(150) DEFAULT NULL COMMENT '邮箱',
  `tel` varchar(11) DEFAULT NULL COMMENT '手机号',
  `roleid` int(11) DEFAULT '0' COMMENT '所属角色',
  `status` tinyint(4) DEFAULT '1' COMMENT '状体1有效0无效',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='管理员' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `sys_user`
--

INSERT INTO `sys_user` (`id`, `username`, `password`, `nick`, `sex`, `mail`, `tel`, `roleid`, `status`, `create_time`, `update_time`) VALUES
(3, 'admin', '21232f297a57a5a743894a0e4a801fc3', '管理员', 1, 'admin@localhost', '13000000000', 1, 1, '2017-03-18 01:22:25', '2017-11-05 09:20:44'),
(4, 'guest', '084e0343a0486ff05530df6c705c8bb4', 'guest', 1, '13800138000@qq.com', '13800138000', 3, 1, '2017-11-04 15:14:56', '2017-11-04 15:14:56');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
