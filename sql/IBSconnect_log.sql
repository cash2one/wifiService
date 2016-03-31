-- ----------------------------
-- Table structure for IBSconnect_log
-- ----------------------------
DROP TABLE IF EXISTS `IBSconnect_log`;
CREATE TABLE `IBSconnect_log` (
  	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  	`type` tinyint(4) DEFAULT NULL COMMENT '类型，0:接收 1:发送',
  	`content` text DEFAULT NULL COMMENT 'XML内容',
  	`time` datetime DEFAULT NULL COMMENT '时间',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='IBS交互记录表';