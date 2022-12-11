CREATE DATABASE IF NOT EXISTS appDB;
CREATE USER IF NOT EXISTS 'user'@'%' IDENTIFIED BY 'password';
GRANT SELECT,UPDATE,INSERT ON appDB.* TO 'user'@'%';
FLUSH PRIVILEGES;

USE appDB;
CREATE TABLE IF NOT EXISTS users (
  ID INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(20) NOT NULL,
  count INT,
  score INT,
  sum_score INT,
  PRIMARY KEY (ID)
);

INSERT INTO users (name, count, score, sum_score) VALUES ('admin', 0, 0, 0);

-- CREATE TABLE IF NOT EXISTS flag (
--   name VARCHAR(20),
--   type BOOLEAN
-- );

-- INSERT INTO flag (name, type) VALUES ("admin", false);