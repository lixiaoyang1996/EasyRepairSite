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

 Date: 04/01/2018 17:00:48
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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='用户组表';

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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='规则表';

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
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0：前台下单完成，待用户后台确认 1：用户后台确认完成，待支付 2：用户支付完成 待商家后台确认订单并服务（此时商家后台才能看到对应订单） 3：商家确认订单并开始服务 4：商家服务完成，待用户评价和确认 5：用户评价和确认完成，商家获取维修款，订单彻底完成！',
  `create_time` int(11) DEFAULT NULL COMMENT '订单创建时间',
  `finish_time` int(11) DEFAULT NULL COMMENT '订单完成时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `sid` (`sid`),
  KEY `pid` (`pid`),
  CONSTRAINT `ers_order_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `ers_users` (`id`),
  CONSTRAINT `ers_order_ibfk_2` FOREIGN KEY (`sid`) REFERENCES `ers_shop` (`id`),
  CONSTRAINT `ers_order_ibfk_3` FOREIGN KEY (`pid`) REFERENCES `ers_price` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='订单表';

-- ----------------------------
-- Records of ers_order
-- ----------------------------
BEGIN;
INSERT INTO `ers_order` VALUES (1, 1, 2, 1, 0, NULL, NULL);
COMMIT;

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='价格表';

-- ----------------------------
-- Records of ers_price
-- ----------------------------
BEGIN;
INSERT INTO `ers_price` VALUES (1, 35, 1, 6);
INSERT INTO `ers_price` VALUES (2, 45, 1, 11);
INSERT INTO `ers_price` VALUES (3, 78, 1, 7);
INSERT INTO `ers_price` VALUES (4, 23, 1, 13);
COMMIT;

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
  `tid` int(11) DEFAULT NULL COMMENT '维修类别id',
  `way` int(11) NOT NULL DEFAULT '0' COMMENT '服务方式 0：上门服务 1：到店服务 2：上门+到店',
  `pic` varchar(255) DEFAULT NULL COMMENT '店铺缩略图',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '运营状态 0：禁用 1：正常',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`),
  CONSTRAINT `ers_shop_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `ers_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='店铺表';

-- ----------------------------
-- Records of ers_shop
-- ----------------------------
BEGIN;
INSERT INTO `ers_shop` VALUES (1, 'e修鸽维修', 5, 0, 'e修鸽到家服务工人队伍和业务规模一直在保持着高速发展。截至目前到家服务合作金牌师傅已有1500余位，覆盖城市90余座，用户口碑和好评率遥遥领先，目前到家服务主营业务有家电、房屋、水电等、家庭装修、家电清洗、清洁保洁、家电水电厨卫五金家具安装，同时新开通了手机维修 等。', 1, 2, 0, NULL, 0);
COMMIT;

-- ----------------------------
-- Table structure for ers_type
-- ----------------------------
DROP TABLE IF EXISTS `ers_type`;
CREATE TABLE `ers_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '维修类别id',
  `name` varchar(60) DEFAULT NULL COMMENT '类别名',
  `pid` int(11) DEFAULT '0' COMMENT '上一级分类id 0：顶级分类',
  `show` int(11) NOT NULL DEFAULT '1' COMMENT '是否显示 0：隐藏 1：显示 默认1',
  `sort` int(11) NOT NULL DEFAULT '99' COMMENT '排序id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='维修类别表';

-- ----------------------------
-- Records of ers_type
-- ----------------------------
BEGIN;
INSERT INTO `ers_type` VALUES (1, '家电维修', 0, 1, 2);
INSERT INTO `ers_type` VALUES (2, '数码维修', 0, 1, 1);
INSERT INTO `ers_type` VALUES (3, '手机', 2, 1, 1);
INSERT INTO `ers_type` VALUES (4, '平板', 2, 1, 3);
INSERT INTO `ers_type` VALUES (5, '电脑', 2, 1, 2);
INSERT INTO `ers_type` VALUES (6, '小米', 3, 1, 2);
INSERT INTO `ers_type` VALUES (7, '魅族', 3, 1, 3);
INSERT INTO `ers_type` VALUES (8, '一加', 3, 1, 1);
INSERT INTO `ers_type` VALUES (9, '华为', 3, 0, 4);
INSERT INTO `ers_type` VALUES (10, '惠普', 5, 1, 1);
INSERT INTO `ers_type` VALUES (11, '戴尔', 5, 1, 2);
INSERT INTO `ers_type` VALUES (12, '联想', 5, 0, 3);
INSERT INTO `ers_type` VALUES (13, 'Apple', 4, 1, 1);
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
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '账号状态 1：正常 0：禁用',
  `sid` int(11) NOT NULL DEFAULT '0' COMMENT '店铺id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of ers_users
-- ----------------------------
BEGIN;
INSERT INTO `ers_users` VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@ers.com', 11111111111, NULL, NULL, '安徽信息工程学院', 1514874262, 1514874681, 1, 0);
INSERT INTO `ers_users` VALUES (2, 'user1', '24c9e15e52afc47c225b757e7bee1f9d', 'user1@qq.com', 11111111111, NULL, NULL, '安徽信息工程学院', 1514874262, 1514874653, 1, 0);
INSERT INTO `ers_users` VALUES (3, 'business1', 'ab36fdc41550db15fd4a47f2e44f0076', 'business1@qq.com', 234523453245, NULL, NULL, '安徽省芜湖市镜湖区和平大厦一楼', 1514874262, 1514874670, 1, 1);
COMMIT;

-- ----------------------------
-- Function structure for getChildList
-- ----------------------------
DROP FUNCTION IF EXISTS `getChildList`;
delimiter ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `getChildList`( rootId INT ) RETURNS varchar(1000) CHARSET utf8
BEGIN
DECLARE
	sTemp VARCHAR ( 4000 );
DECLARE
	sTempChd VARCHAR ( 4000 );

SET sTemp = '$';

SET sTempChd = cast( rootId AS CHAR );
WHILE
		sTempChd IS NOT NULL DO
		
		SET sTemp = CONCAT( sTemp, ',', sTempChd );
	SELECT
		group_concat( id ) INTO sTempChd 
	FROM
		ers_type 
	WHERE
		FIND_IN_SET( pid, sTempChd ) > 0;
	
END WHILE;
RETURN sTemp;

END;
;;
delimiter ;

-- ----------------------------
-- Function structure for getParList
-- ----------------------------
DROP FUNCTION IF EXISTS `getParList`;
delimiter ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `getParList`( childId INT ) RETURNS varchar(1000) CHARSET utf8
BEGIN
DECLARE
	sTemp VARCHAR ( 1000 );
DECLARE
	sTempPar VARCHAR ( 1000 );

SET sTemp = '';

SET sTempPar = childId;#循环递归
WHILE
	sTempPar IS NOT NULL DO#判断是否是第一个，不加的话第一个会为空
IF
sTemp != '' THEN

SET sTemp = concat( sTemp, ',', sTempPar );
ELSE 
	SET sTemp = sTempPar;

END IF;

SET sTemp = concat( sTemp, ',', sTempPar );
SELECT
	group_concat( pid ) INTO sTempPar 
FROM
	ers_type 
WHERE
	pid <> id 
	AND FIND_IN_SET( id, sTempPar ) > 0;

END WHILE;
RETURN sTemp;

END;
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
