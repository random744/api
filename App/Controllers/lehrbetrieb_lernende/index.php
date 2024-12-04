<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

try {
    // Query to select all records from tbl_lehrbetrieb_lernende
    $query = 'SELECT * FROM tbl_lehrbetrieb_lernende';
    $stmt = $db->query($query);
    $stmt->execute();

    // Fetch all records
    $lehrbetriebLernende = $stmt->fetchAll();

    // Send success response with the records
    Response::json([
        'status' => 'success',
        'message' => 'Lehrbetrieb-Lernende records retrieved successfully',
        'data' => $lehrbetriebLernende
    ], Response::OK);
} catch (Exception $e) {
    // Handle unexpected errors with a 500 Internal Server Error response
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving Lehrbetrieb-Lernende records',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}
