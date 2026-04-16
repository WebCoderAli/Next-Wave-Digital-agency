-- =====================================================
-- DATABASE: digital_agency
-- =====================================================

CREATE DATABASE IF NOT EXISTS digital_agency;
USE digital_agency;

-- =====================================================
-- USERS TABLE
-- =====================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status ENUM('active','blocked') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- ADMINS TABLE
-- =====================================================
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- SERVICES TABLE
-- =====================================================
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    short_description TEXT NOT NULL,
    image VARCHAR(255),
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- ORDERS TABLE
-- =====================================================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    short_desc VARCHAR(255) NOT NULL,
    detailed_desc TEXT NOT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- =====================================================
-- PAYMENTS TABLE
-- =====================================================
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    screenshot VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- =====================================================
-- CHAT TABLE (ADMIN <-> USER)
-- =====================================================
CREATE TABLE chats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    sender ENUM('admin','user') NOT NULL,
    message TEXT,
    file VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- =====================================================
-- TEAM TABLE
-- =====================================================
CREATE TABLE team (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- INSERT DEFAULT ADMIN
-- Email: admin@agency.com
-- Password: admin123
-- =====================================================
INSERT INTO admins (name, email, password)
VALUES (
    'Main Admin',
    'admin@agency.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
);

-- =====================================================
-- INSERT TEAM MEMBERS
-- =====================================================
INSERT INTO team (name, role, image) VALUES
('Ali Hamza', 'Lead Developer', 'ali.jpg'),
('Abdul Razzaq', 'Project Manager', 'razzaq.jpg'),
('Fiza Mehmood', 'UI/UX Designer', 'fiza.jpg');

-- =====================================================
-- SAMPLE SERVICES (OPTIONAL)
-- =====================================================
INSERT INTO services (title, short_description, price, status) VALUES
('Web Development', 'Custom websites and systems', 199.00, 'active'),
('Graphic Design', 'Branding, logos, UI design', 99.00, 'active'),
('Digital Marketing', 'SEO and social media marketing', 149.00, 'active');
