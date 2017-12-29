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

 Date: 29/12/2017 09:39:47
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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户组表';

-- ----------------------------
-- Records of ers_auth_group
-- ----------------------------
BEGIN;
INSERT INTO `ers_auth_group` VALUES (1, '普通用户', 1, '3');
INSERT INTO `ers_auth_group` VALUES (2, '商家', 1, '2');
INSERT INTO `ers_auth_group` VALUES (3, '管理员', 1, '1');
COMMIT;

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
-- Records of ers_auth_group_access
-- ----------------------------
BEGIN;
INSERT INTO `ers_auth_group_access` VALUES (1, 3);
INSERT INTO `ers_auth_group_access` VALUES (2, 1);
INSERT INTO `ers_auth_group_access` VALUES (3, 2);
COMMIT;

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='规则表';

-- ----------------------------
-- Records of ers_auth_rule
-- ----------------------------
BEGIN;
INSERT INTO `ers_auth_rule` VALUES (1, 'Admin/*', '管理员后台', 1, 1, '');
INSERT INTO `ers_auth_rule` VALUES (2, 'Business/*', '商家后台', 1, 1, '');
INSERT INTO `ers_auth_rule` VALUES (3, 'User/*', '普通用户后台', 1, 1, '');
COMMIT;

-- ----------------------------
-- Table structure for ers_order
-- ----------------------------
DROP TABLE IF EXISTS `ers_order`;
CREATE TABLE `ers_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单id',
  `sid` int(11) NOT NULL COMMENT '店铺id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `pid` int(11) NOT NULL COMMENT '价格id',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `sid` (`sid`),
  KEY `pid` (`pid`),
  CONSTRAINT `ers_order_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `ers_users` (`id`),
  CONSTRAINT `ers_order_ibfk_2` FOREIGN KEY (`sid`) REFERENCES `ers_shop` (`id`),
  CONSTRAINT `ers_order_ibfk_3` FOREIGN KEY (`pid`) REFERENCES `ers_price` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表';

-- ----------------------------
-- Table structure for ers_price
-- ----------------------------
DROP TABLE IF EXISTS `ers_price`;
CREATE TABLE `ers_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '价格表id',
  `price` decimal(11,0) DEFAULT NULL COMMENT '维修价格',
  `sid` int(11) DEFAULT NULL COMMENT '店铺id',
  `tid` int(11) DEFAULT NULL COMMENT '维修类别id',
  PRIMARY KEY (`id`),
  KEY `sid` (`sid`),
  KEY `tid` (`tid`),
  CONSTRAINT `ers_price_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `ers_shop` (`id`),
  CONSTRAINT `ers_price_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `ers_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='价格表';

-- ----------------------------
-- Table structure for ers_shop
-- ----------------------------
DROP TABLE IF EXISTS `ers_shop`;
CREATE TABLE `ers_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '店铺id',
  `name` varchar(60) NOT NULL COMMENT '店铺名',
  `score` int(11) DEFAULT NULL COMMENT '评分',
  `orders` int(11) DEFAULT NULL COMMENT '订单数',
  `detail` text COMMENT '店铺详情',
  `check` int(11) NOT NULL DEFAULT '0' COMMENT '审核状态 0：未审核 1：审核通过 2：审核未通过',
  `uid` int(11) NOT NULL COMMENT '商家id',
  `tid` int(11) DEFAULT NULL COMMENT '维修类别',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `tid` (`tid`),
  CONSTRAINT `ers_shop_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `ers_users` (`id`),
  CONSTRAINT `ers_shop_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `ers_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺表';

-- ----------------------------
-- Table structure for ers_type
-- ----------------------------
DROP TABLE IF EXISTS `ers_type`;
CREATE TABLE `ers_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '维修类别id',
  `name` varchar(60) DEFAULT NULL COMMENT '类别名',
  `pid` int(11) DEFAULT '0' COMMENT '上一级分类id 0：顶级分类',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='维修类别表';

-- ----------------------------
-- Records of ers_type
-- ----------------------------
BEGIN;
INSERT INTO `ers_type` VALUES (1, '家电维修', 0);
INSERT INTO `ers_type` VALUES (2, '数码维修', 0);
INSERT INTO `ers_type` VALUES (3, '手机', 2);
INSERT INTO `ers_type` VALUES (4, '平板', 2);
INSERT INTO `ers_type` VALUES (5, '电脑', 2);
INSERT INTO `ers_type` VALUES (6, '小米', 3);
INSERT INTO `ers_type` VALUES (7, '魅族', 3);
INSERT INTO `ers_type` VALUES (8, '一加', 3);
INSERT INTO `ers_type` VALUES (9, '华为', 3);
COMMIT;

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
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `register_time` int(11) NOT NULL COMMENT '注册时间',
  `login_time` int(11) NOT NULL COMMENT '最后一次登录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of ers_users
-- ----------------------------
BEGIN;
INSERT INTO `ers_users` VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@ers.com', 11111111111, NULL, NULL, NULL, 0, 0);
INSERT INTO `ers_users` VALUES (2, 'user1', '24c9e15e52afc47c225b757e7bee1f9d', 'user1@qq.com', 11111111111, NULL, NULL, NULL, 0, 0);
INSERT INTO `ers_users` VALUES (3, 'business1', 'ab36fdc41550db15fd4a47f2e44f0076', 'business1@qq.com', 11111111111, NULL, NULL, NULL, 0, 0);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
