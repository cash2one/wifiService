-- ----------------------------
-- Table structure for wifi_item_language
-- ----------------------------
DROP TABLE IF EXISTS `wifi_item_language`;
CREATE TABLE `wifi_item_language` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`wifi_id` int(10)  DEFAULT NULL ,
  	`wifi_name` varchar(100) DEFAULT NULL COMMENT 'wifi名字',
  	`iso` varchar(20) DEFAULT 'zh_cn' COMMENT '中英文标:zh_cn,en',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='wifi套餐语言表';
