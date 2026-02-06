# Student Information System

A lightweight Vue/Vuetify + PHP/MySQL application for managing student records and course information.

> Context: Built for AWS cloud deployment practice with RDS MySQL and EC2 infrastructure using CloudFormation.

## ✨ Features
- **Student Management**: Add, view, and delete student records with ID, name, email, and program details.
- **Course Management**: Add, view, and delete courses with course code, name, and credit information.
- **Real-time UI**: Instant updates with Vue.js reactive data binding and Vuetify Material Design components.
- **RESTful API**: Clean JSON API endpoints for all CRUD operations.
- **Auto-initialization**: Tables and sample data automatically created on first run.

## 🧰 Tech Stack
- **Frontend**: Vue 2, Vuetify 2.x (all via CDN)
- **Backend**: PHP 7.4+ using mysqli with prepared statements for security
- **Database**: MySQL 8.x (local WAMP or AWS RDS)

## 🗂️ Project Structure
```
.
├── index.html           # Main frontend (Vue + Vuetify)
├── api.php              # RESTful API endpoints
├── database.php         # Database configuration and auto-initialization
└── cloudformation/      # AWS infrastructure (IaC) templates
    ├── 01-vpc.yaml
    ├── 02-security-groups.yaml
    ├── 03-alb.yaml
    └── 05-rds.yaml
```

## 🚀 Getting Started

### Local Development (Windows with WAMP)
1. **Prerequisites**: WAMP Server (Apache, PHP 7.4+, MySQL)
2. **Install WAMP**: Download from https://www.wampserver.com/
3. **Deploy Files**: Copy project to `C:\wamp64\www\V3`
4. **Database Setup**:
   - Start WAMP Server (Apache & MySQL)
   - Create database `studentdb` via phpMyAdmin or MySQL CLI:
     ```sql
     CREATE DATABASE studentdb;
     ```
   - Tables (`students`, `courses`) will auto-create on first access
5. **Access Application**: Open http://localhost/V3

## AWS Deployment (EC2)

```bash
#!/bin/bash
# Install Git, Apache & PHP
sudo yum update -y
sudo yum install -y git httpd php php-mysqli php-pdo

# Start Apache
sudo systemctl start httpd
sudo systemctl enable httpd

# Clone/pull project from git
cd /var/www/html
sudo git clone https://github.com/rygan-1220/student-information-system .

# Configure Apache environment variables
cat > /etc/httpd/conf.d/env_vars.conf << 'EOF'
SetEnv DB_HOST "student-app-alb-1168035529.us-east-1.elb.amazonaws.com"
SetEnv DB_NAME "studentdb"
SetEnv DB_USER "admin"
SetEnv DB_PASSWORD "StudentInfoAdmin#123"
EOF

# Allow Apache to connect to external database (SELinux)
sudo setsebool -P httpd_can_network_connect_db 1

# Restart Apache
sudo systemctl restart httpd
```

## API Endpoints

### Students
- `GET api.php` - List all students
- `POST api.php` - Add student
  ```json
  {
    "student_id": "STU003",
    "name": "John Doe",
    "email": "john@mmu.edu.my",
    "program": "Computer Science"
  }
  ```
- `DELETE api.php?id=1` - Delete student by ID

### Courses
- `GET api.php?resource=courses` - List all courses
- `POST api.php?resource=courses` - Add course
  ```json
  {
    "course_code": "CS101",
    "course_name": "Introduction to Programming",
    "credits": 3
  }
  ```
- `DELETE api.php?resource=courses&id=1` - Delete course by ID

## 🔐 Security Notes
- Uses prepared statements to prevent SQL injection
- CORS enabled for development (restrict in production)
- Store AWS credentials in Parameter Store or Secrets Manager
- Use IAM roles for EC2 instances (no hardcoded credentials)
