CREATE DATABASE IF NOT EXISTS barangay_db;
USE barangay_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Resident') DEFAULT 'Resident',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS residents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    purok VARCHAR(50) NOT NULL,
    contact VARCHAR(20) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS households (
    id INT AUTO_INCREMENT PRIMARY KEY,
    household_no VARCHAR(50) NOT NULL UNIQUE,
    head_name VARCHAR(100) NOT NULL,
    purok VARCHAR(50) NOT NULL,
    total_members INT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS officials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    contact VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    document_type VARCHAR(100) NOT NULL,
    purpose TEXT NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


TRUNCATE TABLE announcements;
TRUNCATE TABLE requests;
TRUNCATE TABLE officials;
TRUNCATE TABLE households;
TRUNCATE TABLE residents;
TRUNCATE TABLE users;


INSERT INTO users (username, password, role) 
VALUES ('admin', '$2y$10$e.P6M.f7G/ZtH3a8H1R40e6wGg9A9yP0eL9Y/UuX/Q3qR.e9H3v/G', 'Admin');
