<?php
require_once __DIR__ . '/../repositories/StudentRepository.php';
require_once __DIR__ . '/../services/StudentService.php';

class StudentController {
    private StudentRepository $studentRepository;
    private StudentService $studentService;

    public function __construct() {
        $this->studentRepository = new StudentRepository();
        $this->studentService = new StudentService();
    }

    public function GetAllStudents(): void {
        echo json_encode($this->studentRepository->GetAllStudents());
    }

    public function GetStudentById(int $id): void {
        try {
            $this->studentService->ValidateStudentId($id);
            $student = $this->studentRepository->GetStudentById($id);

            echo json_encode($student ?: ["error" => "Student not found"]);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function AddStudent($data): void {
        try {
            $student = $this->studentService->CreateStudent($data);
            echo json_encode($this->studentRepository->AddStudent($student));
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function UpdateStudent($data): void {
        try {
            $student = $this->studentService->CreateStudent($data, true);
            echo json_encode($this->studentRepository->UpdateStudent($student));
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function DeleteStudent(int $id): void {
        try {
            $this->studentService->ValidateStudentId($id);
            $deleted = $this->studentRepository->DeleteStudent($id);

            echo json_encode($deleted ?: ["error" => "Student not found"]);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
}
