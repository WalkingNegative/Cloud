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
    user_token_id INT PRIMARY KEY,
    user_id       INT         NOT NULL,
    token         VARCHAR(64) NOT NULL,
    valid_to      DATETIME    NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user (user_id) ON DELETE CASCADE
);

CREATE TABLE client
(
    client_id  INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT         NOT NULL,
    first_name VARCHAR(50) NULL DEFAULT NULL,
    last_name  VARCHAR(50) NULL DEFAULT NULL,
    country    VARCHAR(50) NULL DEFAULT NULL,
    city       VARCHAR(50) NULL DEFAULT NULL,
    birth      DATE        NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES user (user_id) ON DELETE CASCADE

);

CREATE TABLE operator
(
    operator_id       INT AUTO_INCREMENT PRIMARY KEY,
    user_id           INT         NOT NULL,
    first_name        VARCHAR(50) NULL     DEFAULT NULL,
    last_name         VARCHAR(50) NULL     DEFAULT NULL,
    is_active         BOOL        NOT NULL DEFAULT TRUE,
    registration_time DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user (user_id) ON DELETE CASCADE
);

CREATE TABLE user_photo
(
    photo_id  INT AUTO_INCREMENT PRIMARY KEY,
    user_id   INT      NOT NULL,
    photo_url TEXT     NOT NULL,
    load_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user (user_id) ON DELETE CASCADE
);

CREATE TABLE file
(
    file_id    INT AUTO_INCREMENT PRIMARY KEY,
    front_id   VARCHAR(8)   NOT NULL,
    user_id    INT          NOT NULL,
    name       VARCHAR(256) NOT NULL,
    size       VARCHAR(10)  NOT NULL,
    path       TEXT         NOT NULL,
    load_time  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user (user_id) ON DELETE CASCADE
);

CREATE TABLE public_file
(
    file_id    INT AUTO_INCREMENT PRIMARY KEY,
    front_id   VARCHAR(8)   NOT NULL,
    user_id    INT          NOT NULL,
    name       VARCHAR(256) NOT NULL,
    size       VARCHAR(10)  NOT NULL,
    path       TEXT         NOT NULL,
    load_time  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user (user_id) ON DELETE CASCADE
);

CREATE TABLE action
(
    action_id   INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT         NOT NULL,
    type        VARCHAR(50) NOT NULL,
    description TEXT        NULL     DEFAULT NULL,
    action_time DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user (user_id) ON DELETE CASCADE
);

CREATE TABLE blocked_users
(
    blocked_users_id   INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT      NOT NULL,
    reason     TEXT     NULL     DEFAULT NULL,
    block_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user (user_id) ON DELETE CASCADE
);

CREATE TABLE login
(
    login_id   INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT         NOT NULL,
    login_time DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user (user_id) ON DELETE CASCADE
);
