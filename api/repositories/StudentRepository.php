<?php

require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../contracts/StudentRepositoryInterface.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../services/StudentService.php';

class StudentRepository implements StudentRepositoryInterface {
    private $pdo;
    private $studentService;

    public function __construct() {
        $this->pdo = require __DIR__ . '/../config/Database.php'; 
        $this->studentService = new StudentService();
    }

    public function AddStudent($StudentName, $MidtermScore, $FinalScore) {
        $student = new Student(null, $StudentName, $MidtermScore, $FinalScore);

        
        $student = $this->studentService->CalculateFinalGrade($student);

        
        $stmt = $this->pdo->prepare("INSERT INTO student (STUD_NAME, STUD_MIDTERM, STUD_FINAL, STUD_GRADE, STUDE_STATUS) 
                                    VALUES (:StudentName, :MidtermScore, :FinalScore, :FinalGrade, :Status)");

        $stmt->execute([
            ':StudentName' => $student->StudentName,
            ':MidtermScore' => $student->MidtermScore,
            ':FinalScore' => $student->FinalScore,
            ':FinalGrade' => $student->FinalGrade,
            ':Status' => $student->Status
        ]);

        
        $student->StudentId = $this->pdo->lastInsertId();

        return $student;
    }

    public function GetAllStudents() {
        $stmt = $this->pdo->query("SELECT STUD_ID, STUD_NAME, STUD_MIDTERM, STUD_FINAL, STUD_GRADE, STUD_STATUS FROM student");
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($students as &$student) {
            $studentObj = new Student($student['STUD_ID'], $student['STUD_NAME'], $student['STUD_MIDTERM'], $student['STUD_FINAL']);
            $student = (array) $this->studentService->CalculateFinalGrade($studentObj);
        }

        return $students;
    }

    public function GetStudentById($StudentId) {
        $stmt = $this->pdo->prepare("SELECT STUD_ID, STUD_NAME, STUD_MIDTERM, STUD_FINAL, STUD_GRADE, STUD_STATUS FROM student WHERE STUD_ID = ?");
        $stmt->execute([$StudentId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student) {
            $studentObj = new Student($student['STUD_ID'], $student['STUD_NAME'], $student['STUD_MIDTERM'], $student['STUD_FINAL']);
            return (array) $this->studentService->CalculateFinalGrade($studentObj);
        }

        return null;
    }

    public function UpdateStudent($StudentId, $MidtermScore, $FinalScore) {
        $student = new Student($StudentId, '', $MidtermScore, $FinalScore);
        $student = $this->studentService->CalculateFinalGrade($student);

        $stmt = $this->pdo->prepare("UPDATE student SET STUD_MIDTERM = ?, STUD_FINAL = ?, STUD_GRADE = ?, STUD_STATUS = ? WHERE STUD_ID = ?");
        $stmt->execute([$student->MidtermScore, $student->FinalScore, $student->FinalGrade, $student->Status, $StudentId]);

        return $this->GetStudentById($StudentId);
    }

    public function DeleteStudent($StudentId) {
        $stmt = $this->pdo->prepare("DELETE FROM student WHERE STUD_ID = ?");
        return $stmt->execute([$StudentId]);
    }
}
