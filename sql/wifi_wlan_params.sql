-- ----------------------------
-- Table structure for wifi_wlan_params
-- ----------------------------
DROP TABLE IF EXISTS `wifi_wlan_params`;
CREATE TABLE `wifi_wlan_params` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  	`wifi_code` varchar(50) DEFAULT NULL COMMENT 'wifi登录帐号',
  	`wlanuserip` varchar(50) DEFAULT NULL COMMENT '',
	`wlanacip` varchar(50) DEFAULT NULL COMMENT '',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='wifi注销时用到的参数';
