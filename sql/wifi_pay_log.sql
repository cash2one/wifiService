-- ----------------------------
-- Table structure for wifi_pay_log
-- ----------------------------
DROP TABLE IF EXISTS `wifi_pay_log`;
CREATE TABLE `wifi_pay_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `check_num` varchar(50) DEFAULT NULL COMMENT '订单流水号',
	`passport_num` varchar(50) DEFAULT NULL COMMENT '支付人的passport number',
	`name` varchar(100) DEFAULT NULL COMMENT '支付人的姓名',
	`amount` int(10) DEFAULT 0 COMMENT '支付金额',
	`pay_time` datetime DEFAULT NULL COMMENT '支付时间',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='购买记录表';