DROP TABLE IF EXISTS `t_channel`;
CREATE TABLE `t_channel` (
  `f_id` int(11) NOT NULL AUTO_INCREMENT,
  `f_name` text NOT NULL,
  `f_os` int(11) NOT NULL,
  `f_ios_version` int(11) NOT NULL,
  `f_android_version` int(11) NOT NULL,
  PRIMARY KEY (`f_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
INSERT INTO `t_channel` VALUES (1,'内测版本','3','0','0'),(998,'公测版本','3','0','0');

