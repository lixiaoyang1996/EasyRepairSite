/*
 Navicat Premium Data Transfer

 Source Server         : mac-mysql
 Source Server Type    : MySQL
 Source Server Version : 50720
 Source Host           : localhost:3306
 Source Schema         : ers

 Target Server Type    : MySQL
 Target Server Version : 50720
 File Encoding         : 65001

 Date: 25/12/2017 11:24:44
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ers_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `ers_auth_group`;
CREATE TABLE `ers_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` char(100) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1：正常 0：禁用',
  `rules` char(80) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id 多个规则用“,”分开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组表';

-- ----------------------------
-- Table structure for ers_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `ers_auth_group_access`;
CREATE TABLE `ers_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组明细表';

-- ----------------------------
-- Table structure for ers_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `ers_auth_rule`;
CREATE TABLE `ers_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文名称',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1：正常  0：禁用',
  `condition` char(100) NOT NULL DEFAULT '' COMMENT '规则表达式，为空表示存在就认证，不为空表示按照条件认证',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='规则表';

-- ----------------------------
-- Table structure for ers_users
-- ----------------------------
DROP TABLE IF EXISTS `ers_users`;
CREATE TABLE `ers_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` varchar(60) NOT NULL COMMENT '用户名',
  `password` varchar(64) NOT NULL COMMENT '密码',
  `email` varchar(60) NOT NULL COMMENT '邮箱',
  `phone` bigint(11) NOT NULL COMMENT '手机号',
  `avatar` varchar(255) DEFAULT NULL COMMENT '用户头像',
  `sex` varchar(10) DEFAULT NULL COMMENT '性别',
  `address` varchar(255) DEFAULT NULL COMMENT '住址',
  `register_time` int(11) NOT NULL COMMENT '注册时间',
  `login_time` int(11) NOT NULL COMMENT '最后一次登录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

SET FOREIGN_KEY_CHECKS = 1;
