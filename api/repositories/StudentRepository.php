<?php

require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../contracts/StudentRepositoryInterface.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../services/StudentService.php';

class StudentRepository implements StudentRepositoryInterface {
    private $pdo, $service;

    public function __construct() {
        $this->pdo = require __DIR__ . '/../config/Database.php';
        $this->service = new StudentService();
    }

    private function fetchStudent($stmt) {
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        return $student ? (array) $this->service->CalculateFinalGrade(new Student(...array_values($student))) : ["error" => "Student not found"];
    }

    public function AddStudent(Student $student) { 
        
        $student = $this->service->CalculateFinalGrade($student);
    
        $stmt = $this->pdo->prepare("INSERT INTO student (STUD_NAME, STUD_MIDTERM, STUD_FINAL, STUD_GRADE, STUD_STATUS) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $student->StudentName,
            $student->MidtermScore,
            $student->FinalScore,
            $student->FinalGrade,  
            $student->Status        
        ]);
    
        return $this->GetStudentById($this->pdo->lastInsertId());
    }
    
    

    public function GetAllStudents() {
        return array_map(fn($s) => (array) $this->service->CalculateFinalGrade(new Student(...array_values($s))),
                        $this->pdo->query("SELECT * FROM student")->fetchAll(PDO::FETCH_ASSOC));
    }

    public function GetStudentById($id) {
        $this->service->ValidateStudentId($id);
        $stmt = $this->pdo->prepare("SELECT * FROM student WHERE STUD_ID = ?");
        $stmt->execute([$id]);
        return $this->fetchStudent($stmt);
    }

    public function UpdateStudent($id, $midterm, $final) {
        $this->service->ValidateStudentId($id);
        $this->service->ValidateStudentData("Placeholder", $midterm, $final);
        $student = $this->service->CalculateFinalGrade(new Student($id, '', $midterm, $final));

        $stmt = $this->pdo->prepare("UPDATE student SET STUD_MIDTERM = ?, STUD_FINAL = ?, STUD_GRADE = ?, STUD_STATUS = ? WHERE STUD_ID = ?");
        $stmt->execute([$student->MidtermScore, $student->FinalScore, $student->FinalGrade, $student->Status, $id]);

        return $this->GetStudentById($id);
    }

    public function DeleteStudent($id) {
        $this->service->ValidateStudentId($id);
        $stmt = $this->pdo->prepare("DELETE FROM student WHERE STUD_ID = ?");
        $stmt->execute([$id]);

        return $stmt->rowCount() ? ["message" => "Student deleted"] : ["error" => "Student not found"];
    }
}
