<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

// Überprüfen, ob die ID vorhanden und gültig ist
if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    try {
        // Zuordnung anhand der ID abfragen
        $query = 'SELECT * FROM tbl_kurse_lernende WHERE id_kurs_lernende = :id';
        $stmt = $db->query($query);
        $stmt->execute([':id' => $id]);
        $zuordnung = $stmt->fetch();

        if ($zuordnung) {
            Response::json([
                'status' => 'success',
                'message' => 'Learner-course association found',
                'data' => $zuordnung
            ], Response::OK);
        } else {
            Response::json([
                'status' => 'error',
                'message' => 'Learner-course association not found'
            ], Response::NOT_FOUND);
        }
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while retrieving the learner-course association',
            'error' => $e->getMessage()
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}
