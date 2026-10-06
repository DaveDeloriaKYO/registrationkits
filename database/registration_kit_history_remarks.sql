-- Registration Kit history and remarks
-- Run once against the existing `nid` database.

CREATE TABLE IF NOT EXISTS `registration_kit_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `regno` int(11) NOT NULL,
  `action` varchar(30) NOT NULL,
  `snapshot_json` longtext DEFAULT NULL,
  `changed_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_rkh_regno` (`regno`),
  KEY `idx_rkh_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `registration_kit_remarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `regno` int(11) NOT NULL,
  `remark` text NOT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_rkr_regno` (`regno`),
  KEY `idx_rkr_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
