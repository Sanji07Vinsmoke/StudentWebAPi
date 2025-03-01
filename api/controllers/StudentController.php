<?php
require_once __DIR__ . '/../repositories/StudentRepository.php';
require_once __DIR__ . '/../config/Database.php';

class StudentController {
    private StudentRepository $studentRepository;

    public function __construct() {
        $database = new Database();
        $this->databaseConnection = $database->getConnection();
        $this->studentRepository = new StudentRepository($this->databaseConnection);
    }

    public function GetAllStudents(): void {
        echo json_encode($this->studentRepository->GetAllStudents());
    }

    public function GetStudentById(int $id): void {
        $student = $this->studentRepository->GetStudentById($id);
        if ($student) {
            echo json_encode($student);
        } else {
            echo json_encode(["error" => "Student not found"]);
        }
    }

    public function AddStudent($studentData): void {
        if (!isset($studentData['StudentName'], $studentData['MidtermScore'], $studentData['FinalScore'])) {
            echo json_encode(["error" => "Invalid input"]);
            return;
        }
        $this->studentRepository->AddStudent($studentData['StudentName'], $studentData['MidtermScore'], $studentData['FinalScore']);
        echo "✅ Student Added Successfully";
    }

    public function UpdateStudent($studentData): void {
        if (!isset($studentData['StudentId'], $studentData['MidtermScore'], $studentData['FinalScore'])) {
            echo json_encode(["error" => "Invalid input"]);
            return;
        }
        $updatedStudent = $this->studentRepository->UpdateStudent($studentData['StudentId'], $studentData['MidtermScore'], $studentData['FinalScore']);
        if ($updatedStudent) {
            echo "✅ Student Updated Successfully";
        } else {
            echo json_encode(["error" => "Student not found"]);
        }
    }

    public function DeleteStudent(int $id): void {
        $deleted = $this->studentRepository->DeleteStudent($id);
        if ($deleted) {
            echo "✅ Student Deleted Successfully";
        } else {
            echo json_encode(["error" => "Student not found"]);
        }
    }
}
