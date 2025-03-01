<?php
require_once __DIR__ . '/api/config/Database.php';

$pdo = require __DIR__ . '/api/config/Database.php';

$StudentId = 3;


$checkSql = "SELECT * FROM student WHERE STUD_ID = :StudentId";
$checkStmt = $pdo->prepare($checkSql);
$checkStmt->execute([':StudentId' => $StudentId]);
$student = $checkStmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo "No student found with ID: $StudentId";
    exit;
}

$deleteSql = "DELETE FROM student WHERE STUD_ID = :StudentId";
$deleteStmt = $pdo->prepare($deleteSql);
$deleteStmt->execute([':StudentId' => $StudentId]);

echo "Student with ID $StudentId has been deleted successfully!";

