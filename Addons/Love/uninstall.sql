DELETE FROM `wp_attribute` WHERE model_id = (SELECT id FROM wp_model WHERE `name`='love' ORDER BY id DESC LIMIT 1);
DELETE FROM `wp_model` WHERE `name`='love' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_love`;

DELETE FROM `wp_attribute` WHERE model_id = (SELECT id FROM wp_model WHERE `name`='love_comment' ORDER BY id DESC LIMIT 1);
DELETE FROM `wp_model` WHERE `name`='love_comment' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_love_comment`;

DELETE FROM `wp_attribute` WHERE model_id = (SELECT id FROM wp_model WHERE `name`='love' ORDER BY id DESC LIMIT 1);
DELETE FROM `wp_model` WHERE `name`='love' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_love`;