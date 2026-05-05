-- create the database
CREATE DATABASE IF NOT EXISTS elearning_db;
USE elearning_db;

-- users table
CREATE TABLE users (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    fname    VARCHAR(50),
    lname    VARCHAR(50),
    email    VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    age      INT,
    role     ENUM('student','instructor','admin'),
    status   TINYINT DEFAULT 1
);

-- courses table
CREATE TABLE courses (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    title         VARCHAR(150),
    description   TEXT,
    price         DECIMAL(10,2),
    image_url     VARCHAR(255),
    category      VARCHAR(50),
    instructor_id INT,
    status        ENUM('pending','approved','rejected') DEFAULT 'pending'
);

-- lessons table
CREATE TABLE lessons (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    course_id  INT,
    title      VARCHAR(150),
    video_url  VARCHAR(255),
    video_name VARCHAR(255)
);

-- enrollments table
CREATE TABLE enrollments (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT,
    course_id   INT,
    enrolled_at DATETIME,
    UNIQUE KEY no_duplicate (user_id, course_id)
);

-- wallets table
CREATE TABLE wallets (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    balance DECIMAL(10,2) DEFAULT 0.00
);

-- cards table
CREATE TABLE cards (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT,
    holder_name  VARCHAR(100),
    last_four    VARCHAR(4),
    expiry_month VARCHAR(2),
    expiry_year  VARCHAR(4),
    cvv          VARCHAR(3)
);

-- =============================================
-- DEFAULT ACCOUNTS
-- =============================================

-- email: admin@elearning.com      password: admin123
INSERT INTO users (fname, lname, email, password, age, role, status)
VALUES ('Admin', 'Admin', 'admin@elearning.com', 'admin123', 25, 'admin', 1);

-- email: instructor@elearning.com  password: instructor123
INSERT INTO users (fname, lname, email, password, age, role, status)
VALUES ('John', 'Doe', 'instructor@elearning.com', 'instructor123', 30, 'instructor', 1);

-- email: student@elearning.com    password: student123
INSERT INTO users (fname, lname, email, password, age, role, status)
VALUES ('Jane', 'Smith', 'student@elearning.com', 'student123', 20, 'student', 1);

-- wallet for test student (id=3)
INSERT INTO wallets (user_id, balance) VALUES (3, 100.00);