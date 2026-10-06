-- Run once against the existing nid database.
ALTER TABLE peripherals
  ADD COLUMN is_frozen TINYINT(1) NOT NULL DEFAULT 0 AFTER creation_time,
  ADD COLUMN frozen_at DATETIME NULL AFTER is_frozen,
  ADD COLUMN frozen_by VARCHAR(150) NULL AFTER frozen_at;
