<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

// JSON-Eingabe lesen
$input = json_decode(file_get_contents('php://input'), true);

// Eingabedaten sanitizen
$country = htmlspecialchars($input['country'] ?? '', ENT_QUOTES, 'UTF-8');

// Eingaben validieren
$errors = [];
if (empty($country)) $errors['country'] = 'Country is required.';

if (!empty($errors)) {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid input',
        'data' => $errors
    ], Response::BAD_REQUEST);
    exit;
}

// Einfügen des neuen Landes in die Datenbank
$query = 'INSERT INTO tbl_countries (country) VALUES (:country)';

try {
    $stmt = $db->query($query);
    $stmt->execute([':country' => $country]);

    Response::json([
        'status' => 'success',
        'message' => 'Country created successfully!'
    ], Response::CREATED);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while creating the country',
        'error' => $e->getMessage()
    ], Response::SERVER_ERROR);
}
