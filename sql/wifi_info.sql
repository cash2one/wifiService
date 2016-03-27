-- ----------------------------
-- Table structure for wifi_info
-- ----------------------------
DROP TABLE IF EXISTS `wifi_info`;
CREATE TABLE `wifi_info` (
	`wifi_info_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`wifi_id` int(10)  DEFAULT NULL ,
  	`wifi_code` varchar(50) DEFAULT NULL COMMENT 'wifi登录帐号',
  	`wifi_password` varchar(50) DEFAULT NULL COMMENT 'wifi登录密码',
  	`status_sale` tinyint(4) DEFAULT 0 COMMENT '出售状态：0 未出售，1已出售',
	PRIMARY KEY (`wifi_info_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='wifi套餐信息表';
