CREATE TABLE `settings` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`groups` varchar(128) DEFAULT NULL,
	`keyword` varchar(128) DEFAULT NULL,
	`value` text,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `settings` VALUES 
(NULL,'smtp','host','smtp.mandrillapp.com'),
(NULL,'smtp','username','mandrill-username'),
(NULL,'smtp','password','mandrill-password'),
(NULL,'smtp','port','587'),
(NULL,'smtp','encryption','tls'),
(NULL,'email','noreply','noreply@local.com'),
(NULL,'email','test.receiver','erson.puyos@gmail.com');