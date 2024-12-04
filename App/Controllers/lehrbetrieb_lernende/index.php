<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

try {
    $query = 'SELECT * FROM tbl_lehrbetrieb_lernende';
    $stmt = $db->query($query);
    $stmt->execute();
    $relations = $stmt->fetchAll();
    Response::json(['status' => 'success', 'message' => 'Relations retrieved successfully', 'data' => $relations], Response::OK);
} catch (Exception $e) {
    Response::json(['status' => 'error', 'message' => 'An error occurred while retrieving relations', 'data' => ['error' => $e->getMessage()]], Response::SERVER_ERROR);
}
