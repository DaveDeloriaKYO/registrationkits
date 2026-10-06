-- NID Administration quarterly workflow migration
-- Safe to run on the existing `nid` database. No existing data is deleted.
CREATE TABLE IF NOT EXISTS personnel_quarterly_history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  personnel_id INT NOT NULL,
  quarter ENUM('Q1','Q2','Q3','Q4') NOT NULL,
  year YEAR(4) NOT NULL,
  position VARCHAR(100) NOT NULL,
  municipality VARCHAR(100) DEFAULT NULL,
  regno INT DEFAULT NULL,
  contract_start_date DATE NOT NULL,
  contract_end_date DATE NOT NULL,
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_personnel_quarter (personnel_id, quarter, year),
  CONSTRAINT fk_pqh_personnel FOREIGN KEY (personnel_id) REFERENCES personnel_info(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS personnel_remarks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  personnel_id INT NOT NULL,
  remark TEXT NOT NULL,
  created_by VARCHAR(100) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_personnel_remarks_personnel (personnel_id),
  CONSTRAINT fk_personnel_remarks_personnel FOREIGN KEY (personnel_id) REFERENCES personnel_info(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Optional data-quality indexes; run only if these indexes do not already exist.
-- CREATE INDEX idx_personnel_name ON personnel_info(last_name, first_name);
-- CREATE INDEX idx_personnel_regno ON personnel_info(regno);
