-- Run once to set up the database:  mysql -u <user> -p < database.sql
-- NOTE: columns are based on create.php (name, email, course). If edit.php or
-- index.php use extra columns, add them here.

CREATE DATABASE IF NOT EXISTS php_crud
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE php_crud;

CREATE TABLE IF NOT EXISTS students (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100) NOT NULL,
  email      VARCHAR(150) NOT NULL,
  course     VARCHAR(100) NOT NULL,
  created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
