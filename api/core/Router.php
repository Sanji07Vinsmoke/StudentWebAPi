<?php
require_once __DIR__ . '/../repositories/StudentRepository.php';

class Router {
    private $repository;

    public function __construct() {
        $this->repository = new StudentRepository();
    }

    public function HandleRequest($route) {
        header('Content-Type: application/json');

        $request_method = $_SERVER['REQUEST_METHOD'];
        $input = json_decode(file_get_contents('php://input'), true);
        $uri = explode('/', trim($_SERVER['REQUEST_URI'], '/')); // Get URL segments
        $studentId = isset($uri[2]) ? (int) $uri[2] : null; // Extract student ID from URL

        switch ($route) {
            case 'student': // Changed from 'students' to 'student'
                if ($request_method === 'GET') {
                    if ($studentId) {
                        $student = $this->repository->GetStudentById($studentId);
                        echo json_encode($student ?: ["error" => "Student not found"]);
                    } else {
                        echo json_encode($this->repository->GetAllStudents());
                    }
                } elseif ($request_method === 'POST') {
                    if (!isset($input['StudentName'], $input['MidtermScore'], $input['FinalScore'])) {
                        http_response_code(400);
                        echo json_encode(["error" => "Missing required fields"]);
                        return;
                    }
                    $student = $this->repository->AddStudent($input['StudentName'], $input['MidtermScore'], $input['FinalScore']);
                    echo json_encode($student);
                } elseif ($request_method === 'PUT' && $studentId) {
                    if (!isset($input['MidtermScore'], $input['FinalScore'])) {
                        http_response_code(400);
                        echo json_encode(["error" => "Missing required fields"]);
                        return;
                    }
                    $student = $this->repository->UpdateStudent($studentId, $input['MidtermScore'], $input['FinalScore']);
                    echo json_encode($student);
                } elseif ($request_method === 'DELETE' && $studentId) {
                    $deleted = $this->repository->DeleteStudent($studentId);
                    echo json_encode(["message" => $deleted ? "Student deleted" : "Student not found"]);
                } else {
                    http_response_code(405);
                    echo json_encode(["error" => "Method Not Allowed"]);
                }
                break;

            default:
                http_response_code(404);
                echo json_encode(["error" => "Invalid API request"]);
        }
    }
}

