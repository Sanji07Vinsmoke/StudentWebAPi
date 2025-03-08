<?php
require_once __DIR__ . '/api/config/Database.php';
require_once __DIR__ . '/api/services/StudentService.php';
require_once __DIR__ . '/api/repositories/StudentRepository.php';
require_once __DIR__ . '/api/models/Student.php';

try {

    $studentRepository = new StudentRepository();
    $studentService = new StudentService($studentRepository);

    $studentData = [
        'StudentName' => "Edmark",
        'MidtermScore' => 90,
        'FinalScore' => 96
    ];

    $addedStudent = $studentService->AddStudent($studentData);

    echo "Student added successfully: " . json_encode($addedStudent, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
