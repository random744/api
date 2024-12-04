<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

try {
    // Alle Kurse abfragen
    $query = 'SELECT * FROM tbl_kurse';
    $stmt = $db->query($query);
    $stmt->execute();

    // Alle Kurse holen
    $kurse = $stmt->fetchAll();

    Response::json([
        'status' => 'success',
        'message' => 'Courses retrieved successfully',
        'data' => $kurse
    ], Response::OK);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving courses',
        'error' => $e->getMessage()
    ], Response::SERVER_ERROR);
}
