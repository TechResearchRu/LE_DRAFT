--Access level control and accounts
CREATE TABLE `sys__accounts` 
( 
    `id` INT(8) NOT NULL AUTO_INCREMENT ,  
    `login` VARCHAR(50) NOT NULL ,  
    `password` VARCHAR(32) NOT NULL ,  
    `level` INT(2) NOT NULL DEFAULT '0' ,  
    `data` JSON NOT NULL DEFAULT '{}' ,    
    PRIMARY KEY  (`id`)
) 
ENGINE = InnoDB CHARSET=utf8 
COLLATE utf8_unicode_ci COMMENT = 'Aккаунты системы';

--add first account
INSERT INTO `sys__accounts` (`id`, `login`, `password`, `level`, `data`) VALUES (NULL, 'admin', MD5('123123'), '99', '{}'); 