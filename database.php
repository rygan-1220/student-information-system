<?php
// Database Configuration and Connection

// ============================================
// LOCAL TESTING - MySQL with WAMP (CURRENTLY ACTIVE)
// ============================================
$db_type = 'mysql'; // Set database type

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "studentdb";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    echo json_encode(['error' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit;
}

// Create table if not exists
$create_table = "CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    program VARCHAR(100)
)";
mysqli_query($conn, $create_table);

// Create courses table
$create_courses_table = "CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20) NOT NULL UNIQUE,
    course_name VARCHAR(100) NOT NULL,
    credits INT NOT NULL
)";
mysqli_query($conn, $create_courses_table);

// Insert sample data if empty
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM students");
$row = mysqli_fetch_assoc($result);
if ($row['count'] == 0) {
    mysqli_query($conn, "INSERT INTO students (student_id, name, email, program) VALUES 
        ('STU001', 'Song', 'song@mmu.edu.my', 'Computer Science'),
        ('STU002', 'Wong', 'wong@mmu.edu.my', 'Cybersecurity')");
}

// Insert sample courses if empty
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM courses");
$row = mysqli_fetch_assoc($result);
if ($row['count'] == 0) {
    mysqli_query($conn, "INSERT INTO courses (course_code, course_name, credits) VALUES 
        ('CS101', 'Introduction to Programming', 3),
        ('CS201', 'Database Security', 3),
        ('CS301', 'Cybersecurity', 4),
        ('CS401', 'Cloud Computing', 3),
        ('CS501', 'Machine Learning', 4)");
}


// ============================================
// AWS DEPLOYMENT - MySQL RDS 
// When migrating to AWS:
// 1. Comment out the entire LOCAL TESTING section above
// 2. Uncomment this section below
// 3. Update the RDS endpoint and credentials
// ============================================
/*
$db_type = 'mysql'; // Set database type

$db_host = getenv('DB_HOST') ?: "your-rds-endpoint.rds.amazonaws.com";
$db_user = getenv('DB_USER') ?: "admin";
$db_pass = getenv('DB_PASSWORD') ?: "your-password";
$db_name = getenv('DB_NAME') ?: "studentdb";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    echo json_encode(['error' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit;
}

// Create table if not exists
$create_table = "CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    program VARCHAR(100)
)";
mysqli_query($conn, $create_table);

// Create courses table
$create_courses_table = "CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20) NOT NULL UNIQUE,
    course_name VARCHAR(100) NOT NULL,
    credits INT NOT NULL
)";
mysqli_query($conn, $create_courses_table);

// Insert sample data if empty
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM students");
$row = mysqli_fetch_assoc($result);
if ($row['count'] == 0) {
    mysqli_query($conn, "INSERT INTO students (student_id, name, email, program) VALUES 
        ('STU001', 'Song', 'song@mmu.edu.my', 'Computer Science'),
        ('STU002', 'Wong', 'wong@mmu.edu.my', 'Cybersecurity')");
}

// Insert sample courses if empty
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM courses");
$row = mysqli_fetch_assoc($result);
if ($row['count'] == 0) {
    mysqli_query($conn, "INSERT INTO courses (course_code, course_name, credits) VALUES 
        ('CS101', 'Introduction to Programming', 3),
        ('CS201', 'Database Security', 3),
        ('CS301', 'Cybersecurity', 4),
        ('CS401', 'Cloud Computing', 3),
        ('CS501', 'Machine Learning', 4)");
}
*/

?>
