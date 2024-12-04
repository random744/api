<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

// JSON-Eingabe lesen
$input = json_decode(file_get_contents('php://input'), true);

// Eingabedaten sanitizen
$fk_lernende = (int)($input['fk_lernende'] ?? 0);
$fk_kurs = (int)($input['fk_kurs'] ?? 0);
$role = htmlspecialchars($input['role'] ?? '', ENT_QUOTES, 'UTF-8');

// Eingaben validieren
$errors = [];
if (empty($fk_lernende)) $errors['fk_lernende'] = 'Lernende ID is required.';
if (empty($fk_kurs)) $errors['fk_kurs'] = 'Kurs ID is required.';
if (empty($role)) $errors['role'] = 'Role is required.';

if (!empty($errors)) {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid input',
        'data' => $errors
    ], Response::BAD_REQUEST);
    exit;
}

// Insert-Query für die Zuordnung eines Lernenden zu einem Kurs
$query = 'INSERT INTO tbl_kurse_lernende (fk_lernende, fk_kurs, role)
          VALUES (:fk_lernende, :fk_kurs, :role)';

try {
    $stmt = $db->query($query);
    $stmt->execute([
        ':fk_lernende' => $fk_lernende,
        ':fk_kurs' => $fk_kurs,
        ':role' => $role
    ]);

    Response::json([
        'status' => 'success',
        'message' => 'Lernender successfully added to the course with role'
    ], Response::CREATED);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while assigning the learner to the course',
        'error' => $e->getMessage()
    ], Response::SERVER_ERROR);
}
