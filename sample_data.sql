-- Sample minimal SQL inserts for quick import (adjust DB name/user as needed)
-- WARNING: passwords must be bcrypt-hashed; prefer using the provided PHP seed script.

-- Example users (passwords NOT hashed here) - use seed.php to create hashed passwords
-- Use: php scripts/seed.php

/*
INSERT INTO `users` (`name`,`email`,`password`,`role`,`created_at`,`updated_at`) VALUES
('Administrateur','admin@lms.test','password123','admin',NOW(),NOW()),
('Dr. Ahmed Karim','prof1@lms.test','password123','teacher',NOW(),NOW()),
('Ali Mohammed','student1@lms.test','password123','student',NOW(),NOW());
*/

-- Prefer running php scripts/seed.php which bootstraps Laravel and hashes passwords.
