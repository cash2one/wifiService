-- ----------------------------
-- Table structure for wifi_item
-- ----------------------------
DROP TABLE IF EXISTS `wifi_item`;
CREATE TABLE `wifi_item` (
	`wifi_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  	`sale_price` int(10) DEFAULT 0 COMMENT 'wifi价格 单位：$',
  	`wifi_flow` int(10) DEFAULT 0 COMMENT 'wifi流量 单位:M',
  	`status` tinyint(4) DEFAULT 0 COMMENT '是否停用 ：0可用 1停用',
	PRIMARY KEY (`wifi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='wifi套餐类型表';
