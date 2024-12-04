<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

try {
    // Alle Zuordnungen zwischen Lernenden und Kursen abfragen
    $query = 'SELECT * FROM tbl_kurse_lernende';
    $stmt = $db->query($query);
    $stmt->execute();

    // Alle Zuordnungen holen
    $zuordnungen = $stmt->fetchAll();

    Response::json([
        'status' => 'success',
        'message' => 'Learner-course associations retrieved successfully',
        'data' => $zuordnungen
    ], Response::OK);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving learner-course associations',
        'error' => $e->getMessage()
    ], Response::SERVER_ERROR);
}
