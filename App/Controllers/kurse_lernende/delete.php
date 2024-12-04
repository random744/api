<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

// ID aus der URL für das Löschen lesen
$id = $params['id']; // Assuming $params['id'] enthält die ID der Zuordnung

// Delete-Query für die Zuordnung eines Lernenden zu einem Kurs
$query = 'DELETE FROM tbl_kurse_lernende WHERE id_kurs_lernende = :id';

try {
    $stmt = $db->query($query);
    $stmt->execute([':id' => $id]);

    Response::json([
        'status' => 'success',
        'message' => 'Learner course association deleted successfully'
    ], Response::OK);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while deleting the learner course association',
        'error' => $e->getMessage()
    ], Response::SERVER_ERROR);
}
