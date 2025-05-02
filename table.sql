--
-- Table structure for calendar module integration
--

CREATE TABLE IF NOT EXISTS `mod_calendar_sync` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) NOT NULL,
  `appointment_id` bigint(20) NOT NULL,
  `external_calendar_id` varchar(255) DEFAULT NULL,
  `external_event_id` varchar(255) DEFAULT NULL,
  `last_sync` datetime DEFAULT CURRENT_TIMESTAMP,
  `sync_status` enum('pending', 'synced', 'failed') DEFAULT 'pending',
  `error_message` text,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `appointment_id` (`appointment_id`)
) ENGINE=InnoDB;

--
-- Table structure for calendar configuration
--

CREATE TABLE IF NOT EXISTS `mod_calendar_config` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `config_key` varchar(255) NOT NULL,
  `config_value` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `config_key` (`config_key`)
) ENGINE=InnoDB;
