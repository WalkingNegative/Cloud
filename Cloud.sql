drop database Cloud;
create database Cloud;

use Cloud;

create table Users(
id_user int auto_increment primary key,
email varchar(30) not null,
pas varchar(255) not null);


CREATE TABLE Files (
    id_file INT AUTO_INCREMENT PRIMARY KEY,
    file_name VARCHAR(255) NOT NULL,
    path VARCHAR(500) NOT NULL,
    id_user INT NOT NULL,
    size DOUBLE NOT NULL,
    FOREIGN KEY (id_user)
        REFERENCES Users (id_user)
        ON DELETE CASCADE
);

select * from Users;

select * from Files;


