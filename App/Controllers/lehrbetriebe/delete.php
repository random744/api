<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Check if the 'id' parameter is set and is a valid integer
if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    try {
        // Prepare the DELETE query
        $query = 'DELETE FROM tbl_lehrbetrieb WHERE id_lehrbetrieb = :id';
        
        // Execute the query using the Database connection (query method instead of prepare)
        $stmt = $db->query($query);
        $stmt->execute([':id' => $id]);

        // Check if the delete was successful
        if ($stmt->rowCount() > 0) {
            // Send success response
            Response::json([
                'status' => 'success',
                'message' => 'Lehrbetrieb deleted successfully!'
            ], Response::OK);
        } else {
            // Send a 404 Not Found response if the ID doesn't exist
            Response::json([
                'status' => 'error',
                'message' => 'Lehrbetrieb not found'
            ], Response::NOT_FOUND);
        }
    } catch (Exception $e) {
        // Handle unexpected errors with a 500 Internal Server Error response
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while deleting the lehrbetrieb',
            'error' => $e->getMessage()
        ], Response::SERVER_ERROR);
    }
} else {
    // Send a 400 Bad Request response if 'id' is missing or invalid
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}
