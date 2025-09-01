-- 1. Create a New User in MySQL

-- By default, MySQL comes with the root user (super admin). For security, we create separate accounts for different people.

-- Syntax: CREATE USER 'username'@'host' IDENTIFIED BY 'password';
CREATE USER 'student1'@'localhost' IDENTIFIED BY 'Stud3nt@123';
CREATE USER 'lecturer1'@'%' IDENTIFIED BY 'L3cturer@123';


-- 'student1'@'localhost' → Can only connect from the same machine.

-- 'lecturer1'@'%' → Can connect from any host (useful for remote connections).

-- 2. Grant Access at Server Level

-- You can grant global privileges (across all databases).

-- Give a user global read-only access
GRANT SELECT ON *.* TO 'lecturer1'@'%';


-- *.* → All databases, all tables.

-- Now lecturer1 can read from any database, but not change anything.

-- Grant Access at Database Level

-- Usually, you want users restricted to specific databases.

-- Give student1 full access only to IST database
GRANT ALL PRIVILEGES ON IST.* TO 'student1'@'localhost';

-- Give lecturer1 read + insert rights on IST database
GRANT SELECT, INSERT ON IST.* TO 'lecturer1'@'%';


-- IST.* → Means “all tables inside IST database.”

-- You can mix privileges (SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, ALTER, ALL PRIVILEGES).

-- 4. Grant Access at Table Level

-- If you want even finer control:

-- Lecturer can only see students table, but not modify
GRANT SELECT ON IST.students TO 'lecturer1'@'%';

-- Student can update their own record
GRANT SELECT, UPDATE ON IST.students TO 'student1'@'localhost';

-- Show User Privileges

-- To check what a user can do:

SHOW GRANTS FOR 'student1'@'localhost';

-- Revoke Privileges (Remove Rights)

-- If you gave too much access, you can revoke it:

REVOKE INSERT, UPDATE ON IST.* FROM 'lecturer1'@'%';

-- Drop User

-- If an account is no longer needed:

DROP USER 'student1'@'localhost';

-- 1. CREATE DATABASE
CREATE DATABASE IST;
USE IST;

-- 2. CREATE TABLES
-- Students Table
CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    course_id INT,      -- Will link to courses later
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Courses Table
CREATE TABLE courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(100),
    description TEXT
);

-- Lecturers Table
CREATE TABLE lecturers (
    lecturer_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    specialty VARCHAR(100)
);

-- Enrollments Table (many-to-many relationship between students and courses)
CREATE TABLE enrollments (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    enrollment_date DATE
);

-- 3. ADD RELATIONSHIPS (with ALTER TABLE)
ALTER TABLE students
ADD CONSTRAINT fk_students_courses
FOREIGN KEY (course_id) REFERENCES courses(course_id);

ALTER TABLE enrollments
ADD CONSTRAINT fk_enrollments_students
FOREIGN KEY (student_id) REFERENCES students(student_id);

ALTER TABLE enrollments
ADD CONSTRAINT fk_enrollments_courses
FOREIGN KEY (course_id) REFERENCES courses(course_id);


-- Courses
INSERT INTO courses (course_name, description)
VALUES ('Cybersecurity Basics', 'Introduction to cybersecurity concepts'),
       ('Networking', 'Computer networking fundamentals'),
       ('Ethical Hacking', 'Hands-on penetration testing'),
       ('Web Security', 'Securing web applications');

-- Lecturers
INSERT INTO lecturers (first_name, last_name, specialty)
VALUES ('Alice', 'Johnson', 'Networking'),
       ('Bob', 'Smith', 'Ethical Hacking'),
       ('Eve', 'Ngugi', 'Cybersecurity Basics');

-- Students
INSERT INTO students (first_name, last_name, email, course_id)
VALUES ('John', 'Doe', 'john.doe@example.com', 1),
       ('Mary', 'Njeri', 'mary.njeri@example.com', 2),
       ('Peter', 'Omondi', 'peter.omondi@example.com', 3),
       ('Aisha', 'Ali', 'aisha.ali@example.com', 1);

-- Enrollments
INSERT INTO enrollments (student_id, course_id, enrollment_date)
VALUES (1, 1, '2025-01-15'),
       (2, 2, '2025-01-16'),
       (3, 3, '2025-01-17'),
       (4, 1, '2025-01-18'),
       (4, 3, '2025-01-19'); -- One student in two courses


-- Get all students in Cybersecurity Basics
SELECT s.first_name, s.last_name, c.course_name
FROM students s
JOIN courses c ON s.course_id = c.course_id
WHERE c.course_name = 'Cybersecurity Basics';


-- Find all enrollments after 2025-01-16
SELECT * FROM enrollments
WHERE enrollment_date > '2025-01-16';


-- Update a student’s email
UPDATE students
SET email = 'john.doe@ist.ac.ke'
WHERE student_id = 1;


-- Add a phone number column to students table
ALTER TABLE students
ADD phone VARCHAR(20);


-- Show which students are enrolled in which courses
SELECT s.first_name, s.last_name, c.course_name, e.enrollment_date
FROM enrollments e
JOIN students s ON e.student_id = s.student_id
JOIN courses c ON e.course_id = c.course_id;


USE IST;

-- 1. Create Roles Table
CREATE TABLE roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) UNIQUE
);

-- Insert sample roles
INSERT INTO roles (role_name)
VALUES ('admin'), ('lecturer'), ('student');

-- Primary Key (PK)

-- A Primary Key is a column (or set of columns) that uniquely identifies each row in a table.
-- It must be unique (no two rows can have the same value).
-- It cannot be NULL (every record must have a value).
-- A table can only have one primary key, but that key can consist of multiple columns (composite key).--


-- Foreign Key (FK)
-- A Foreign Key is a column in one table that refers to the Primary Key in another table.
-- It creates a relationship between tables.
-- Ensures referential integrity (you can’t enroll a student in a course if that student doesn’t exist).

-- UNION
-- The UNION operator combines the results of two or more SELECT queries into one result set.
-- Each SELECT must have the same number of columns and compatible data types.
-- Removes duplicate rows (unless you use UNION ALL).

-- JOIN

-- A JOIN combines rows from two or more tables based on related columns (usually a foreign key).
-- Types of Joins:
-- INNER JOIN → Returns only rows where there’s a match in both tables.
-- LEFT JOIN → Returns all rows from the left table, plus matches from the right. If no match, fills with NULL.
-- RIGHT JOIN → Opposite of LEFT JOIN.
-- FULL OUTER JOIN → Returns all rows from both tables (not supported directly in MySQL, but can be simulated with UNION).

-- 2. Create Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,  -- store hashed passwords (important for cybersecurity!)
    role_id INT,
    student_id INT NULL,
    lecturer_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(role_id),
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (lecturer_id) REFERENCES lecturers(lecturer_id)
);


-- Admin account
INSERT INTO users (username, password_hash, role_id)
VALUES ('admin1', SHA2('admin123', 256), 1);

-- Lecturer accounts
INSERT INTO users (username, password_hash, role_id, lecturer_id)
VALUES ('bob_smith', SHA2('lecturer123', 256), 2, 2),
       ('alice_j', SHA2('lecturer123', 256), 2, 1);

-- Student accounts
INSERT INTO users (username, password_hash, role_id, student_id)
VALUES ('john_doe', SHA2('student123', 256), 3, 1),
       ('mary_n', SHA2('student123', 256), 3, 2),
       ('aisha_ali', SHA2('student123', 256), 3, 4);


--Show All Users with Their Role
SELECT u.username, r.role_name
FROM users u
JOIN roles r ON u.role_id = r.role_id;


--Update a User’s Password (simulate reset)
UPDATE users
SET password_hash = SHA2('newpass123', 256)
WHERE username = 'john_doe';

--Union Example with Users
-- Combine usernames and student emails
SELECT username AS identifier FROM users
UNION
SELECT email AS identifier FROM students;