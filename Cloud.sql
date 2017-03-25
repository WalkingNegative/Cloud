drop database Cloud;
create database Cloud;

use Cloud;

SET SQL_SAFE_UPDATES = 0;
 
CREATE TABLE `Users` (
    `id_user` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(20) NOT NULL,
    `surname` VARCHAR(20) NOT NULL,
    `email` VARCHAR(30) NOT NULL,
    `password` CHAR(255) NOT NULL
);

CREATE TABLE `Files` (
    `id_file` INT AUTO_INCREMENT PRIMARY KEY,
    `file_name` VARCHAR(255) NOT NULL,
    `path` VARCHAR(500) NOT NULL,
    `id_user` INT NOT NULL,
    `size` DOUBLE NOT NULL,
    `access` varchar(7) not null,
    FOREIGN KEY (id_user)
        REFERENCES Users (id_user)
        ON DELETE CASCADE
);

CREATE TABLE `Sessions` ( 
  `id_session` tinytext NOT NULL, 
  `putdate` datetime NOT NULL default '0000-00-00 00:00:00', 
  `id_user` tinytext NOT NULL 
);

select * from Users;

select * from Files;

select * from Sessions;