CREATE TABLE `yimei_love` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `phone` varchar(255) NOT NULL COMMENT '电话号码',
  `nickname` varchar(255) NOT NULL COMMENT '表白者',
  `content` text NOT NULL COMMENT '表白内容',
  `lover` varchar(255) NOT NULL COMMENT '表白对象',
  `cTime` int(10) NOT NULL COMMENT '发布时间',
  `uid` varchar(255) NOT NULL COMMENT '用户UI',
  `token` varchar(255) NOT NULL COMMENT 'Token',
  `state` int(10) NOT NULL DEFAULT '2' COMMENT '表白状态',
  `reply` text NOT NULL COMMENT '对象反馈',
  `favourid` varchar(255) NOT NULL COMMENT '点赞数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


INSERT INTO `wp_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`) VALUES ('love','表白墙','0','','1','{"1":["cTime","lover","content","nickname","phone","reply"]}','1:基础','','','','','lover:对象名称\r\nphone:对象手机号\r\ncontent:表白内容\r\nnickname:表白者\r\ncTime|time_format:表白时间\r\nreply:对象反馈\r\nid:操作:[EDIT]&id=[id]|编辑,[DELETE]&id=[id]|删除','10','lover','','1405751672','1406353574','1','MyISAM');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('phone','电话号码','varchar(255) NOT NULL','string','','','1','','0','0','1','1406349162','1406349162','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('nickname','表白者','varchar(255) NOT NULL','string','','','1','','0','0','1','1405751850','1405751850','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('content','表白内容','text NOT NULL','textarea','','','1','','0','0','1','1405751825','1405751825','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('lover','表白对象','varchar(255) NOT NULL','string','','','1','','0','0','1','1405751804','1405751804','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('cTime','发布时间','int(10) NOT NULL','datetime','','','1','','0','0','1','1405751773','1405751773','','3','','regex','time','1','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('uid','用户UI','varchar(255) NOT NULL','string','','','0','','0','0','1','1405751742','1405751742','','3','','regex','get_mid','1','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','Token','varchar(255) NOT NULL','string','','','0','','0','0','1','1405752400','1405751694','','3','','regex','get_token','1','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('state','表白状态','int(10) NOT NULL','radio','2','','1','0:表白失败\r\n1:表白成功\r\n2:表白ing','0','0','1','1406353931','1406353472','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('reply','对象反馈','text NOT NULL','textarea','','','1','','0','0','1','1406353506','1406353506','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('favourid','点赞数','varchar(255) NOT NULL','string','','','0','','0','0','1','1406814622','1406814622','','3','','regex','','3','function');
UPDATE `wp_attribute` SET model_id= (SELECT MAX(id) FROM `wp_model`) WHERE model_id=0;


CREATE TABLE IF NOT EXISTS `wp_love_comment` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`token`  varchar(255) NOT NULL  COMMENT 'Token',
`uid`  varchar(255) NOT NULL  COMMENT '用户ID',
`content`  text NOT NULL  COMMENT '评论内容',
`cTime`  int(10) NOT NULL  COMMENT '创建时间',
`loveId`  int(10) NOT NULL  COMMENT '表白内容关联ID',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

INSERT INTO `wp_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`) VALUES ('love_comment','表白评论','0','','1','{"1":["content","cTime"]}','1:基础','','','','','content:评论内容\r\ncTime|time_format:创建时间\r\nid:操作:[EDIT]&id=[id]|编辑,[DELETE]&id=[id]|删除','10','content','','1406721078','1406732056','1','MyISAM');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','Token','varchar(255) NOT NULL','string','','','0','','0','0','1','1406730452','1406730402','','3','','regex','get_token','1','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('uid','用户ID','varchar(255) NOT NULL','string','','','0','','0','0','1','1406730435','1406730435','','3','','regex','get_mid','1','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('content','评论内容','text NOT NULL','textarea','','','1','','0','0','1','1406730536','1406730536','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('cTime','创建时间','int(10) NOT NULL','datetime','','','1','','0','0','1','1406730562','1406730562','','3','','regex','time','1','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('loveId','表白内容关联ID','int(10) NOT NULL','num','','','0','','0','0','1','1406730610','1406730610','','3','','regex','','3','function');
UPDATE `wp_attribute` SET model_id= (SELECT MAX(id) FROM `wp_model`) WHERE model_id=0;

CREATE TABLE IF NOT EXISTS `wp_love` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`phone`  varchar(255) NOT NULL  COMMENT '电话号码',
`nickname`  varchar(255) NOT NULL  COMMENT '表白者',
`content`  text NOT NULL  COMMENT '表白内容',
`lover`  varchar(255) NOT NULL  COMMENT '表白对象',
`cTime`  int(10) NOT NULL  COMMENT '发布时间',
`uid`  varchar(255) NOT NULL  COMMENT '用户UI',
`token`  varchar(255) NOT NULL  COMMENT 'Token',
`state`  int(10) NOT NULL  DEFAULT 2 COMMENT '表白状态',
`reply`  text NOT NULL  COMMENT '对象反馈',
`favourid`  varchar(255) NOT NULL  COMMENT '点赞数',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

INSERT INTO `wp_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`) VALUES ('love','表白墙','0','','1','{"1":["cTime","lover","content","nickname","phone","reply"]}','1:基础','','','','','lover:对象名称\r\nphone:对象手机号\r\ncontent:表白内容\r\nnickname:表白者\r\ncTime|time_format:表白时间\r\nreply:对象反馈\r\nid:操作:[EDIT]&id=[id]|编辑,[DELETE]&id=[id]|删除','10','lover','','1405751672','1406353574','1','MyISAM');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('phone','电话号码','varchar(255) NOT NULL','string','','','1','','0','0','1','1406349162','1406349162','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('nickname','表白者','varchar(255) NOT NULL','string','','','1','','0','0','1','1405751850','1405751850','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('content','表白内容','text NOT NULL','textarea','','','1','','0','0','1','1405751825','1405751825','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('lover','表白对象','varchar(255) NOT NULL','string','','','1','','0','0','1','1405751804','1405751804','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('cTime','发布时间','int(10) NOT NULL','datetime','','','1','','0','0','1','1405751773','1405751773','','3','','regex','time','1','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('uid','用户UI','varchar(255) NOT NULL','string','','','0','','0','0','1','1405751742','1405751742','','3','','regex','get_mid','1','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','Token','varchar(255) NOT NULL','string','','','0','','0','0','1','1405752400','1405751694','','3','','regex','get_token','1','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('state','表白状态','int(10) NOT NULL','radio','2','','1','0:表白失败\r\n1:表白成功\r\n2:表白ing','0','0','1','1406353931','1406353472','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('reply','对象反馈','text NOT NULL','textarea','','','1','','0','0','1','1406353506','1406353506','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('favourid','点赞数','varchar(255) NOT NULL','string','','','0','','0','0','1','1406814622','1406814622','','3','','regex','','3','function');
UPDATE `wp_attribute` SET model_id= (SELECT MAX(id) FROM `wp_model`) WHERE model_id=0;