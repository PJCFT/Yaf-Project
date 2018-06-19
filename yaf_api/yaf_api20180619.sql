/*
Navicat MySQL Data Transfer

Source Server         : local5.7
Source Server Version : 50720
Source Host           : localhost:3306
Source Database       : yaf_api

Target Server Type    : MYSQL
Target Server Version : 50720
File Encoding         : 65001

Date: 2018-06-19 18:03:19
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `art`
-- ----------------------------
DROP TABLE IF EXISTS `art`;
CREATE TABLE `art` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT '文章标题',
  `contents` text COLLATE utf8_unicode_ci NOT NULL COMMENT '文章内容',
  `author` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '作者名称',
  `cate` int(4) NOT NULL COMMENT '文章分类ID',
  `ctime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'create time',
  `mtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'modify time',
  `status` enum('delete','online','offline') COLLATE utf8_unicode_ci DEFAULT 'offline' COMMENT '是否被删除',
  PRIMARY KEY (`id`),
  KEY `Title index` (`title`),
  KEY `分类索引` (`cate`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章';

-- ----------------------------
-- Records of art
-- ----------------------------
INSERT INTO `art` VALUES ('4', 'pjcft2111', '123456781', 'pjc1', '1', '2017-05-13 14:18:00', '2018-04-24 21:02:05', 'offline');
INSERT INTO `art` VALUES ('5', '测试文章标题', '12312312', 'yi', '1', '2017-05-13 14:18:37', '2018-04-24 21:38:16', 'online');
INSERT INTO `art` VALUES ('6', 'pjcft211', '123456781', 'pjc1', '1', '2017-05-13 14:18:38', '2018-04-24 20:27:10', 'offline');
INSERT INTO `art` VALUES ('7', '测试文章标题', '12312312', 'yi', '1', '2017-05-13 14:21:01', '2018-04-24 21:43:01', 'online');
INSERT INTO `art` VALUES ('10', '测试文章标题', '12312312', 'yi', '1', '2017-05-13 14:21:20', '2017-05-13 14:21:20', 'offline');
INSERT INTO `art` VALUES ('14', '测试文章 testId:31801Changed444', '测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493测试内容1807888493151', 'yi1948226284152', '1', '2017-05-13 14:54:38', '2017-05-13 14:55:51', 'offline');
INSERT INTO `art` VALUES ('16', 'pjcft2', '12345678', 'pjc', '1', '2018-04-23 23:00:56', '2018-04-23 23:00:56', 'offline');
INSERT INTO `art` VALUES ('18', 'pjcft21', '12345678', 'pjc', '1', '2018-04-24 11:58:31', '2018-04-24 11:58:31', 'offline');

-- ----------------------------
-- Table structure for `bill`
-- ----------------------------
DROP TABLE IF EXISTS `bill`;
CREATE TABLE `bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '账单id',
  `itemid` int(11) NOT NULL COMMENT '商品id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `price` int(11) NOT NULL DEFAULT '0' COMMENT '商品价格，单位为分',
  `status` enum('paid','unpaid','failed','') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unpaid' COMMENT '支付状态',
  `transaction` text COLLATE utf8_unicode_ci COMMENT '交易ID',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `ptime` timestamp NULL DEFAULT NULL COMMENT '支付时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of bill
-- ----------------------------
INSERT INTO `bill` VALUES ('1', '1', '1', '10', 'paid', '9223372036854775807', '2017-07-06 14:30:13', '2017-07-06 15:02:52', '2017-07-06 15:02:52');

-- ----------------------------
-- Table structure for `cate`
-- ----------------------------
DROP TABLE IF EXISTS `cate`;
CREATE TABLE `cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '类目名',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='分类信息';

-- ----------------------------
-- Records of cate
-- ----------------------------
INSERT INTO `cate` VALUES ('1', '啊哈哈哈');

-- ----------------------------
-- Table structure for `item`
-- ----------------------------
DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品名',
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT '商品描述',
  `price` bigint(20) NOT NULL DEFAULT '0' COMMENT '商品价格，单位为分',
  `stock` int(11) NOT NULL COMMENT '商品数量',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `etime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '过期时间',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商品';

-- ----------------------------
-- Records of item
-- ----------------------------
INSERT INTO `item` VALUES ('1', '测试商品123', '商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！商品描述信息！！！', '10', '99', '2017-07-06 14:08:37', '2017-07-31 14:22:29', '2017-07-06 14:30:13');

-- ----------------------------
-- Table structure for `sms_record`
-- ----------------------------
DROP TABLE IF EXISTS `sms_record`;
CREATE TABLE `sms_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `contents` text COLLATE utf8_unicode_ci NOT NULL COMMENT '消息内容',
  `template` int(11) NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '发送时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='短信发送记录';

-- ----------------------------
-- Records of sms_record
-- ----------------------------

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'user id',
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user name',
  `pwd` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user password',
  `email` text COLLATE utf8_unicode_ci COMMENT '用户邮箱',
  `mobile` bigint(11) DEFAULT NULL COMMENT '用户手机号',
  `reg_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'user register time',
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'information change time',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户注册信息表';

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'pangee', '64166e7bf41c7ada0f8c5b6e18301554', null, null, '2017-07-06 22:28:49', null);
INSERT INTO `user` VALUES ('4', 'apitest_uname_1300224274', '71788eccd0aaf996b569db61fb74b1d7', null, null, '2017-07-08 23:08:29', null);
INSERT INTO `user` VALUES ('5', 'test', 'c8bb9401addc891f66b7a6f4c2e85691', null, null, '2017-07-09 00:20:02', null);
INSERT INTO `user` VALUES ('6', 'apitest_uname_757319156', 'cd86831cae7a1624a93e2b4fe77025ea', null, null, '2017-07-09 00:47:49', null);
INSERT INTO `user` VALUES ('7', 'pjc', '481a19dda8c3c2ba6343eacbe32acf19', null, null, '2018-04-13 21:45:41', null);
INSERT INTO `user` VALUES ('8', 'pjcft', '481a19dda8c3c2ba6343eacbe32acf19', '238@qq.com', '17111111111', '2018-06-19 17:54:48', '2018-06-19 17:54:48');
INSERT INTO `user` VALUES ('9', 'pjcft2', '481a19dda8c3c2ba6343eacbe32acf19', '239@qq.com', '17111111111', '2018-06-19 17:55:05', '2018-06-19 17:55:05');
INSERT INTO `user` VALUES ('10', 'qfy', '481a19dda8c3c2ba6343eacbe32acf19', '507@qq.com', '17111111111', '2018-06-19 17:55:13', '2018-06-19 17:55:13');
