<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

// Überprüfen, ob die ID vorhanden und gültig ist
if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    try {
        // Land anhand der ID abfragen
        $query = 'SELECT * FROM tbl_countries WHERE id_countries = :id';
        $stmt = $db->query($query);
        $stmt->execute([':id' => $id]);
        $country = $stmt->fetch();

        if ($country) {
            Response::json([
                'status' => 'success',
                'message' => 'Country found',
                'data' => $country
            ], Response::OK);
        } else {
            Response::json([
                'status' => 'error',
                'message' => 'Country not found'
            ], Response::NOT_FOUND);
        }
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while retrieving the country'
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}
