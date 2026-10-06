-- Run this against the `nid` database, or just run setup_admin.php
-- once on the server, which creates this table automatically.

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- No row is inserted here on purpose: run setup_admin.php on your
-- actual server so the password is hashed by your live PHP install,
-- rather than trusting a hash generated somewhere else.
