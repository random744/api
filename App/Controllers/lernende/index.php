<?php
use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

try {
    $query = 'SELECT * FROM tbl_lernende';
    $stmt = $db->query($query);
    $stmt->execute();
    $lernende = $stmt->fetchAll();
    Response::json(['status' => 'success', 'message' => 'Lernende retrieved successfully', 'data' => $lernende], Response::OK);
} catch (Exception $e) {
    Response::json(['status' => 'error', 'message' => 'An error occurred while retrieving lernende', 'data' => ['error' => $e->getMessage()]], Response::SERVER_ERROR);
}
