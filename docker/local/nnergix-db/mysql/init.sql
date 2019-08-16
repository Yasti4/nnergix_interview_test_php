CREATE DATABASE /*!32312 IF NOT EXISTS*/ `nnergix_crawler` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `nnergix_crawler`;

DROP USER 'app'@'%';
CREATE USER 'app'@'%' IDENTIFIED BY 'test';
GRANT ALL PRIVILEGES ON nnergix_crawler.* TO 'app'@'%' WITH GRANT OPTION;
