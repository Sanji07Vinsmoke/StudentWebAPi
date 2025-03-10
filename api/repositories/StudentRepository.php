<?php

require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../contracts/StudentRepositoryInterface.php';
require_once __DIR__ . '/../config/Database.php';

use Config\Database;

class StudentRepository implements StudentRepositoryInterface {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    private function fetchStudent($stmt) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function AddStudent(Student $student) {
        error_log("Inserting student: " . json_encode([
            "StudentName" => $student->StudentName,
            "MidtermScore" => $student->MidtermScore,
            "FinalScore" => $student->FinalScore,
            "FinalGrade" => $student->FinalGrade,
            "Status" => $student->Status
        ]));
    
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
        return $this->pdo->query("SELECT * FROM student")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function GetStudentById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM student WHERE STUD_ID = ?");
        $stmt->execute([$id]);
        return $this->fetchStudent($stmt);
    }

    public function UpdateStudent($StudentId, $MidtermScore, $FinalScore) {
        $stmt = $this->pdo->prepare("UPDATE student SET STUD_MIDTERM = ?, STUD_FINAL = ? WHERE STUD_ID = ?");
        $stmt->execute([$MidtermScore, $FinalScore, $StudentId]);

        return $this->GetStudentById($StudentId);
    }

    public function DeleteStudent($id) {
        $stmt = $this->pdo->prepare("DELETE FROM student WHERE STUD_ID = ?");
        $stmt->execute([$id]);

        return $stmt->rowCount() ? ["message" => "Student deleted"] : ["error" => "Student not found"];
    }

    public function GetFinalGradeAndStatus(){
        $stmt = $this->pdo->query("SELECT STUD_ID, STUD_NAME, STUD_GRADE, STUD_STATUS FROM student");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
