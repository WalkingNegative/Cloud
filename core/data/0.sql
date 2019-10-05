CREATE TABLE user
(
    user_id           INT AUTO_INCREMENT PRIMARY KEY,
    front_id          VARCHAR(8)   NOT NULL,
    email             VARCHAR(256) NOT NULL,
    password          VARCHAR(256) NOT NULL,
    registration_time DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_token
(
    user_id  INT PRIMARY KEY,
    token    VARCHAR(64) NOT NULL,
    valid_to DATETIME    NOT NULL
);

CREATE TABLE client
(
    user_id    INT         NOT NULL,
    first_name VARCHAR(50) NULL DEFAULT NULL,
    last_name  VARCHAR(50) NULL DEFAULT NULL,
    country    VARCHAR(50) NULL DEFAULT NULL,
    city       VARCHAR(50) NULL DEFAULT NULL,
    birth      DATE        NULL DEFAULT NULL
);

CREATE TABLE operator
(
    user_id           INT         NOT NULL,
    first_name        VARCHAR(50) NULL     DEFAULT NULL,
    last_name         VARCHAR(50) NULL     DEFAULT NULL,
    birth             DATE        NULL     DEFAULT NULL,
    registration_time DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_photo
(
    photo_id  INT AUTO_INCREMENT PRIMARY KEY,
    user_id   INT      NOT NULL,
    photo_url TEXT     NOT NULL,
    load_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE file
(
    file_id    INT AUTO_INCREMENT PRIMARY KEY,
    front_id   VARCHAR(8)   NOT NULL,
    user_id    INT          NOT NULL,
    name       VARCHAR(256) NOT NULL,
    size       VARCHAR(10)  NOT NULL,
    path       TEXT         NOT NULL,
    hash       VARCHAR(256) NOT NULL,
    is_private BOOL         NOT NULL DEFAULT FALSE,
    load_time  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE file_user
(
    file_id   INT NOT NULL,
    owner_id  INT NOT NULL,
    friend_id INT NOT NULL
);

CREATE TABLE black_list
(
    user_id    INT NOT NULL,
    bad_guy_id INT NOT NULL
);

CREATE TABLE action
(
    action_id   INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT         NOT NULL,
    type        VARCHAR(50) NOT NULL,
    description TEXT        NULL     DEFAULT NULL,
    action_time DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE blocked_users
(
    user_id    INT      NOT NULL,
    reason     TEXT     NULL     DEFAULT NULL,
    block_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
