drop database Cloud;
create database Cloud;

use Cloud;

SET SQL_SAFE_UPDATES = 0;

CREATE TABLE `Users` (
    `id_user` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(30) NOT NULL,
    `pas` CHAR(255) NOT NULL
);

CREATE TABLE `Loginhash` (
    `ip` VARCHAR(15) NOT NULL,
    `hash` VARCHAR(40) NOT NULL,
    `deadline` DATETIME NOT NULL
) ENGINE=MEMORY;

CREATE TABLE `Files` (
    `id_file` INT AUTO_INCREMENT PRIMARY KEY,
    `file_name` VARCHAR(255) NOT NULL,
    `path` VARCHAR(500) NOT NULL,
    `id_user` INT NOT NULL,
    `size` DOUBLE NOT NULL,
    FOREIGN KEY (id_user)
        REFERENCES Users (id_user)
        ON DELETE CASCADE
);


