-- Quarterly Personnel Remarks Migration
-- Run this after personnel_quarterly_history exists.
-- Existing remarks are preserved. Legacy remarks remain visible as General / Legacy
-- until they are optionally assigned to a quarterly record.

SET @has_qid := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'personnel_remarks'
    AND COLUMN_NAME = 'quarterly_history_id'
);
SET @sql := IF(@has_qid = 0,
  'ALTER TABLE personnel_remarks ADD COLUMN quarterly_history_id INT NULL AFTER personnel_id',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @has_idx := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'personnel_remarks'
    AND INDEX_NAME = 'idx_personnel_remarks_quarter'
);
SET @sql := IF(@has_idx = 0,
  'ALTER TABLE personnel_remarks ADD INDEX idx_personnel_remarks_quarter (quarterly_history_id)',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @has_fk := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
  WHERE CONSTRAINT_SCHEMA = DATABASE()
    AND TABLE_NAME = 'personnel_remarks'
    AND CONSTRAINT_NAME = 'fk_personnel_remarks_quarter'
);
SET @sql := IF(@has_fk = 0,
  'ALTER TABLE personnel_remarks ADD CONSTRAINT fk_personnel_remarks_quarter FOREIGN KEY (quarterly_history_id) REFERENCES personnel_quarterly_history(id) ON DELETE SET NULL',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- New remarks should always have quarterly_history_id.
-- Existing NULL values are intentionally retained as General / Legacy.
