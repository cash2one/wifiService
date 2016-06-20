/*
 Navicat Premium Data Transfer

 Source Server         : bisheng
 Source Server Type    : MySQL
 Source Server Version : 50517
 Source Host           : 192.168.8.67
 Source Database       : wifiservice

 Target Server Type    : MySQL
 Target Server Version : 50517
 File Encoding         : utf-8

 Date: 06/16/2016 16:38:44 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `admin_role`
-- ----------------------------
DROP TABLE IF EXISTS `admin_role`;
CREATE TABLE `admin_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_menu` varchar(1000) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='管理员角色表';

-- ----------------------------
--  Records of `admin_role`
-- ----------------------------
BEGIN;
INSERT INTO `admin_role` VALUES ('1', '1,2,3,4,5,6,7,8,9,10', '1'), ('2', '3,6', '3'), ('6', '1,2,3,4,5', '2'), ('8', '1,4,5,7', '4');
COMMIT;

-- ----------------------------
--  Table structure for `ibs_pay`
-- ----------------------------
DROP TABLE IF EXISTS `ibs_pay`;
CREATE TABLE `ibs_pay` (
  `id` int(2) NOT NULL,
  `type` tinyint(2) DEFAULT '1' COMMENT '支付类型：0不通过ibs支付，1通过ibs支付',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='通过改变type来确定是否用ibs来支付';

-- ----------------------------
--  Records of `ibs_pay`
-- ----------------------------
BEGIN;
INSERT INTO `ibs_pay` VALUES ('1', '1', '是否通过ibs系统来支付 0：不通过，1:通过'), ('2', '1', '是否需要查询余额 0:不需要  1:需要');
COMMIT;

-- ----------------------------
--  Table structure for `ibsxml_log`
-- ----------------------------
DROP TABLE IF EXISTS `ibsxml_log`;
CREATE TABLE `ibsxml_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) DEFAULT NULL COMMENT '类型，0:接收 1:发送',
  `content` text COMMENT 'XML内容',
  `time` datetime DEFAULT NULL COMMENT '时间',
  `identififer` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='IBS交互记录表';

-- ----------------------------
--  Table structure for `role_info`
-- ----------------------------
DROP TABLE IF EXISTS `role_info`;
CREATE TABLE `role_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `role_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `role_info`
-- ----------------------------
BEGIN;
INSERT INTO `role_info` VALUES ('2', '1', 'wifibilling/indata/index'), ('4', '2', 'wifibilling/indata/wifiurl'), ('6', '3', 'wifibilling/indata/currdata'), ('8', '4', 'wifibilling/indata/updata'), ('10', '5', 'wifibilling/indata/pay'), ('12', '6', 'wifibilling/indata/payinformation'), ('14', '7', 'wifibilling/indata/report'), ('18', '1', 'wifibilling/indata/edit'), ('20', '1', 'wifibilling/indata/delete'), ('22', '1', 'wifibilling/indata/deleteall'), ('24', '2', 'wifibilling/indata/editurl'), ('26', '2', 'wifibilling/indata/deleteurl'), ('28', '2', 'wifibilling/indata/deleteallurl'), ('30', '4', 'wifibilling/indata/infodata'), ('32', '4', 'wifibilling/indata/savedata'), ('34', '1', 'wifibilling/default/index'), ('36', '2', 'wifibilling/default/index'), ('38', '3', 'wifibilling/default/index'), ('40', '4', 'wifibilling/default/index'), ('42', '5', 'wifibilling/default/index'), ('44', '6', 'wifibilling/default/index'), ('46', '7', 'wifibilling/default/index'), ('48', '8', 'wifibilling/indata/authcontroller'), ('50', '9', 'wifibilling/indata/wlanparams'), ('52', '9', 'wifibilling/indata/editwlan'), ('54', '9', 'wifibilling/indata/deleteallwlan'), ('56', '9', 'wifibilling/indata/deletewlan'), ('58', '10', 'wifibilling/indata/changepassword');
COMMIT;

-- ----------------------------
--  Table structure for `wifi_admin`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_admin`;
CREATE TABLE `wifi_admin` (
  `admin_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(255) NOT NULL COMMENT '用于登录',
  `admin_real_name` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `last_login_ip` varchar(255) NOT NULL,
  `last_login_time` datetime NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0未激活 1激活',
  `is_login` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
--  Records of `wifi_admin`
-- ----------------------------
BEGIN;
INSERT INTO `wifi_admin` VALUES ('1', 'super_admin', '超级管理员', '888888', '1', '127.0.0.1', '2016-04-11 02:42:31', '123@163.com', '1', '0'), ('2', '123456', 'qq', '123456', '1', '1', '0000-00-00 00:00:00', '', '0', '0'), ('3', 'test', 'fdas', '123456', '1', '1', '2016-04-20 14:28:59', '1', '0', '0'), ('4', 'sdf', 'sd', 'hhh123456', '1', '1', '2016-04-13 09:29:04', '1', '1', '0');
COMMIT;

-- ----------------------------
--  Table structure for `wifi_info`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_info`;
CREATE TABLE `wifi_info` (
  `wifi_info_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wifi_id` int(10) DEFAULT NULL,
  `wifi_code` varchar(32) DEFAULT NULL COMMENT 'wifi登录帐号',
  `wifi_password` varchar(32) DEFAULT NULL COMMENT 'wifi登录密码',
  `status_sale` tinyint(4) DEFAULT '0' COMMENT '出售状态：0 未出售，1已出售',
  `time` datetime DEFAULT NULL COMMENT '开通时间',
  `expiry_day` int(10) DEFAULT NULL COMMENT '有效期限(单位：天)',
  PRIMARY KEY (`wifi_info_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='wifi套餐信息表';

-- ----------------------------
--  Records of `wifi_info`
-- ----------------------------
BEGIN;
INSERT INTO `wifi_info` VALUES ('1', '1', '123456', '123456', '1', '2016-05-16 17:04:59', '1000'), ('2', '1', '222', '222', '1', '2016-05-16 06:08:05', '1000'), ('4', '6', '123', '123', '1', '2016-05-16 07:13:27', '1000'), ('6', '2', '22', '22', '1', '2016-05-16 17:42:19', '1000'), ('8', '4', '33', '33', '1', '2016-05-16 17:42:24', '1000');
COMMIT;

-- ----------------------------
--  Table structure for `wifi_item`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_item`;
CREATE TABLE `wifi_item` (
  `wifi_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sale_price` float DEFAULT '0' COMMENT 'wifi价格 单位：$',
  `wifi_flow` int(10) DEFAULT '0' COMMENT 'wifi流量 单位:M',
  `status` tinyint(4) DEFAULT '0' COMMENT '是否停用 ：0可用 1停用',
  PRIMARY KEY (`wifi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='wifi套餐类型表';

-- ----------------------------
--  Records of `wifi_item`
-- ----------------------------
BEGIN;
INSERT INTO `wifi_item` VALUES ('1', '50', '50', '0'), ('2', '100', '100', '0'), ('4', '12.3', '1', '0'), ('6', '12.34', '121', '0');
COMMIT;

-- ----------------------------
--  Table structure for `wifi_item_language`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_item_language`;
CREATE TABLE `wifi_item_language` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wifi_id` int(10) DEFAULT NULL COMMENT 'wifi id',
  `wifi_name` varchar(100) DEFAULT NULL COMMENT 'wifi名字',
  `iso` varchar(20) DEFAULT 'zh_cn' COMMENT '中英文标:zh_cn,en',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='wifi套餐语言表';

-- ----------------------------
--  Records of `wifi_item_language`
-- ----------------------------
BEGIN;
INSERT INTO `wifi_item_language` VALUES ('1', '1', '套餐一', 'zh_cn'), ('2', '2', '套餐二', 'zh_cn'), ('4', '4', '1', 'zh_cn'), ('6', '6', '12', 'zh_cn');
COMMIT;

-- ----------------------------
--  Table structure for `wifi_item_status`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_item_status`;
CREATE TABLE `wifi_item_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `passport_num` varchar(50) DEFAULT NULL COMMENT '支付人的passport number',
  `wifi_info_id` int(10) DEFAULT NULL COMMENT 'wifi_info_id',
  `pay_log_id` int(10) DEFAULT NULL COMMENT 'pay_log_id',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态，0可用 1耗尽',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='wifi套餐使用状态表';

-- ----------------------------
--  Records of `wifi_item_status`
-- ----------------------------
BEGIN;
INSERT INTO `wifi_item_status` VALUES ('2', '123456', '1', '2', '0'), ('4', '123456', '2', '4', '0'), ('6', '123456', '4', '6', '0'), ('8', '123456', '6', '8', '0'), ('10', '123456', '8', '10', '0');
COMMIT;

-- ----------------------------
--  Table structure for `wifi_last_connect`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_last_connect`;
CREATE TABLE `wifi_last_connect` (
  `id` int(11) unsigned NOT NULL,
  `wifi_info_id` int(11) DEFAULT NULL,
  `card_number` varchar(25) DEFAULT NULL,
  `card_password` varchar(25) DEFAULT NULL,
  `passport_number` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='游客最近连接的一张wifi卡号';

-- ----------------------------
--  Table structure for `wifi_pay_log`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_pay_log`;
CREATE TABLE `wifi_pay_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `check_num` varchar(50) DEFAULT NULL COMMENT '订单流水号',
  `passport_num` varchar(50) DEFAULT NULL COMMENT '支付人的passport number',
  `name` varchar(100) DEFAULT NULL COMMENT '支付人的姓名',
  `amount` int(10) DEFAULT '0' COMMENT '支付金额',
  `pay_time` datetime DEFAULT NULL COMMENT '支付时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='购买记录表';

-- ----------------------------
--  Records of `wifi_pay_log`
-- ----------------------------
BEGIN;
INSERT INTO `wifi_pay_log` VALUES ('2', 'EE2016282990063227', '123456', '张三', '50', '2016-04-14 06:04:59'), ('4', 'EE2016284851481935', '123456', '张三', '50', '2016-04-14 06:08:05'), ('6', 'EE2016324067211742', '123456', 'zhangsan', '50', '2016-04-14 07:13:27');
COMMIT;

-- ----------------------------
--  Table structure for `wifi_url_params`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_url_params`;
CREATE TABLE `wifi_url_params` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `url` text COMMENT '路径配置表',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `wifi_url_params`
-- ----------------------------
BEGIN;
INSERT INTO `wifi_url_params` VALUES ('1', 'ibs_request_url', 'http://172.16.2.218:9560/', 'ibs请求地址'), ('2', 'flow_url', 'http://172.16.149.20:8080/fms/ws/queryFlowInfo', '查询流量地址'), ('3', 'request_url', 'http://172.16.149.56:8080/', '重定向返回的地址'), ('4', 'portal_url', 'http://172.16.149.56:8080/cgi-bin/index.cgi', 'portal认证的地址'), ('5', '304_url', 'http://www.baidu.com', 'wifi认证对接不上时重定向到portal认证界面');
COMMIT;

-- ----------------------------
--  Table structure for `wifi_wlan_params`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_wlan_params`;
CREATE TABLE `wifi_wlan_params` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `params_key` varchar(50) DEFAULT NULL,
  `params_value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='认证时的参数';

-- ----------------------------
--  Records of `wifi_wlan_params`
-- ----------------------------
BEGIN;
INSERT INTO `wifi_wlan_params` VALUES ('1', 'nacid', '6D6B0EEF-7C77-4432-991C-79DC76A96788'), ('2', 'wlanacip', '10.9.47.2'), ('3', 'wlanusermac', '58-69-6C-6D-E0-19'), ('4', 'wlanusersn', '336077731'), ('5', 'wlanuserfirsturl', null);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
