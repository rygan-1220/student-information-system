<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Include database configuration
require_once 'database.php';


$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$resource = $_GET['resource'] ?? 'students'; // Default to students for backward compatibility

// Route based on resource type
if ($resource === 'courses') {
    // COURSES API
    switch ($method) {
        case 'GET':
            // Get all courses
            $result = mysqli_query($conn, "SELECT * FROM courses ORDER BY course_code ASC");
            $courses = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $courses[] = $row;
            }
            echo json_encode($courses);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate required fields
            if (empty($data['course_code']) || empty($data['course_name']) || empty($data['credits'])) {
                echo json_encode(['success' => false, 'message' => 'All fields are required']);
                break;
            }
            
            $stmt = mysqli_prepare($conn, "INSERT INTO courses (course_code, course_name, credits) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssi", $data['course_code'], $data['course_name'], $data['credits']);
            
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true, 'id' => mysqli_insert_id($conn)]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add course']);
            }
            mysqli_stmt_close($stmt);
            break;

        case 'DELETE':
            $id = $_GET['id'] ?? 0;
            
            $stmt = mysqli_prepare($conn, "DELETE FROM courses WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            
            echo json_encode(['success' => true]);
            break;

        default:
            echo json_encode(['error' => 'Invalid method']);
    }
} else {
    // STUDENT  API
    switch ($method) {
        case 'GET':
            // Get all students
            $result = mysqli_query($conn, "SELECT * FROM students ORDER BY id DESC");
            $students = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $students[] = $row;
            }
            echo json_encode($students);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate required fields
            if (empty($data['student_id']) || empty($data['name']) || empty($data['email']) || empty($data['program'])) {
                echo json_encode(['success' => false, 'message' => 'All fields are required']);
                break;
            }
            
            $stmt = mysqli_prepare($conn, "INSERT INTO students (student_id, name, email, program) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssss", $data['student_id'], $data['name'], $data['email'], $data['program']);
            mysqli_stmt_execute($stmt);
            echo json_encode(['success' => true, 'id' => mysqli_insert_id($conn)]);
            mysqli_stmt_close($stmt);
            break;

        case 'DELETE':
            $id = $_GET['id'] ?? 0;
            
            $stmt = mysqli_prepare($conn, "DELETE FROM students WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            
            echo json_encode(['success' => true]);
            break;

        default:
            echo json_encode(['error' => 'Invalid method']);
    }
}

// Close connection
mysqli_close($conn);
?>