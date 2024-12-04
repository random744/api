<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

try {
    // Alle Länder abfragen
    $query = 'SELECT * FROM tbl_countries';
    $stmt = $db->query($query);
    $stmt->execute();

    // Alle Länder holen
    $countries = $stmt->fetchAll();

    Response::json([
        'status' => 'success',
        'message' => 'Countries retrieved successfully',
        'data' => $countries
    ], Response::OK);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving countries',
        'error' => $e->getMessage()
    ], Response::SERVER_ERROR);
}
