<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

// Überprüfen, ob die ID vorhanden und gültig ist
if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    try {
        // JSON-Eingabe lesen
        $input = json_decode(file_get_contents('php://input'), true);

        // Eingabedaten sanitizen
        $country = htmlspecialchars($input['country'] ?? '', ENT_QUOTES, 'UTF-8');

        // Eingaben validieren
        if (empty($country)) {
            Response::json([
                'status' => 'error',
                'message' => 'Country is required'
            ], Response::BAD_REQUEST);
            exit;
        }

        // Aktualisieren des Landes in der Datenbank
        $query = 'UPDATE tbl_countries SET country = :country WHERE id_countries = :id';
        $stmt = $db->query($query);
        $stmt->execute([':country' => $country, ':id' => $id]);

        Response::json([
            'status' => 'success',
            'message' => 'Country updated successfully!'
        ], Response::OK);
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while updating the country'
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}
