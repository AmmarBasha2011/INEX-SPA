--
-- This script creates the `users` table, which is essential for the user
-- authentication and management features of the application. It defines the
-- core schema for storing user data, including personal information, credentials,
-- and contact details.
--
-- The table includes the following columns:
--   - `id`: A unique, auto-incrementing identifier for each user.
--   - `Name`: The user's full name.
--   - `username`: A unique identifier for logging in.
--   - `email`: A unique email address for communication and recovery.
--   - `phoneNumber`: The user's phone number.
--   - `age`: The user's age.
--   - `isCompany`: A boolean flag to indicate if the user represents a company.
--   - `domain`: A domain associated with the user, if applicable.
--   - `password`: The user's password.
--   - `inviteCode`: A code used for registration or invitations.
--
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  `Name` VARCHAR(60) NOT NULL,
  `username` VARCHAR(60) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `phoneNumber` VARCHAR(255) UNIQUE,
  `age` INT,
  `isCompany` TINYINT(1) NOT NULL DEFAULT 0,
  `domain` VARCHAR(50),
  `password` VARCHAR(60) NOT NULL DEFAULT '0123456789',
  `inviteCode` INT NOT NULL
);
