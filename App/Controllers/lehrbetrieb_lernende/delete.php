<?php
use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];
    try {
        $query = 'DELETE FROM tbl_lehrbetrieb_lernende WHERE id_lehrbetrieb_lernende = :id';
        $stmt = $db->query($query);
        $stmt->execute([':id' => $id]);
        Response::json(['status' => 'success', 'message' => 'Relation deleted successfully'], Response::OK);
    } catch (Exception $e) {
        Response::json(['status' => 'error', 'message' => 'An error occurred while deleting the relation'], Response::SERVER_ERROR);
    }
} else {
    Response::json(['status' => 'error', 'message' => 'Invalid ID parameter'], Response::BAD_REQUEST);
}
