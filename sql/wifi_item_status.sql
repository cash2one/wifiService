-- ----------------------------
-- Table structure for wifi_item_status
-- ----------------------------
DROP TABLE IF EXISTS `wifi_item_status`;
CREATE TABLE `wifi_item_status` (
  	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  	`passport_num` varchar(50) DEFAULT NULL COMMENT '支付人的passport number',
  	`wifi_info_id`	int(10) DEFAULT NULL COMMENT 'wifi_info_id',
  	`pay_log_id` int(10) DEFAULT NULL COMMENT  'pay_log_id',
  	`status` tinyint(4) DEFAULT '0' COMMENT '状态，0可用 1耗尽',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='IBS交互记录表';