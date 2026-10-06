-- Tracks a quarterly assignment record per personnel, instead of
-- overwriting personnel_info on every renewal. personnel_info stays
-- the "master" record (name, contact, email); each quarter's
-- position/municipality/reg-kit/contract dates/status live here,
-- one row per personnel per quarter.

CREATE TABLE IF NOT EXISTS `personnel_quarterly_history` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `personnel_id` INT NOT NULL,
  `quarter` ENUM('Q1','Q2','Q3','Q4') NOT NULL,
  `year` YEAR(4) NOT NULL,
  `position` VARCHAR(100) NOT NULL,
  `municipality` VARCHAR(100) DEFAULT NULL,
  `regno` INT DEFAULT NULL,
  `contract_start_date` DATE NOT NULL,
  `contract_end_date` DATE NOT NULL,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_personnel_quarter` (`personnel_id`, `quarter`, `year`),
  CONSTRAINT `fk_pqh_personnel`
    FOREIGN KEY (`personnel_id`) REFERENCES `personnel_info` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- The unique key means the same personnel can't get two rows for the
-- same quarter/year by mistake — trying to insert a duplicate should
-- be caught in PHP and shown as "already has a record for that
-- quarter" (edit the existing one instead of adding another).
