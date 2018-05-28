CREATE TABLE IF NOT EXISTS `#__jsn_easyslider_item_templates` (
	`model_id` INT(11) NOT NULL AUTO_INCREMENT,
	`collection_id` varchar( 30 ) NOT NULL ,
	`name` varchar( 255 ) NOT NULL ,
	`data` text NOT NULL ,
	`type` varchar(15) NOT NULL,
	PRIMARY KEY (`model_id`)
)  CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__jsn_easyslider_slide_templates` (
	`model_id` INT(11) NOT NULL AUTO_INCREMENT,
	`collection_id` varchar( 30 ) NOT NULL ,
	`name` varchar( 255 ) NOT NULL ,
	`data` text NOT NULL ,
	`type` varchar(15) NOT NULL,
	PRIMARY KEY (`model_id`)
)  CHARACTER SET `utf8`;
