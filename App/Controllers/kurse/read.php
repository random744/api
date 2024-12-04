<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

// Überprüfen, ob die ID vorhanden und gültig ist
if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    try {
        // Kurs anhand der ID abfragen
        $query = 'SELECT * FROM tbl_kurse WHERE id_kurs = :id';
        $stmt = $db->query($query);
        $stmt->execute([':id' => $id]);
        $kurs = $stmt->fetch();

        if ($kurs) {
            Response::json([
                'status' => 'success',
                'message' => 'Course found',
                'data' => $kurs
            ], Response::OK);
        } else {
            Response::json([
                'status' => 'error',
                'message' => 'Course not found'
            ], Response::NOT_FOUND);
        }
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while retrieving the course',
            'error' => $e->getMessage()
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}
