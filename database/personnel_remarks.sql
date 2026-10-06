CREATE TABLE IF NOT EXISTS `personnel_remarks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `personnel_id` INT NOT NULL,
  `quarterly_history_id` INT NULL,
  `remark` TEXT NOT NULL,
  `created_by` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_personnel_remarks_personnel` (`personnel_id`),
  KEY `idx_personnel_remarks_quarter` (`quarterly_history_id`),
  CONSTRAINT `fk_personnel_remarks_personnel` FOREIGN KEY (`personnel_id`) REFERENCES `personnel_info` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_personnel_remarks_quarter` FOREIGN KEY (`quarterly_history_id`) REFERENCES `personnel_quarterly_history` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
