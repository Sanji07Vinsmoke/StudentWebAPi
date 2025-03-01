<?php
require_once __DIR__ . '/api/config/Database.php'; 
require_once __DIR__ . '/api/services/StudentService.php'; 
require_once __DIR__ . '/api/models/Student.php'; 

$pdo = require __DIR__ . '/api/config/Database.php'; 


$StudentName = "Jayson";
$MidtermScore = 86;
$FinalScore = 84;


$student = new Student(null, $StudentName, $MidtermScore, $FinalScore);


$studentService = new StudentService();
$student = $studentService->CalculateFinalGrade($student);

$sql = "INSERT INTO student (STUD_NAME, STUD_MIDTERM, STUD_FINAL, STUD_GRADE, STUD_STATUS) 
        VALUES (:StudentName, :MidtermScore, :FinalScore, :FinalGrade, :Status)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':StudentName' => $student->StudentName,
    ':MidtermScore' => $student->MidtermScore,
    ':FinalScore' => $student->FinalScore,
    ':FinalGrade' => $student->FinalGrade,
    ':Status' => $student->Status
]);

echo "Student added successfully!";
