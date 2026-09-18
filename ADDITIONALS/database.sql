-- ==============================================================================
-- UP CEBU TTBDO (Technology Transfer and Business Development Office)
-- Relational Database Schema (MySQL / MariaDB)
-- Designed for XAMPP (mysqli compatible)
-- ==============================================================================

CREATE DATABASE IF NOT EXISTS `up_cebu_ttbdo_db` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `up_cebu_ttbdo_db`;

-- ------------------------------------------------------------------------------
-- 1. Table: admins (Admin & Editor Accounts)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `role` ENUM('superadmin', 'editor') NOT NULL DEFAULT 'editor',
    `last_login` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default superadmin: email = superadmin@gmail.com, password = password (hashed using PASSWORD_DEFAULT)
INSERT INTO `admins` (`username`, `email`, `password_hash`, `full_name`, `role`)
VALUES (
    'superadmin', 
    'superadmin@gmail.com', 
    '$2y$10$q0uRZ1jcrLKFFhZWSDd8IObl6Iv0eNJf9.HP.3NvJu3PeFbIxaRGK', -- bcrypt hash for 'password'
    'TTBDO Super Administrator', 
    'superadmin'
) ON DUPLICATE KEY UPDATE `email`='superadmin@gmail.com', `password_hash`='$2y$10$q0uRZ1jcrLKFFhZWSDd8IObl6Iv0eNJf9.HP.3NvJu3PeFbIxaRGK';

-- ------------------------------------------------------------------------------
-- 2. Table: news (News, Announcements, Press Releases, Grant Calls)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `news` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `category` VARCHAR(50) NOT NULL DEFAULT 'Announcement', -- e.g. Partnership, Call for Proposals, Milestone, Announcement
    `badge_label` VARCHAR(50) NULL DEFAULT 'NEW',
    `summary` TEXT NOT NULL,
    `content` LONGTEXT NULL,
    `image_url` VARCHAR(255) NULL DEFAULT 'assets/hero-campus.jpg',
    `author` VARCHAR(100) NOT NULL DEFAULT 'TTBDO Media Communications',
    `meeting_focus` VARCHAR(100) NULL, -- e.g. 'Innovation Hub & Prototyping Track'
    `startups_supported` VARCHAR(50) NULL, -- e.g. '12 Startups'
    `coverage_area` VARCHAR(100) NULL, -- e.g. 'Cebu & Region VII'
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `is_published` TINYINT(1) NOT NULL DEFAULT 1,
    `published_date` DATE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_news_published` (`is_published`, `published_date`),
    INDEX `idx_news_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample News Data (Synchronized with current SECTIONS/news.html)
INSERT IGNORE INTO `news` (`title`, `slug`, `category`, `badge_label`, `summary`, `content`, `image_url`, `author`, `meeting_focus`, `startups_supported`, `coverage_area`, `is_featured`, `is_published`, `published_date`) VALUES
(
    'UP Cebu and DOST Regional Office VII Formalize Regional Innovation Hub Partnership',
    'up-cebu-dost-regional-innovation-hub-partnership',
    'PARTNERSHIP',
    'FEATURED STORY',
    'The University of the Philippines Cebu, through the Technology Transfer and Business Development Office (TTBDO), collaborates with the Department of Science and Technology (DOST-VII) to establish dedicated prototyping and acceleration tracks for faculty and student spin-offs throughout Central Visayas.',
    '<p>The University of the Philippines Cebu, through the Technology Transfer and Business Development Office (TTBDO), collaborates with the Department of Science and Technology (DOST-VII) to establish dedicated prototyping and acceleration tracks for faculty and student spin-offs throughout Central Visayas.</p><p>This initiative leverages modern equipment, research mentorship, and acceleration tracks to empower regional innovators.</p>',
    'assets/hero-campus.jpg',
    'TTBDO Media Communications',
    'Innovation Hub & Prototyping Track',
    '12 Startups Assisted',
    'Cebu & Region VII',
    1,
    1,
    '2025-04-15'
),
(
    '2025 Research & Commercialization Grants Open for Faculty Teams',
    '2025-research-commercialization-grants-open',
    'CALL FOR PROPOSALS',
    'GRANT CALL',
    'Faculty and research associates may submit disclosure abstracts for competitive seed validation funding up to ₱500,000 per project under the Innovation Acceleration Program.',
    '<p>Faculty and research associates may submit disclosure abstracts for competitive seed validation funding up to ₱500,000 per project under the Innovation Acceleration Program.</p>',
    'assets/resources.jpg',
    'TTBDO Grants Secretariat',
    NULL,
    NULL,
    NULL,
    0,
    1,
    '2025-04-08'
),
(
    'Five UP Cebu Inventions Secure IPOPHL Certificates of Registration',
    'five-up-cebu-inventions-secure-ipophl-certificates',
    'PATENT & IP',
    'MILESTONE',
    'The Intellectual Property Office of the Philippines has officially awarded utility model and industrial design protection to research outputs from the Department of Computer Science and School of Management.',
    '<p>The Intellectual Property Office of the Philippines has officially awarded utility model and industrial design protection to research outputs from the Department of Computer Science and School of Management.</p>',
    'assets/resources.jpg',
    'TTBDO IP Protection Unit',
    NULL,
    NULL,
    NULL,
    0,
    1,
    '2025-03-24'
),
(
    'TTBDO Conducts Prior-Art Patent Search Workshop for STEM Educators',
    'ttbdo-conducts-prior-art-patent-search-workshop',
    'CAPACITY BUILDING',
    'TRAINING',
    'Over 45 regional educators and lab coordinators completed the intensive two-day patent landscape workshop hosted at the UP Cebu Performing Arts Hall.',
    '<p>Over 45 regional educators and lab coordinators completed the intensive two-day patent landscape workshop hosted at the UP Cebu Performing Arts Hall.</p>',
    'assets/resources.jpg',
    'TTBDO Training Committee',
    NULL,
    NULL,
    NULL,
    0,
    1,
    '2025-03-12'
);

-- ------------------------------------------------------------------------------
-- 3. Table: events (Events, Summits, IP Clinics, Pitch Days)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `events` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `category` VARCHAR(50) NOT NULL DEFAULT 'Workshop', -- Summit, Legal Clinic, Mentorship, Workshop
    `badge_label` VARCHAR(50) NULL DEFAULT 'UPCOMING',
    `event_date` DATE NOT NULL,
    `start_time` VARCHAR(20) NOT NULL DEFAULT '09:00 AM',
    `end_time` VARCHAR(20) NOT NULL DEFAULT '05:00 PM',
    `venue` VARCHAR(150) NOT NULL DEFAULT 'UP Cebu Campus',
    `venue_type` ENUM('in-person', 'virtual', 'hybrid') NOT NULL DEFAULT 'in-person',
    `summary` TEXT NOT NULL,
    `description` LONGTEXT NULL,
    `registration_url` VARCHAR(255) NULL DEFAULT '#contact',
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `status` ENUM('upcoming', 'completed', 'cancelled') NOT NULL DEFAULT 'upcoming',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_event_date` (`event_date`),
    INDEX `idx_event_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample Events Data (Synchronized with current SECTIONS/events.html)
INSERT IGNORE INTO `events` (`title`, `slug`, `category`, `badge_label`, `event_date`, `start_time`, `end_time`, `venue`, `venue_type`, `summary`, `description`, `registration_url`, `is_featured`, `status`) VALUES
(
    'Central Visayas Innovation Summit & Startup Demo Day 2025',
    'central-visayas-innovation-summit-2025',
    'ANNUAL SUMMIT',
    'FLAGSHIP EVENT',
    '2025-04-15',
    '08:30 AM',
    '05:00 PM',
    'UP Cebu SRP Campus & Live Stream',
    'hybrid',
    'Annual gathering connecting student innovators, faculty researchers, angel investors, and regional venture partners for live pitch presentations and tech demonstrations.',
    '<p>Annual gathering connecting student innovators, faculty researchers, angel investors, and regional venture partners for live pitch presentations and tech demonstrations.</p>',
    '#contact',
    1,
    'upcoming'
),
(
    'Legal Clinic: Navigating Generative AI and Copyright Law in the Philippines',
    'legal-clinic-generative-ai-copyright-law',
    'IP LEGAL CLINIC',
    'FREE WEBINAR',
    '2025-03-28',
    '02:00 PM',
    '04:30 PM',
    'Online via Zoom & UP Cebu AVR',
    'hybrid',
    'Dedicated legal consultation on intellectual property, algorithmic training datasets, and copyright protections for digital creators and software developers.',
    '<p>Dedicated legal consultation on intellectual property, algorithmic training datasets, and copyright protections for digital creators and software developers.</p>',
    '#contact',
    0,
    'upcoming'
),
(
    'Pitch Perfect: SIMP Cohort 4 Mentorship & Investor Readiness Lab',
    'pitch-perfect-simp-cohort-4-mentorship',
    'STUDENT MENTORSHIP',
    'WORKSHOP',
    '2025-04-03',
    '09:00 AM',
    '12:00 PM',
    'TIC Innovation Lab, 3rd Floor',
    'in-person',
    'Intensive deck refinement and financial modeling clinic for undergraduate capstone teams qualifying for DOST regional prototype validation grants.',
    '<p>Intensive deck refinement and financial modeling clinic for undergraduate capstone teams qualifying for DOST regional prototype validation grants.</p>',
    '#contact',
    0,
    'upcoming'
);

-- ------------------------------------------------------------------------------
-- 4. Table: inquiries (Contact, IP Disclosures, Incubation Applications)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `inquiries` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `contact_number` VARCHAR(30) NULL,
    `affiliation` ENUM('student', 'faculty', 'researcher', 'msme', 'industry_partner', 'general_public') NOT NULL DEFAULT 'general_public',
    `inquiry_type` ENUM('ip_protection', 'business_incubation', 'simp_mentorship', 'licensing', 'msme_support', 'press_media', 'general') NOT NULL DEFAULT 'general',
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('pending', 'in_review', 'resolved', 'archived') NOT NULL DEFAULT 'pending',
    `admin_notes` TEXT NULL,
    `ip_address` VARCHAR(45) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_inquiries_status` (`status`),
    INDEX `idx_inquiries_type` (`inquiry_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample Inquiries Data for testing
INSERT IGNORE INTO `inquiries` (`full_name`, `email`, `contact_number`, `affiliation`, `inquiry_type`, `subject`, `message`, `status`) VALUES
(
    'Maria Santos', 
    'maria.santos@up.edu.ph', 
    '09171234567', 
    'faculty', 
    'ip_protection', 
    'Invention Disclosure for AI-assisted AgriTech Sensor', 
    'Good day TTBDO team. Our research group has finished testing our IoT humidity and soil acidity sensor. We would like to initiate prior art search and utility model filing.', 
    'in_review'
),
(
    'Juan Dela Cruz', 
    'juan.delacruz@gmail.com', 
    '09189876543', 
    'msme', 
    'msme_support', 
    'DOST SETUP and Product Packaging Consultation', 
    'Hello. We are a food processing enterprise based in Mandaue City and interested in adopting UP Cebu packaging technology and technical advisory.', 
    'pending'
);

-- ------------------------------------------------------------------------------
-- 5. Table: site_settings (Office Info, Phone, Address, Socials, Status Toggles)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `site_settings` (
    `setting_key` VARCHAR(50) PRIMARY KEY,
    `setting_value` TEXT NOT NULL,
    `setting_group` VARCHAR(50) NOT NULL DEFAULT 'general',
    `description` VARCHAR(255) NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_group`, `description`) VALUES
('office_title', 'Technology Transfer and Business Development Office', 'general', 'Official office name'),
('university_name', 'University of the Philippines Cebu', 'general', 'Parent institution name'),
('office_location', '3rd Floor, Technology Innovation Center, UP Cebu, Gorordo Ave., Lahug, Cebu City', 'contact', 'Physical office address'),
('contact_email', 'ttbdo@upcebu.edu.ph', 'contact', 'Primary contact email'),
('contact_phone', '(032) 232-6001 loc. 301', 'contact', 'Official trunkline / local'),
('facebook_url', 'https://www.facebook.com/upcebuttbdo', 'social', 'Official Facebook page'),
('office_hours', 'Monday - Friday: 8:00 AM - 5:00 PM', 'general', 'Official office operating hours'),
('motto', 'Nurtured to Create • Inspired to Innovate • Destined to Serve', 'general', 'UP Cebu Official Motto')
ON DUPLICATE KEY UPDATE `setting_value`=VALUES(`setting_value`);
