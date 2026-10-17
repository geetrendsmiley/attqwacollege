CREATE DATABASE IF NOT EXISTS attqwa_college
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE attqwa_college;

CREATE TABLE IF NOT EXISTS students (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    admission_number VARCHAR(40) NOT NULL UNIQUE,
    full_name VARCHAR(160) NOT NULL,
    email VARCHAR(254) NOT NULL,
    class_name ENUM('Early Years', 'Primary', 'Junior Secondary', 'Senior Secondary') NOT NULL,
    password_hash VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_students_email (email)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS payments (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    reference VARCHAR(80) NOT NULL UNIQUE,
    amount INT UNSIGNED NOT NULL,
    currency CHAR(3) NOT NULL,
    term ENUM('First Term', 'Second Term', 'Third Term') NOT NULL,
    status ENUM('pending', 'paid', 'failed') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_payments_student FOREIGN KEY (student_id)
        REFERENCES students(id) ON DELETE RESTRICT,
    INDEX idx_payments_student_created (student_id, created_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contact_messages (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(254) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Add real student records supplied by the school. Students set a password
-- themselves in the portal after verifying their admission number and email.
-- Example:
-- INSERT INTO students (admission_number, full_name, email, class_name)
-- VALUES ('ATQ-2026-001', 'Student Name', 'family@example.com', 'Primary');
