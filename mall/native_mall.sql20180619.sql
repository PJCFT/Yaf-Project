/*
Navicat MySQL Data Transfer

Source Server         : local5.7
Source Server Version : 50720
Source Host           : localhost:3306
Source Database       : native_mall

Target Server Type    : MYSQL
Target Server Version : 50720
File Encoding         : 65001

Date: 2018-06-19 08:54:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `nt_admin`
-- ----------------------------
DROP TABLE IF EXISTS `nt_admin`;
CREATE TABLE `nt_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '//管理员id',
  `admin_name` varchar(50) NOT NULL DEFAULT '' COMMENT '//管理员名',
  `admin_password` varchar(50) NOT NULL DEFAULT '' COMMENT '//管理员密码',
  `admin_phone` varchar(11) NOT NULL DEFAULT '' COMMENT '//手机号',
  `create_time` int(11) NOT NULL COMMENT '//创建时间',
  `update_time` int(11) NOT NULL COMMENT '//修改时间',
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nt_admin
-- ----------------------------
INSERT INTO `nt_admin` VALUES ('1', 'admin', '19de4feba62affeef88c76d0a6c693dc', '17111111111', '1521543417', '1521553470');
INSERT INTO `nt_admin` VALUES ('2', 'admin123', '19de4feba62affeef88c76d0a6c693dc', '17111111111', '0', '1521553470');
INSERT INTO `nt_admin` VALUES ('3', 'pjc', '19de4feba62affeef88c76d0a6c693dc', '', '0', '0');
INSERT INTO `nt_admin` VALUES ('4', 'sss', '19de4feba62affeef88c76d0a6c693dc', '', '0', '0');
INSERT INTO `nt_admin` VALUES ('5', 'ss', '19de4feba62affeef88c76d0a6c693dc', '', '0', '0');
INSERT INTO `nt_admin` VALUES ('6', 's', '19de4feba62affeef88c76d0a6c693dc', '', '0', '0');
INSERT INTO `nt_admin` VALUES ('7', 'qfy', '19de4feba62affeef88c76d0a6c693dc', '', '0', '0');
INSERT INTO `nt_admin` VALUES ('8', 'hrt', '19de4feba62affeef88c76d0a6c693dc', '', '0', '0');
INSERT INTO `nt_admin` VALUES ('9', 'dgx', '19de4feba62affeef88c76d0a6c693dc', '', '0', '0');
INSERT INTO `nt_admin` VALUES ('10', 'cjy', '19de4feba62affeef88c76d0a6c693dc', '', '0', '0');
INSERT INTO `nt_admin` VALUES ('11', 'kdy', '19de4feba62affeef88c76d0a6c693dc', '', '0', '0');
INSERT INTO `nt_admin` VALUES ('13', '111111', '19de4feba62affeef88c76d0a6c693dc', '', '0', '0');
INSERT INTO `nt_admin` VALUES ('14', '123456', '19de4feba62affeef88c76d0a6c693dc', '', '0', '0');
INSERT INTO `nt_admin` VALUES ('15', '111', '19de4feba62affeef88c76d0a6c693dc', '17111111111', '1521543417', '1521543417');

-- ----------------------------
-- Table structure for `nt_goods`
-- ----------------------------
DROP TABLE IF EXISTS `nt_goods`;
CREATE TABLE `nt_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '//商品id',
  `name` varchar(100) DEFAULT NULL COMMENT '//商品名称',
  `price` int(11) DEFAULT NULL COMMENT '//商品价格',
  `pic` varchar(255) DEFAULT NULL COMMENT '//商品图片',
  `des` varchar(200) DEFAULT NULL COMMENT '//商品简介',
  `content` longtext COMMENT '//商品详细信息',
  `user_id` int(11) DEFAULT NULL COMMENT '//用户id',
  `create_time` int(11) DEFAULT NULL COMMENT '//发布时间',
  `update_time` int(11) DEFAULT NULL COMMENT '//修改时间',
  `view` int(11) DEFAULT '0' COMMENT '//浏览次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nt_goods
-- ----------------------------
INSERT INTO `nt_goods` VALUES ('2', '华硕电脑519L', '3400', 'http://mall.hd/admin/style/upload/2018/0331/5abf5d48861616383.jpg', '华硕w519l，你值得拥有！', '<p>500G大容量，4G大内存，运行无卡顿！！！<br/></p>', '1', '1522490696', '1522490696', '12');
INSERT INTO `nt_goods` VALUES ('3', '中兴BLADE A2', '599', 'http://mall.hd/admin/style/upload/2018/0331/5abf5e92126902774.jpg', '配置：2G+16G，不容错过！', '<p>指纹解锁，超大黑边！！<br/></p>', '1', '1522491026', '1522491026', '4');
INSERT INTO `nt_goods` VALUES ('5', '1212', '111', 'http://mall.hd/admin/style/upload/2018/0402/5ac182a30a8577664.jpg', '按时大大1212', '<p>啊撒大叔大叔1212<br/></p>', '1', '1522497723', '1522631331', '5');
INSERT INTO `nt_goods` VALUES ('6', '2131', '123', 'http://mall.hd/admin/style/upload/2018/0331/5abf7d625c08d8824.jpg', '123123', '<p>12312312</p>', '7', '1522498914', '1522498914', '1');
INSERT INTO `nt_goods` VALUES ('7', '34343', '343', 'http://mall.hd/admin/style/upload/2018/0331/5abf7db169a7f2919.jpg', '3434334', '<p>3434</p>', '7', '1522498993', '1522498993', '9');
INSERT INTO `nt_goods` VALUES ('8', '娃哈哈', '3', 'http://mall.hd/admin/style/upload/2018/0402/5ac17520e0e267715.jpg', '好好喝的饮料~~~', '<p>早餐必备饮料，健康生活你拥有！<br/></p>', '1', '1522627872', '1522844282', '8');
INSERT INTO `nt_goods` VALUES ('9', '贵妃糖', '5', 'http://mall.hd/admin/style/upload/2018/0402/5ac176c853d642962.jpg', '好吃的糖', '<p>好吃的糖！！！<br/></p>', '1', '1522628296', '1522628296', '1');

-- ----------------------------
-- Table structure for `nt_user`
-- ----------------------------
DROP TABLE IF EXISTS `nt_user`;
CREATE TABLE `nt_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '//主键id',
  `user_name` varchar(50) NOT NULL DEFAULT '' COMMENT '//用户名',
  `user_sex` varchar(2) NOT NULL DEFAULT '' COMMENT '//发布者性别：0表示男性；1表示女性',
  `user_phone` varchar(11) NOT NULL DEFAULT '' COMMENT '//发布者手机号',
  `user_postcode` varchar(11) DEFAULT '' COMMENT '//发布者邮编',
  `user_address` varchar(255) DEFAULT '' COMMENT '//发布者地址',
  `user_password` varchar(50) NOT NULL DEFAULT '' COMMENT '//密码',
  `create_time` int(11) NOT NULL COMMENT '//创建时间',
  `update_time` int(11) NOT NULL COMMENT '//修改时间',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nt_user
-- ----------------------------
INSERT INTO `nt_user` VALUES ('1', 'admin', '0', '11111111111', '525200', '广东', '19de4feba62affeef88c76d0a6c693dc', '1519028677', '1522481073');
INSERT INTO `nt_user` VALUES ('2', 'user0', '0', '', '', '', '19de4feba62affeef88c76d0a6c693dc', '1519028699', '0');
INSERT INTO `nt_user` VALUES ('3', 'user1', '0', '', '', '', '19de4feba62affeef88c76d0a6c693dc', '1519028998', '0');
INSERT INTO `nt_user` VALUES ('6', 'admin123', '1', '11111111111', '525200', '广东', '19de4feba62affeef88c76d0a6c693dc', '1519267180', '1522325666');
INSERT INTO `nt_user` VALUES ('7', 'admin111', '1', '', '', '', '19de4feba62affeef88c76d0a6c693dc', '1519267256', '0');
INSERT INTO `nt_user` VALUES ('8', '123456', '1', '11111111111', '525200', '广东', '19de4feba62affeef88c76d0a6c693dc', '1522307791', '1522307791');
INSERT INTO `nt_user` VALUES ('9', 'pjc', '0', '11111111111', '525200', '广东', '19de4feba62affeef88c76d0a6c693dc', '1522307866', '1522307866');
INSERT INTO `nt_user` VALUES ('10', 'cft', '1', '11111111111', '525200', '广东', '19de4feba62affeef88c76d0a6c693dc', '1522404737', '1522404737');

-- ----------------------------
-- Table structure for `nt_visitor`
-- ----------------------------
DROP TABLE IF EXISTS `nt_visitor`;
CREATE TABLE `nt_visitor` (
  `visitor_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '//普通用户id',
  `visitor_name` varchar(50) NOT NULL DEFAULT '' COMMENT '//普通用户名',
  `visitor_password` varchar(50) NOT NULL DEFAULT '' COMMENT '//普通用户密码',
  `create_time` int(11) NOT NULL COMMENT '//创建时间',
  `update_time` int(11) NOT NULL COMMENT '//修改时间',
  PRIMARY KEY (`visitor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nt_visitor
-- ----------------------------
INSERT INTO `nt_visitor` VALUES ('1', 'visitor1', '9121cf7807d5e0290568361aa81a6ce1', '1521619564', '1522895539');
INSERT INTO `nt_visitor` VALUES ('2', 'visitor2', '19de4feba62affeef88c76d0a6c693dc', '1521619635', '1521619635');
INSERT INTO `nt_visitor` VALUES ('4', 'visitor4', '19de4feba62affeef88c76d0a6c693dc', '1521619709', '1521619709');
INSERT INTO `nt_visitor` VALUES ('5', 'visitor5', '19de4feba62affeef88c76d0a6c693dc', '1521619725', '1521619725');
INSERT INTO `nt_visitor` VALUES ('6', 'visitor6', '19de4feba62affeef88c76d0a6c693dc', '1521619740', '1521623275');
INSERT INTO `nt_visitor` VALUES ('7', 'visitor7', '19de4feba62affeef88c76d0a6c693dc', '1521619759', '1521619759');
INSERT INTO `nt_visitor` VALUES ('8', 'visitor8', '19de4feba62affeef88c76d0a6c693dc', '1522851808', '1522851808');
INSERT INTO `nt_visitor` VALUES ('9', 'visitor9', '19de4feba62affeef88c76d0a6c693dc', '1522852610', '1522852610');
INSERT INTO `nt_visitor` VALUES ('10', 'visitor10', '19de4feba62affeef88c76d0a6c693dc', '1522852777', '1522852777');
