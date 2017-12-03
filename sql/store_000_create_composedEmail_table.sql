--
-- composedEmail create table
--

CREATE TABLE IF NOT EXISTS `composedEmail` (
  `composedEmailId` bigint(20) NOT NULL AUTO_INCREMENT,
  `from` varchar(255) NOT NULL,
  `recipients` json NOT NULL,
  `email` text NOT NULL,
  PRIMARY KEY (`composedEmailId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Rollback:
--
-- DROP TABLE `composedEmail`;
