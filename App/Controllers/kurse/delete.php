<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

// Überprüfen, ob die ID vorhanden und gültig ist
if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    try {
        // Löschen des Kurses aus der Datenbank
        $query = 'DELETE FROM tbl_kurse WHERE id_kurs = :id';
        $stmt = $db->query($query);
        $stmt->execute([':id' => $id]);

        Response::json([
            'status' => 'success',
            'message' => 'Course deleted successfully!'
        ], Response::OK);
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while deleting the course',
            'error' => $e->getMessage()
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}
