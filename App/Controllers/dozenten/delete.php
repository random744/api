<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

// ID aus der URL für das Löschen lesen
$id = $params['id']; // Assuming $params['id'] enthält die Dozenten-ID

// Löschen des Dozenten aus der Datenbank
$query = 'DELETE FROM tbl_dozenten WHERE id_dozent = :id';

try {
    $stmt = $db->query($query);
    $stmt->execute([':id' => $id]);

    Response::json([
        'status' => 'success',
        'message' => 'Dozent deleted successfully!'
    ], Response::OK);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while deleting the Dozent',
        'error' => $e->getMessage()
    ], Response::SERVER_ERROR);
}
