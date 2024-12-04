<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

try {
    // Alle Dozenten abfragen
    $query = 'SELECT * FROM tbl_dozenten';
    $stmt = $db->query($query);
    $stmt->execute();

    // Alle Dozenten holen
    $dozenten = $stmt->fetchAll();

    Response::json([
        'status' => 'success',
        'message' => 'Dozenten retrieved successfully',
        'data' => $dozenten
    ], Response::OK);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving dozenten',
        'error' => $e->getMessage()
    ], Response::SERVER_ERROR);
}
