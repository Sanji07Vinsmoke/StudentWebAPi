<?php
require_once __DIR__ . '/../models/Student.php';

class StudentService {
    public function ValidateStudentData($name, $midterm, $final) {
        if (empty($name) || !is_numeric($midterm) || !is_numeric($final)) {
            throw new Exception("Invalid input: All fields are required, and scores must be numeric.");
        }
        if ($midterm < 0 || $midterm > 100 || $final < 0 || $final > 100) {
            throw new Exception("Scores must be between 0 and 100.");
        }
    }

    public function ValidateStudentId($id) {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("Invalid student ID.");
        }
    }

    public function CalculateFinalGrade(Student $student) {
        $student->FinalGrade = (0.4 * $student->MidtermScore) + (0.6 * $student->FinalScore);
        $student->Status = $student->FinalGrade >= 75 ? 'Passed' : 'Failed';
        return $student;
    }

    public function CreateStudent($data, $isUpdate = false) {
        if ($isUpdate) {
            $this->ValidateStudentId($data['StudentId'] ?? null);
        }
        $this->ValidateStudentData($data['StudentName'] ?? null, $data['MidtermScore'] ?? null, $data['FinalScore'] ?? null);
        
        $student = new Student(
            $data['StudentId'] ?? null,
            $data['StudentName'],
            $data['MidtermScore'],
            $data['FinalScore']
        );

        return $this->CalculateFinalGrade($student);
    }
}
