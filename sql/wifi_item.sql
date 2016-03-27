-- ----------------------------
-- Table structure for wifi_item
-- ----------------------------
DROP TABLE IF EXISTS `wifi_item`;
CREATE TABLE `wifi_item` (
	`wifi_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  	`sale_price` int(10) DEFAULT 0 COMMENT 'wifi价格',
  	`wifi_flow` int(10) DEFAULT 0 COMMENT 'wifi流量 单位:M',
	PRIMARY KEY (`wifi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='wifi套餐类型表';
