/* create users table */
CREATE TABLE `web_app`.`users` (
    `user_id` INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(20) NOT NULL,
    `email` VARCHAR(20) NOT NULL,
    `password` VARCHAR(50) NOT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME DEFAULT 0,
    `is_locked` TINYINT DEFAULT 0,
    `failure_count` INT(10) DEFAULT 0,
    `unlocked_at` DATETIME DEFAULT 0
) ENGINE=InnoDB;

/* create capture_data table */
CREATE TABLE `web_app`.`capture_data` (
    `data_id` INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT(10) NOT NULL,
    `data_name` VARCHAR(255) NOT NULL,
    `data_summary` VARCHAR(255) NOT NULL,
    `created_at` DATETIME NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    FOREIGN KEY(`user_id`) REFERENCES `users`(`user_id`)
) ENGINE=InnoDB;

/* create vendors table */
CREATE TABLE `web_app`.`vendors` (
    `vendor_id` INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `mac_address` VARCHAR(20) NOT NULL,
    `vendor_name` VARCHAR(255) DEFAULT 'other'
) ENGINE=InnoDB;

/* create protocols table */
CREATE TABLE `web_app`.`protocols` (
    `protocol_id` INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `port_num` INT(10) NOT NULL,
    `protocol_name` VARCHAR(255) DEFAULT 'unknown'
) ENGINE=InnoDB;

/* create global_headers table */
CREATE TABLE `web_app`.`global_headers` (
    `global_header_id` INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `data_id` INT(10) NOT NULL,
    `global_header` VARBINARY(50) NOT NULL,
    FOREIGN KEY(`data_id`) REFERENCES `capture_data`(`data_id`)
) ENGINE=InnoDB;

/* create packet_data table */
CREATE TABLE `web_app`.`packet_data` (
    `packet_data_id` INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `data_id` INT(10) NOT NULL,
    `global_header_id` INT(10) NOT NULL,
    `packet_data` VARBINARY(1500) NOT NULL,
    FOREIGN KEY(`data_id`) REFERENCES `capture_data`(`data_id`),
    FOREIGN KEY(`global_header_id`) REFERENCES `global_headers`(`global_header_id`)
) ENGINE=InnoDB;

/* insert into users */
INSERT INTO `web_app`.`users` (`name`, `email`, `password`, `created_at`) VALUES('test', 'test@test.com', 'passwd', NOW());