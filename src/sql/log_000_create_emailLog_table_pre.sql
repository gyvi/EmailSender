--
-- emailLog create table
--

CREATE TABLE IF NOT EXISTS `emailLog` (
  `emailLogId` bigint(20) NOT NULL AUTO_INCREMENT,
  `composedEmailId` bigint(20) NOT NULL,
  `from` varchar(255) NOT NULL,
  `recipients` json NOT NULL,
  `subject` varchar(77) NOT NULL,
  `logged` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `queued` datetime DEFAULT NULL,
  `sent` datetime DEFAULT NULL,
  `delay` int(11) NOT NULL DEFAULT '0',
  `status` enum('-1','0','1','2') NOT NULL DEFAULT '0',
  `errorMessage` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`emailLogId`),
  KEY `composedEmailId` (`composedEmailId`),
  KEY `from` (`from`),
  KEY `queued` (`queued`),
  KEY `sent` (`sent`),
  KEY `status` (`status`),
  KEY `logged` (`logged`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Rollback:
--
-- DROP TABLE `emailLog`;
