<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Read the raw JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Sanitize inputs using htmlspecialchars to prevent HTML special characters in the database
$fk_lehrbetrieb = htmlspecialchars($input['fk_lehrbetrieb'] ?? '', ENT_QUOTES, 'UTF-8');
$fk_lernende = htmlspecialchars($input['fk_lernende'] ?? '', ENT_QUOTES, 'UTF-8');
$start = htmlspecialchars($input['start'] ?? '', ENT_QUOTES, 'UTF-8');
$ende = htmlspecialchars($input['ende'] ?? '', ENT_QUOTES, 'UTF-8');
$beruf = htmlspecialchars($input['beruf'] ?? '', ENT_QUOTES, 'UTF-8');

// Initial validation: check for empty fields
$errors = [];
if (empty($fk_lehrbetrieb)) $errors['fk_lehrbetrieb'] = 'Lehrbetrieb ID is required.';
if (empty($fk_lernende)) $errors['fk_lernende'] = 'Lernende ID is required.';
if (empty($start)) $errors['start'] = 'Start date is required.';
if (empty($ende)) $errors['ende'] = 'End date is required.';
if (empty($beruf)) $errors['beruf'] = 'Beruf is required.';

if (!empty($errors)) {
    // Send error response in JSON format with a 400 Bad Request status
    Response::json([
        'status' => 'error',
        'message' => 'Bad request',
        'data' => $errors
    ], Response::BAD_REQUEST);
    exit;
}

// Insert data into the database
$query = 'INSERT INTO tbl_lehrbetrieb_lernende (fk_lehrbetrieb, fk_lernende, start, ende, beruf) 
          VALUES (:fk_lehrbetrieb, :fk_lernende, :start, :ende, :beruf)';

try {
    $stmt = $db->query($query);
    $stmt->execute([
        ':fk_lehrbetrieb' => $fk_lehrbetrieb,
        ':fk_lernende' => $fk_lernende,
        ':start' => $start,
        ':ende' => $ende,
        ':beruf' => $beruf
    ]);

    // Send success response in JSON format with a 201 Created status
    Response::json([
        'status' => 'success',
        'message' => 'Lehrbetrieb-Lernende record created successfully!'
    ], Response::CREATED);
} catch (Exception $e) {
    // Handle unexpected errors with a 500 Internal Server Error response
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while creating the Lehrbetrieb-Lernende record',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}
