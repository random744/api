<?php
use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];
    try {
        $query = 'SELECT * FROM tbl_lehrbetrieb_lernende WHERE id_lehrbetrieb_lernende = :id';
        $stmt = $db->query($query);
        $stmt->execute([':id' => $id]);
        $relation = $stmt->fetch();
        if ($relation) {
            Response::json(['status' => 'success', 'message' => 'Relation found', 'data' => $relation], Response::OK);
        } else {
            Response::json(['status' => 'error', 'message' => 'Relation not found'], Response::NOT_FOUND);
        }
    } catch (Exception $e) {
        Response::json(['status' => 'error', 'message' => 'An error occurred while retrieving the relation'], Response::SERVER_ERROR);
    }
} else {
    Response::json(['status' => 'error', 'message' => 'Invalid ID parameter'], Response::BAD_REQUEST);
}
