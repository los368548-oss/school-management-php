-- School Management System Database Schema
-- MySQL 8.0+ Compatible

-- Create database
CREATE DATABASE IF NOT EXISTS school_management;
USE school_management;

-- User Roles Table
CREATE TABLE user_roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users Table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT,
    is_active TINYINT(1) DEFAULT 1,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES user_roles(id)
);

-- Classes Table
CREATE TABLE classes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    class_name VARCHAR(50) NOT NULL,
    section VARCHAR(10),
    capacity INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Subjects Table
CREATE TABLE subjects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    subject_name VARCHAR(100) NOT NULL,
    subject_code VARCHAR(20) UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Class Subjects Table
CREATE TABLE class_subjects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    class_id INT,
    subject_id INT,
    teacher_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (teacher_id) REFERENCES users(id)
);

-- Students Table
CREATE TABLE students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    scholar_number VARCHAR(20) UNIQUE,
    admission_number VARCHAR(20),
    admission_date DATE,
    first_name VARCHAR(50),
    middle_name VARCHAR(50),
    last_name VARCHAR(50),
    father_name VARCHAR(100),
    mother_name VARCHAR(100),
    guardian_name VARCHAR(100),
    guardian_contact VARCHAR(15),
    dob DATE,
    gender ENUM('Male', 'Female', 'Other'),
    caste VARCHAR(50),
    category VARCHAR(50),
    nationality VARCHAR(50),
    religion VARCHAR(50),
    blood_group VARCHAR(10),
    village VARCHAR(100),
    address TEXT,
    permanent_address TEXT,
    mobile VARCHAR(15),
    email VARCHAR(100),
    aadhar VARCHAR(20),
    samagra VARCHAR(20),
    apaar VARCHAR(20),
    pan VARCHAR(20),
    previous_school VARCHAR(100),
    medical_conditions TEXT,
    photo VARCHAR(255),
    class_id INT,
    section VARCHAR(10),
    roll_number INT,
    status ENUM('Active', 'Inactive', 'Graduated') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id)
);

-- Attendance Table
CREATE TABLE attendance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    class_id INT,
    date DATE,
    status ENUM('Present', 'Absent', 'Late'),
    remarks TEXT,
    marked_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (class_id) REFERENCES classes(id),
    FOREIGN KEY (marked_by) REFERENCES users(id)
);

-- Exams Table
CREATE TABLE exams (
    id INT PRIMARY KEY AUTO_INCREMENT,
    exam_name VARCHAR(100),
    exam_type VARCHAR(50),
    class_id INT,
    start_date DATE,
    end_date DATE,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Exam Results Table
CREATE TABLE exam_results (
    id INT PRIMARY KEY AUTO_INCREMENT,
    exam_id INT,
    student_id INT,
    subject_id INT,
    marks_obtained DECIMAL(5,2),
    max_marks DECIMAL(5,2),
    grade VARCHAR(5),
    remarks TEXT,
    entered_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (exam_id) REFERENCES exams(id),
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (entered_by) REFERENCES users(id)
);

-- Fees Table
CREATE TABLE fees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    class_id INT,
    fee_type VARCHAR(50),
    amount DECIMAL(10,2),
    academic_year VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id)
);

-- Fee Payments Table
CREATE TABLE fee_payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    fee_id INT,
    amount_paid DECIMAL(10,2),
    payment_date DATE,
    payment_mode ENUM('Cash', 'Online', 'Cheque', 'UPI'),
    transaction_id VARCHAR(100),
    receipt_number VARCHAR(50),
    remarks TEXT,
    collected_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (fee_id) REFERENCES fees(id),
    FOREIGN KEY (collected_by) REFERENCES users(id)
);

-- Expenses Table
CREATE TABLE expenses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    receipt_number VARCHAR(50),
    reason VARCHAR(255),
    category VARCHAR(50),
    amount DECIMAL(10,2),
    payment_date DATE,
    remarks TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Events Table
CREATE TABLE events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    description TEXT,
    event_date DATE,
    event_time TIME,
    location VARCHAR(255),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Gallery Table
CREATE TABLE gallery (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    description TEXT,
    image_path VARCHAR(255),
    category VARCHAR(50),
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

-- News Table
CREATE TABLE news (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    content TEXT,
    published_date DATE,
    author_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id)
);

-- Homepage Content Table
CREATE TABLE homepage_content (
    id INT PRIMARY KEY AUTO_INCREMENT,
    section VARCHAR(50),
    title VARCHAR(255),
    content TEXT,
    image_path VARCHAR(255),
    order_position INT,
    is_active TINYINT(1) DEFAULT 1,
    updated_by INT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id)
);

-- Timetable Table
CREATE TABLE timetable (
    id INT PRIMARY KEY AUTO_INCREMENT,
    class_id INT,
    subject_id INT,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
    start_time TIME,
    end_time TIME,
    teacher_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (teacher_id) REFERENCES users(id)
);

-- Audit Logs Table
CREATE TABLE audit_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(255),
    table_name VARCHAR(50),
    record_id INT,
    old_values TEXT,
    new_values TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Settings Table
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE,
    setting_value TEXT,
    updated_by INT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id)
);

-- Permissions Table
CREATE TABLE permissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role_id INT,
    permission VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES user_roles(id)
);

-- Indexes for performance
CREATE INDEX idx_students_class ON students(class_id);
CREATE INDEX idx_students_scholar ON students(scholar_number);
CREATE INDEX idx_attendance_student_date ON attendance(student_id, date);
CREATE INDEX idx_exam_results_exam_student ON exam_results(exam_id, student_id);
CREATE INDEX idx_fee_payments_student ON fee_payments(student_id);
CREATE INDEX idx_events_date ON events(event_date);
CREATE INDEX idx_gallery_category ON gallery(category);