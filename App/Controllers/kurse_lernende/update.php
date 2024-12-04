<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

// ID aus der URL für das Update lesen
$id = $params['id']; // Assuming $params['id'] enthält die ID der Zuordnung

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

// Update-Query für die Zuordnung eines Lernenden zu einem Kurs
$query = 'UPDATE tbl_kurse_lernende SET 
            fk_lernende = :fk_lernende, 
            fk_kurs = :fk_kurs, 
            role = :role
          WHERE id_kurs_lernende = :id';

try {
    $stmt = $db->query($query);
    $stmt->execute([
        ':fk_lernende' => $fk_lernende,
        ':fk_kurs' => $fk_kurs,
        ':role' => $role,
        ':id' => $id
    ]);

    Response::json([
        'status' => 'success',
        'message' => 'Learner course association updated successfully'
    ], Response::OK);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while updating the learner course association',
        'error' => $e->getMessage()
    ], Response::SERVER_ERROR);
}
