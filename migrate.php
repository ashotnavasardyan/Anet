<?php
    $db = mysqli_connect('localhost','root','');
    $create_db_sql = "CREATE DATABASE IF NOT EXISTS network";
    mysqli_query($db,$create_db_sql);
    $db = mysqli_connect('localhost','root','','network');
    $create_users_table_sql = "CREATE TABLE IF NOT EXISTS `network`.`users` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(256) NULL , `password` TEXT NULL ,`image`VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user.png',`last_action` TIMESTAMP DEFAULT NULL, PRIMARY KEY (`id`))ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci;;";
    mysqli_query($db,$create_users_table_sql);
    $create_posts_table_sql = "CREATE TABLE IF NOT EXISTS `network`.`posts` ( `id` INT NOT NULL AUTO_INCREMENT , `title` VARCHAR(256) NULL , `content` TEXT NULL , `image` TEXT NULL , `user_id` INT NOT NULL , PRIMARY KEY (`id`))ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci;;";
    mysqli_query($db,$create_posts_table_sql);
    $create_messages_table_sql = "CREATE TABLE IF NOT EXISTS `network`.`messages` ( `id` INT NOT NULL AUTO_INCREMENT , `message` TEXT NOT NULL , `send_id` INT(5) NOT NULL , `get_id` INT(5) NOT NULL,`media` TEXT DEFAULT NULL,`seen` INT(1) NOT NULL DEFAULT '0',`time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`))ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci;;";
    mysqli_query($db,$create_messages_table_sql);
    $blocked_users_table_sql = "CREATE TABLE `network`.`blocked_users` ( `id` INT NOT NULL AUTO_INCREMENT , `blocked_by_user` INT NOT NULL , `blocked_user` INT NOT NULL , `date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
    mysqli_query($db,$blocked_users_table_sql);
?>