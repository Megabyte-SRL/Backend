CREATE DATABASE IF NOT EXISTS `laravel_db`;

CREATE USER 'username'@'localhost' IDENTIFIED BY 'secret';
GRANT ALL PRIVILEGES ON *.* TO 'username'@'%';