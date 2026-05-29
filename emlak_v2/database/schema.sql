-- Emlak Yönetim Sistemi Database Schema
-- Version: 2.0.1
-- Created: 2026-05-29

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET COLLATION_CONNECTION = utf8mb4_unicode_ci;

-- Users Table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `username` VARCHAR(255) UNIQUE NOT NULL,
  `email` VARCHAR(255) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20),
  `role` ENUM('admin', 'office', 'agent', 'customer') DEFAULT 'customer',
  `office_id` INT,
  `profile_image` VARCHAR(255),
  `is_active` BOOLEAN DEFAULT TRUE,
  `last_login` DATETIME,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_email` (`email`),
  KEY `idx_username` (`username`),
  KEY `idx_office_id` (`office_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Real Estate Offices Table
CREATE TABLE IF NOT EXISTS `real_estate_offices` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `owner_id` INT NOT NULL,
  `address` VARCHAR(500) NOT NULL,
  `city` VARCHAR(100),
  `district` VARCHAR(100),
  `postal_code` VARCHAR(10),
  `country` VARCHAR(100) DEFAULT 'Türkiye',
  `phone` VARCHAR(20),
  `email` VARCHAR(255),
  `website` VARCHAR(255),
  `latitude` DECIMAL(10, 8),
  `longitude` DECIMAL(11, 8),
  `logo` VARCHAR(255),
  `banner` VARCHAR(255),
  `business_license` VARCHAR(255),
  `tax_id` VARCHAR(20),
  `design_theme` ENUM('modern', 'premium', 'colorful') DEFAULT 'modern',
  `opening_hours` JSON,
  `social_media` JSON,
  `is_verified` BOOLEAN DEFAULT FALSE,
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_owner_id` (`owner_id`),
  KEY `idx_city` (`city`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Real Estate Agents Table
CREATE TABLE IF NOT EXISTS `agents` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `office_id` INT NOT NULL,
  `license_number` VARCHAR(50),
  `specialization` VARCHAR(255),
  `bio` TEXT,
  `phone_verified` BOOLEAN DEFAULT FALSE,
  `email_verified` BOOLEAN DEFAULT FALSE,
  `commission_rate` DECIMAL(5, 2) DEFAULT 5.00,
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`office_id`) REFERENCES `real_estate_offices`(`id`),
  KEY `idx_office_id` (`office_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Properties/Listings Table
CREATE TABLE IF NOT EXISTS `listings` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `office_id` INT NOT NULL,
  `agent_id` INT,
  `title` VARCHAR(255) NOT NULL,
  `description` LONGTEXT,
  `property_type` ENUM('apartment', 'house', 'villa', 'shop', 'office', 'land', 'commercial', 'other') NOT NULL,
  `purpose` ENUM('sale', 'rent', 'both') DEFAULT 'sale',
  `address` VARCHAR(500) NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `district` VARCHAR(100),
  `postal_code` VARCHAR(10),
  `latitude` DECIMAL(10, 8),
  `longitude` DECIMAL(11, 8),
  `price` DECIMAL(12, 2) NOT NULL,
  `currency` VARCHAR(3) DEFAULT 'TRY',
  `area` DECIMAL(8, 2),
  `rooms` INT,
  `bedrooms` INT,
  `bathrooms` DECIMAL(3, 1),
  `floor` INT,
  `total_floors` INT,
  `year_built` INT,
  `parking` BOOLEAN DEFAULT FALSE,
  `balcony` BOOLEAN DEFAULT FALSE,
  `garden` BOOLEAN DEFAULT FALSE,
  `pool` BOOLEAN DEFAULT FALSE,
  `heating` VARCHAR(100),
  `cooling` VARCHAR(100),
  `furnished` ENUM('unfurnished', 'partially', 'furnished') DEFAULT 'unfurnished',
  `pet_friendly` BOOLEAN DEFAULT FALSE,
  `lease_start_date` DATE,
  `lease_end_date` DATE,
  `lease_duration_months` INT,
  `status` ENUM('active', 'inactive', 'sold', 'rented') DEFAULT 'active',
  `priority_level` ENUM('normal', 'featured', 'premium') DEFAULT 'normal',
  `views_count` INT DEFAULT 0,
  `likes_count` INT DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expires_at` DATETIME,
  FOREIGN KEY (`office_id`) REFERENCES `real_estate_offices`(`id`),
  FOREIGN KEY (`agent_id`) REFERENCES `agents`(`id`),
  KEY `idx_office_id` (`office_id`),
  KEY `idx_city` (`city`),
  KEY `idx_property_type` (`property_type`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listing Images/Gallery Table
CREATE TABLE IF NOT EXISTS `listing_images` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `listing_id` INT NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `image_alt` VARCHAR(255),
  `is_primary` BOOLEAN DEFAULT FALSE,
  `order` INT DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`listing_id`) REFERENCES `listings`(`id`) ON DELETE CASCADE,
  KEY `idx_listing_id` (`listing_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 360 Degree Tour/Virtual Tour Table
CREATE TABLE IF NOT EXISTS `virtual_tours` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `listing_id` INT NOT NULL,
  `tour_type` ENUM('360', 'video', 'virtual', 'drone') DEFAULT '360',
  `tour_url` VARCHAR(500),
  `thumbnail` VARCHAR(255),
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`listing_id`) REFERENCES `listings`(`id`) ON DELETE CASCADE,
  KEY `idx_listing_id` (`listing_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contracts Table
CREATE TABLE IF NOT EXISTS `contracts` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `listing_id` INT NOT NULL,
  `office_id` INT NOT NULL,
  `tenant_id` INT,
  `owner_id` INT,
  `contract_type` ENUM('sale', 'rent') DEFAULT 'rent',
  `contract_number` VARCHAR(50) UNIQUE,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `duration_months` INT,
  `contract_amount` DECIMAL(12, 2),
  `payment_method` VARCHAR(100),
  `contract_document` VARCHAR(255),
  `status` ENUM('draft', 'active', 'completed', 'terminated', 'expired') DEFAULT 'draft',
  `notes` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`listing_id`) REFERENCES `listings`(`id`),
  FOREIGN KEY (`office_id`) REFERENCES `real_estate_offices`(`id`),
  KEY `idx_status` (`status`),
  KEY `idx_start_date` (`start_date`),
  KEY `idx_end_date` (`end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Requests/Inquiries Table
CREATE TABLE IF NOT EXISTS `requests` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `listing_id` INT,
  `office_id` INT NOT NULL,
  `customer_id` INT,
  `agent_id` INT,
  `request_type` ENUM('inquiry', 'tour', 'offer', 'complaint', 'other') DEFAULT 'inquiry',
  `title` VARCHAR(255) NOT NULL,
  `description` LONGTEXT,
  `contact_name` VARCHAR(255),
  `contact_email` VARCHAR(255),
  `contact_phone` VARCHAR(20),
  `status` ENUM('new', 'in_progress', 'responded', 'closed') DEFAULT 'new',
  `priority` ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
  `assigned_to` INT,
  `notes` LONGTEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`listing_id`) REFERENCES `listings`(`id`),
  FOREIGN KEY (`office_id`) REFERENCES `real_estate_offices`(`id`),
  FOREIGN KEY (`customer_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`agent_id`) REFERENCES `agents`(`id`),
  KEY `idx_office_id` (`office_id`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Favorites/Likes Table
CREATE TABLE IF NOT EXISTS `favorites` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `listing_id` INT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`listing_id`) REFERENCES `listings`(`id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_favorite` (`user_id`, `listing_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reviews Table
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `office_id` INT NOT NULL,
  `agent_id` INT,
  `reviewer_id` INT NOT NULL,
  `listing_id` INT,
  `rating` INT CHECK (rating >= 1 AND rating <= 5),
  `title` VARCHAR(255),
  `comment` LONGTEXT,
  `is_approved` BOOLEAN DEFAULT FALSE,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`office_id`) REFERENCES `real_estate_offices`(`id`),
  FOREIGN KEY (`agent_id`) REFERENCES `agents`(`id`),
  FOREIGN KEY (`reviewer_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`listing_id`) REFERENCES `listings`(`id`),
  KEY `idx_office_id` (`office_id`),
  KEY `idx_rating` (`rating`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Activity Log Table
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT,
  `office_id` INT,
  `action` VARCHAR(100),
  `entity_type` VARCHAR(100),
  `entity_id` INT,
  `description` TEXT,
  `ip_address` VARCHAR(45),
  `user_agent` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_user_id` (`user_id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Documents Table
CREATE TABLE IF NOT EXISTS `documents` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `listing_id` INT,
  `contract_id` INT,
  `document_type` VARCHAR(100),
  `document_name` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `file_size` INT,
  `uploaded_by` INT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`listing_id`) REFERENCES `listings`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`contract_id`) REFERENCES `contracts`(`id`) ON DELETE CASCADE,
  KEY `idx_listing_id` (`listing_id`),
  KEY `idx_contract_id` (`contract_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Notifications Table
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `message` LONGTEXT,
  `notification_type` VARCHAR(100),
  `related_entity_type` VARCHAR(100),
  `related_entity_id` INT,
  `is_read` BOOLEAN DEFAULT FALSE,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  KEY `idx_user_id` (`user_id`),
  KEY `idx_is_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings Table
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `setting_key` VARCHAR(255) UNIQUE NOT NULL,
  `setting_value` LONGTEXT,
  `description` TEXT,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Indexes for better performance
CREATE INDEX idx_listings_city_price ON listings(city, price);
CREATE INDEX idx_listings_property_type_status ON listings(property_type, status);
CREATE INDEX idx_contracts_office_status ON contracts(office_id, status);
CREATE INDEX idx_requests_office_status ON requests(office_id, status);

-- Insert Default Settings
INSERT INTO `settings` (`setting_key`, `setting_value`, `description`) VALUES
('app_name', 'Emlak Yönetim Sistemi', 'Application name'),
('app_email', 'info@emlaksistemi.com', 'Application email'),
('app_phone', '+90 (XXX) XXX XX XX', 'Application phone'),
('items_per_page', '10', 'Items per page for pagination'),
('max_upload_size', '5242880', 'Maximum file upload size in bytes'),
('enable_whatsapp', '1', 'Enable WhatsApp notifications'),
('enable_sms', '1', 'Enable SMS notifications'),
('enable_email', '1', 'Enable Email notifications'),
('currency', 'TRY', 'Default currency'),
('google_maps_api', '', 'Google Maps API Key'),
('openai_api', '', 'OpenAI API Key');