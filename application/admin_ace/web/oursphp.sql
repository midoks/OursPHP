/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50624
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2017-11-03 17:46:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sys_func`
-- ----------------------------
DROP TABLE IF EXISTS `sys_func`;
CREATE TABLE `sys_func` (
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='权限列表';

-- ----------------------------
-- Records of sys_func
-- ----------------------------
INSERT INTO `sys_func` VALUES ('1', '系统设置', '0', 'system', 'index', '0', '1', 'fa fa-cog', '系统相关参数设置11111', '0', '1', '2017-03-14 01:28:12', '2017-11-03 10:15:28');
INSERT INTO `sys_func` VALUES ('2', '管理员管理', '1', 'sysuser', 'index', '1', '1', 'fa fa-users', '添加、删除、编辑系统管理员的权限。', '0', '1', '2017-03-14 01:51:41', '2017-11-03 12:32:43');
INSERT INTO `sys_func` VALUES ('3', '系统功能添加', '1', 'sysfunc', 'add', '1', '0', 'glyphicon glyphicon-th', '系统功能添加', '0', '1', '2017-03-14 02:01:14', '2017-11-03 11:26:12');
INSERT INTO `sys_func` VALUES ('5', '功能管理', '1', 'sysfunc', 'index', '1', '1', '', '功能列表', '0', '1', '2017-03-14 02:03:39', '2017-11-03 10:31:24');
INSERT INTO `sys_func` VALUES ('6', '系统功能删除', '1', 'sysfunc', 'functiondel', '1', '0', '', '系统功能删除', '0', '1', '2017-03-14 02:04:20', '2017-11-03 10:29:00');
INSERT INTO `sys_func` VALUES ('7', '添加管理员', '1', 'sysuser', 'add', '1', '0', 'glyphicon glyphicon-user', '添加管理员', '0', '1', '2017-03-14 02:15:48', '2017-11-03 13:47:29');
INSERT INTO `sys_func` VALUES ('9', '管理员删除', '1', 'sysuser', 'managerdel', '1', '0', '', '管理员删除', '0', '1', '2017-03-14 02:17:31', '2017-11-03 13:47:31');
INSERT INTO `sys_func` VALUES ('11', '重置管理员密码', '1', 'sysuser', 'repassword', '1', '0', '', '重置管理员密码', '0', '1', '2017-03-14 02:23:05', '2017-11-03 17:01:21');
INSERT INTO `sys_func` VALUES ('12', '锁定管理员', '1', 'sysuser', 'managerlock', '1', '0', '', '锁定管理员', '0', '1', '2017-03-14 02:23:59', '2017-11-03 17:01:16');
INSERT INTO `sys_func` VALUES ('13', '系统功能锁定', '1', 'sysfunc', 'functionlock', '1', '0', '', '系统功能锁定', '0', '1', '2017-03-14 02:24:54', '2017-11-03 10:29:08');
INSERT INTO `sys_func` VALUES ('14', '角色管理', '1', 'sysrole', 'index', '1', '1', 'fa fa-users', '系统功能锁定', '0', '1', '2017-03-14 02:26:19', '2017-11-03 17:00:47');
INSERT INTO `sys_func` VALUES ('15', '添加角色', '1', 'sysrole', 'add', '1', '0', 'fa fa-users', '添加角色', '0', '1', '2017-03-14 02:26:56', '2017-11-03 17:00:52');
INSERT INTO `sys_func` VALUES ('17', '删除角色', '1', 'sysrole', 'del', '1', '0', 'fa fa-users', '删除角色', '0', '1', '2017-03-14 02:28:03', '2017-11-03 17:00:55');
INSERT INTO `sys_func` VALUES ('18', '锁定角色', '1', 'sysrole', 'lock', '1', '0', 'fa fa-users', '锁定角色', '0', '1', '2017-03-14 02:28:34', '2017-11-03 17:01:03');
INSERT INTO `sys_func` VALUES ('19', '功能设置菜单', '1', 'sysfunc', 'setmenu', '1', '0', '', '功能设置菜单', '0', '1', '2017-03-14 02:50:05', '2017-11-03 17:01:12');
INSERT INTO `sys_func` VALUES ('20', '控制台', '0', 'index', 'index', '0', '0', '', '网站后台首页', '0', '1', '2017-03-17 01:53:31', '2017-11-03 09:56:55');
INSERT INTO `sys_func` VALUES ('21', '首页', '20', 'index', 'index', '1', '0', '', '后台首页', '0', '1', '2017-03-17 01:55:11', '2017-11-03 09:56:49');
INSERT INTO `sys_func` VALUES ('22', '密码项目', '0', 'mima', 'init', '0', '1', 'fa fa-bell', '', '0', '1', '2017-11-03 17:40:51', '2017-11-03 17:40:51');

-- ----------------------------
-- Table structure for `sys_logs`
-- ----------------------------
DROP TABLE IF EXISTS `sys_logs`;
CREATE TABLE `sys_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `msg` text NOT NULL,
  `add_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of sys_logs
-- ----------------------------

-- ----------------------------
-- Table structure for `sys_role`
-- ----------------------------
DROP TABLE IF EXISTS `sys_role`;
CREATE TABLE `sys_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '角色名称',
  `desc` varchar(200) DEFAULT NULL COMMENT '角色介绍',
  `list` text NOT NULL COMMENT '权限列表JSON',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态1有效0无效',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='管理员权限表';

-- ----------------------------
-- Records of sys_role
-- ----------------------------
INSERT INTO `sys_role` VALUES ('1', '系统管理员', '系统总管理员', '2,3,5,6,7,9,11,12,13,14,15,17,18,19,21', '1', '2017-03-15 15:39:20', '2017-11-03 17:34:37');
INSERT INTO `sys_role` VALUES ('3', '编辑', '普通编辑人员', '7,9,14,18,19', '1', '2017-11-03 17:35:17', '2017-11-03 17:35:17');

-- ----------------------------
-- Table structure for `sys_user`
-- ----------------------------
DROP TABLE IF EXISTS `sys_user`;
CREATE TABLE `sys_user` (
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='管理员';

-- ----------------------------
-- Records of sys_user
-- ----------------------------
INSERT INTO `sys_user` VALUES ('3', 'admin', '21232f297a57a5a743894a0e4a801fc3', '管理员', '1', 'admin@localhost', '13000000000', '1', '1', '2017-03-18 09:22:25', '2017-11-03 16:38:48');
