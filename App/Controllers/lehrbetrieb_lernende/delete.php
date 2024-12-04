<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Check if 'id' is set in $params and is a valid integer
if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    try {
        // Prepare the query to delete the Lehrbetrieb-Lernende record by id
        $query = 'DELETE FROM tbl_lehrbetrieb_lernende WHERE id_lehrbetrieb_lernende = :id';

        // Execute the query through the Database class
        $stmt = $db->query($query);
        $stmt->execute([':id' => $id]);

        // Check if any row was deleted
        if ($stmt->rowCount() > 0) {
            // Send a success response
            Response::json([
                'status' => 'success',
                'message' => 'Lehrbetrieb-Lernende record deleted successfully!'
            ], Response::OK);
        } else {
            // Send a 404 Not Found response if no record is found to delete
            Response::json([
                'status' => 'error',
                'message' => 'Lehrbetrieb-Lernende record not found'
            ], Response::NOT_FOUND);
        }
    } catch (Exception $e) {
        // Handle unexpected errors with a 500 Internal Server Error response
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while deleting the Lehrbetrieb-Lernende record',
            'data' => ['error' => $e->getMessage()]
        ], Response::SERVER_ERROR);
    }
} else {
    // Send a 400 Bad Request response if 'id' is missing or invalid
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}
