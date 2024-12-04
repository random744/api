<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

// Überprüfen, ob die ID vorhanden und gültig ist
if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    try {
        // Löschen des Landes aus der Datenbank
        $query = 'DELETE FROM tbl_countries WHERE id_countries = :id';
        $stmt = $db->query($query);
        $stmt->execute([':id' => $id]);

        Response::json([
            'status' => 'success',
            'message' => 'Country deleted successfully!'
        ], Response::OK);
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while deleting the country'
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}
