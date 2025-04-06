-- Database creation
CREATE DATABASE IF NOT EXISTS khsetri_db;
USE khsetri_db;

-- News table
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    image_url VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Events table
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    event_date DATETIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    image_url VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Members table
CREATE TABLE IF NOT EXISTS members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20),
    address TEXT,
    password_hash VARCHAR(255) NOT NULL,
    registration_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_verified BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(255),
    last_login DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data for news
INSERT INTO news (title, content, date) VALUES
('Kshetri Cultural Festival 2023', 'The annual Kshetri cultural festival will be held next month...', '2023-05-15 10:00:00'),
('Community Meeting Announcement', 'Important community meeting to discuss upcoming projects...', '2023-05-10 14:30:00');

-- Sample data for events
INSERT INTO events (title, description, event_date, location) VALUES
('Dashain Celebration', 'Traditional Dashain celebration with cultural programs', '2023-10-15 17:00:00', 'Community Hall, Kathmandu'),
('Youth Conference', 'Annual youth conference for Kshetri community members', '2023-07-22 09:00:00', 'Hotel Yak & Yeti, Kathmandu');

-- Admin account (default password: admin123)
INSERT INTO admins (username, password_hash, full_name) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator');