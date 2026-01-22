-- This script creates the `users` table if it does not already exist.
-- The `users` table is the central table for storing all user account information,
-- including credentials, personal details, and other related data.

CREATE TABLE IF NOT EXISTS users (
  -- The unique identifier for each user record. Automatically increments.
  id INT AUTO_INCREMENT PRIMARY KEY,

  -- The full name of the user. This field is required.
  `Name` VARCHAR(60) NOT NULL,

  -- The user's chosen username for logging in. Must be unique across all users.
  `username` VARCHAR(60) NOT NULL UNIQUE,

  -- The user's email address. Used for communication and must be unique.
  `email` VARCHAR(100) NOT NULL UNIQUE,

  -- The user's phone number. This is optional but must be unique if provided.
  `phoneNumber` VARCHAR(255) UNIQUE,

  -- The age of the user. This field is optional.
  `age` INT,

  -- A boolean flag (0 or 1) to indicate if the user account represents a company.
  -- Defaults to 0 (false).
  `isCompany` TINYINT(1) NOT NULL DEFAULT 0,

  -- An associated domain name, typically for company accounts. This field is optional.
  `domain` VARCHAR(50),

  -- The user's password. In a real application, this should be a hashed value, not plain text.
  -- The default value is for placeholder purposes only.
  `password` VARCHAR(60) NOT NULL DEFAULT '0123456789',

  -- An invite code that was required for the user to sign up.
  `inviteCode` INT NOT NULL
);
