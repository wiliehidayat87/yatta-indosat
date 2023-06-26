--
-- Table structure for table `data_admin`
--

CREATE TABLE IF NOT EXISTS `data_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(200) NOT NULL,
  `last_sign_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `insert_by` varchar(50) NOT NULL,
  `insert_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_by` varchar(50) DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

--
-- Dumping data for table `data_admin`
--

INSERT INTO `data_admin` VALUES(null, 'admin', md5('123456'), now(), '', now(), NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `data_content_code_mapping`
--

CREATE TABLE IF NOT EXISTS `data_content_code_mapping` (
  `prefix` varchar(12) NOT NULL,
  `content_type` varchar(50) NOT NULL,
  KEY `prefix` (`prefix`,`content_type`) USING BTREE
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `data_content_price`
--

CREATE TABLE IF NOT EXISTS `data_content_price` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `partner_id` bigint(20) unsigned NOT NULL,
  `content_code` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `partner_id` (`partner_id`,`content_code`),
  KEY `partner_id_2` (`partner_id`) USING BTREE,
  KEY `content_code` (`content_code`) USING BTREE
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `data_partner`
--

CREATE TABLE IF NOT EXISTS `data_partner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(200) NOT NULL,
  `sharing` int(11) NOT NULL,
  `has_access` enum('0','1') DEFAULT '0',
  `last_sign_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `insert_by` varchar(50) NOT NULL,
  `insert_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_by` varchar(50) DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `data_partner_access`
--

CREATE TABLE IF NOT EXISTS `data_partner_access` (
  `partner_id` bigint(20) unsigned NOT NULL,
  `section` varchar(128) NOT NULL,
  UNIQUE KEY `partner_id_2` (`partner_id`,`section`),
  KEY `partner_id` (`partner_id`) USING BTREE
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `data_partner_content`
--

CREATE TABLE IF NOT EXISTS `data_partner_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `mapping_type` enum('content','owner') NOT NULL,
  `pricing_type` enum('free','premium') NOT NULL,
  `insert_by` varchar(50) NOT NULL,
  `insert_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_by` varchar(50) DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_data_partner_content_data_partner` (`partner_id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `data_partner_content_filter`
--

CREATE TABLE IF NOT EXISTS `data_partner_content_filter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_content_id` int(11) NOT NULL,
  `key` varchar(50) NOT NULL,
  `type` enum('service','sid','price') NOT NULL,
  `insert_by` varchar(50) NOT NULL,
  `insert_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_by` varchar(50) DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `data_partner_publisher`
--

CREATE TABLE IF NOT EXISTS `data_partner_publisher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `mapping_type` enum('content','owner') NOT NULL,
  `insert_by` varchar(50) NOT NULL,
  `insert_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_by` varchar(50) DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_data_partner_content_data_partner` (`partner_id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `data_partner_publisher_filter`
--

CREATE TABLE IF NOT EXISTS `data_partner_publisher_filter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_publisher_id` int(11) NOT NULL,
  `key` varchar(50) NOT NULL,
  `type` enum('service','sid','price') NOT NULL,
  `insert_by` varchar(50) NOT NULL,
  `insert_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_by` varchar(50) DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `data_partner_service`
--

CREATE TABLE IF NOT EXISTS `data_partner_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) NOT NULL,
  `service_shortcode` varchar(20) NOT NULL,
  `service_name` varchar(50) NOT NULL,
  `insert_by` varchar(50) NOT NULL,
  `insert_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_by` varchar(50) DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_data_partner_service_data_partner` (`partner_id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `data_partner_service_filter`
--

CREATE TABLE IF NOT EXISTS `data_partner_service_filter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_service_id` int(11) NOT NULL,
  `key` varchar(50) NOT NULL,
  `type` enum('subject','operator','sid','price') NOT NULL,
  `insert_by` varchar(50) NOT NULL,
  `insert_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_by` varchar(50) DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_data_partner_service_filter_data_partner_service` (`partner_service_id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `data_ratio_content`
--

CREATE TABLE IF NOT EXISTS `data_ratio_content` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `partner_content_id` bigint(20) unsigned NOT NULL,
  `start_time` date NOT NULL DEFAULT '0000-00-00',
  `end_time` date NOT NULL DEFAULT '0000-00-00',
  `ratio` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_content_id` (`partner_content_id`) USING BTREE
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `data_ratio_publisher`
--

CREATE TABLE IF NOT EXISTS `data_ratio_publisher` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `partner_publisher_id` bigint(20) unsigned NOT NULL,
  `start_time` date NOT NULL DEFAULT '0000-00-00',
  `end_time` date NOT NULL DEFAULT '0000-00-00',
  `ratio` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_content_id` (`partner_publisher_id`) USING BTREE
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `data_ratio_service`
--

CREATE TABLE IF NOT EXISTS `data_ratio_service` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `partner_service_id` bigint(20) unsigned NOT NULL,
  `start_time` date NOT NULL DEFAULT '0000-00-00',
  `end_time` date NOT NULL DEFAULT '0000-00-00',
  `ratio` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_service_id` (`partner_service_id`) USING BTREE
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `data_reconciliation`
--

CREATE TABLE IF NOT EXISTS `data_reconciliation` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `short_code` varchar(8) NOT NULL,
  `operator` varchar(24) NOT NULL,
  `month` varchar(2) NOT NULL,
  `year` varchar(4) NOT NULL,
  `gross_internal` varchar(25) NOT NULL,
  `gross_operator` varchar(25) NOT NULL,
  `difference_price` varchar(25) NOT NULL,
  `difference_percentage` varchar(25) NOT NULL,
  `partner_percentage` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `short_code` (`short_code`,`operator`,`month`,`year`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `data_reconciliation_mapping`
--

CREATE TABLE IF NOT EXISTS `data_reconciliation_mapping` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `data_reconciliation_id` bigint(20) unsigned NOT NULL,
  `service_id` varchar(255) NOT NULL,
  `traffic_internal` varchar(25) NOT NULL,
  `traffic_operator` varchar(25) NOT NULL,
  `gross_internal` varchar(25) NOT NULL,
  `gross_operator` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `data_reconciliation_id` (`data_reconciliation_id`,`service_id`),
  KEY `data_reconciliation_id_2` (`data_reconciliation_id`) USING BTREE
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS `data_operator_sharing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `short_code` varchar(8) NOT NULL,
  `operator` varchar(32) NOT NULL,
  `sharing` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `short_code` (`short_code`,`operator`),
  KEY `operator` (`operator`) USING BTREE
) ENGINE=InnoDB;

DELIMITER |
CREATE TRIGGER removeService AFTER DELETE ON data_partner_service
  FOR EACH ROW BEGIN
    DELETE FROM data_partner_service_filter WHERE partner_service_id = OLD.id;
  END;
|
DELIMITER ;

DELIMITER |
CREATE TRIGGER removeContent AFTER DELETE ON data_partner_content
  FOR EACH ROW BEGIN
    DELETE FROM data_partner_content_filter WHERE partner_content_id = OLD.id;
  END;
|
DELIMITER ;

DELIMITER |
CREATE TRIGGER removePublisher AFTER DELETE ON data_partner_publisher
  FOR EACH ROW BEGIN
    DELETE FROM data_partner_publisher_filter WHERE partner_publisher_id = OLD.id;
  END;
|
DELIMITER ;


DELIMITER |
CREATE TRIGGER addDefaultContentPrice AFTER INSERT ON data_partner
  FOR EACH ROW BEGIN
    INSERT INTO data_content_price VALUES (0, NEW.id, '1*', 10000);
    INSERT INTO data_content_price VALUES (0, NEW.id, '2*', 5000);
    INSERT INTO data_content_price VALUES (0, NEW.id, '3*', 3000);
    INSERT INTO data_content_price VALUES (0, NEW.id, '4*', 5000);
    INSERT INTO data_content_price VALUES (0, NEW.id, '5*', 8000);
    INSERT INTO data_content_price VALUES (0, NEW.id, '6*', 10000);
    INSERT INTO data_content_price VALUES (0, NEW.id, '7*', 5000);
    INSERT INTO data_content_price VALUES (0, NEW.id, '9*', 5000);
  END;
|
DELIMITER ;

DELIMITER |
CREATE TRIGGER removePartnerItem AFTER DELETE ON data_partner
  FOR EACH ROW BEGIN
    DELETE FROM data_partner_content WHERE partner_id = OLD.id;
    DELETE FROM data_partner_publisher WHERE partner_id = OLD.id;
    DELETE FROM data_partner_service WHERE partner_id = OLD.id;
    DELETE FROM data_content_price WHERE partner_id = OLD.id;
  END;
|
DELIMITER ;

DELIMITER |
CREATE TRIGGER removeReconciliation AFTER DELETE ON data_reconciliation
  FOR EACH ROW BEGIN
    DELETE FROM data_reconciliation_mapping WHERE data_reconciliation_id = OLD.id;
  END;
|
DELIMITER ;