CREATE DATABASE register;

USE register;

CREATE TABLE students (
    id INT(11)  AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    userpass VARCHAR(255) NOT NULL,
    userrole ENUM('regular', 'admin', 'superuser') NOT NULL DEFAULT 'regular',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);