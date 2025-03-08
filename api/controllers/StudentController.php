<?php
require_once __DIR__ . '/../services/StudentService.php';

class StudentController {
    private StudentService $studentService;

    public function __construct(StudentService $studentService) {
        $this->studentService = $studentService;
    }

    public function GetAllStudents(): void {
        echo json_encode($this->studentService->GetAllStudents());
    }

    public function GetStudentById(int $id): void {
        try {
            $student = $this->studentService->GetStudentById($id);
            echo json_encode($student ?: ["error" => "Student not found"]);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function AddStudent($data): void {
        try {
            $student = $this->studentService->AddStudent($data);
            echo json_encode($student);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function UpdateStudent($data): void {
        try {
            $student = $this->studentService->UpdateStudent($data);
            echo json_encode($student);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function DeleteStudent(int $id): void {
        try {
            $deleted = $this->studentService->DeleteStudent($id);
            echo json_encode($deleted ?: ["error" => "Student not found"]);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
}
