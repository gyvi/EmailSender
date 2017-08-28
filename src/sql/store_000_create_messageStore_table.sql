--
-- messageLog create table
--

CREATE TABLE IF NOT EXISTS `messageStore` (
  `messageId` bigint(20) NOT NULL AUTO_INCREMENT,
  `recipients` json NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`messageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Rollback:
--
-- DROP TABLE `messageStore`;
