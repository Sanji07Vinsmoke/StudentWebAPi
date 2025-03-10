<?php
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../repositories/StudentRepository.php';

class StudentService {
    private StudentRepository $repository;

    public function __construct(StudentRepository $repository) {
        $this->repository = $repository;
    }

    private function ValidateStudentData($name, $midterm, $final) {
        if (empty($name) || !is_numeric($midterm) || !is_numeric($final)) {
            throw new Exception("Invalid input: All fields are required, and scores must be numeric.");
        }
        if ($midterm < 0 || $midterm > 100 || $final < 0 || $final > 100) {
            throw new Exception("Scores must be between 0 and 100.");
        }
    }

    private function ValidateStudentId($id) {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("Invalid student ID.");
        }
    }

    private function AssignStudentProperties(Student $student, array $data) {
        $student->StudentId = $data['StudentId'] ?? null;
        $student->StudentName = trim($data['StudentName']);
        $student->MidtermScore = (float) $data['MidtermScore'];
        $student->FinalScore = (float) $data['FinalScore'];
    }

    private function CalculateFinalGrade(Student $student) {
        $student->FinalGrade = (0.4 * $student->MidtermScore) + (0.6 * $student->FinalScore);
        $student->Status = $student->FinalGrade >= 75 ? 'Passed' : 'Failed';
    }

    public function CreateStudent($data, $isUpdate = false) {
        if ($isUpdate) {
            $this->ValidateStudentId($data['StudentId'] ?? null);
        }

        if (empty($data['StudentName'])) {
            throw new Exception("StudentName is required and cannot be empty.");
        }

        $this->ValidateStudentData($data['StudentName'], $data['MidtermScore'] ?? null, $data['FinalScore'] ?? null);

        $student = new Student();
        $this->AssignStudentProperties($student, $data);
        $this->CalculateFinalGrade($student);

        return $student;
    }

    public function AddStudent($data) {
        try {
            if (!isset($data['StudentName']) || trim($data['StudentName']) === '') {
                throw new Exception("StudentName is required and cannot be empty.");
            }

            $student = $this->CreateStudent($data);
            return $this->repository->AddStudent($student);
        } catch (Exception $e) {
            error_log("Error adding student: " . $e->getMessage());
            return ["error" => $e->getMessage()];
        }
    }

    public function UpdateStudent($data) {
        $student = $this->CreateStudent($data, true);
        return $this->repository->UpdateStudent($student->StudentId, $student->MidtermScore, $student->FinalScore);
    }

    public function DeleteStudent($id) {
        $this->ValidateStudentId($id);
        return $this->repository->DeleteStudent($id);
    }

    public function GetAllStudents() {
        return $this->repository->GetAllStudents();
    }

    public function GetStudentById($id) {
        $this->ValidateStudentId($id);
        return $this->repository->GetStudentById($id);
    }

    public function GetFinalGradeAndStatus($data = null) {
        return $this->repository->GetFinalGradeAndStatus();
    }    
}
