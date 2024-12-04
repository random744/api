<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int)$params['id'];

    try {
        // Parse the input JSON
        $input = json_decode(file_get_contents('php://input'), true);

        // Initialize fields and parameters for the query
        $fieldsToUpdate = [];
        $queryParams = [];

        // Check if the country exists before updating
        if (isset($input['fk_land'])) {
            $fk_land = (int)$input['fk_land'];
            
            // Verify that the country exists
            $checkCountryQuery = "SELECT id_countries FROM tbl_countries WHERE id_countries = $fk_land";
            $result = $db->query($checkCountryQuery);
            $country = $result->fetch();

            // If country doesn't exist, return an error
            if (!$country) {
                Response::json([
                    'status' => 'error',
                    'message' => 'Invalid country ID provided for fk_land.'
                ], Response::BAD_REQUEST);
                exit;
            }

            // If the country exists, proceed to update
            $fieldsToUpdate[] = 'fk_land = :fk_land';
            $queryParams[':fk_land'] = $fk_land;
        }

        // Check and sanitize all other fields
        if (isset($input['vorname'])) {
            $fieldsToUpdate[] = 'vorname = :vorname';
            $queryParams[':vorname'] = htmlspecialchars($input['vorname'], ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['nachname'])) {
            $fieldsToUpdate[] = 'nachname = :nachname';
            $queryParams[':nachname'] = htmlspecialchars($input['nachname'], ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['strasse'])) {
            $fieldsToUpdate[] = 'strasse = :strasse';
            $queryParams[':strasse'] = htmlspecialchars($input['strasse'], ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['plz'])) {
            $fieldsToUpdate[] = 'plz = :plz';
            $queryParams[':plz'] = htmlspecialchars($input['plz'], ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['ort'])) {
            $fieldsToUpdate[] = 'ort = :ort';
            $queryParams[':ort'] = htmlspecialchars($input['ort'], ENT_QUOTES, 'UTF-8');
        }

        // Add the ID to the query parameters
        $queryParams[':id'] = $id;

        // If no fields to update, return an error
        if (empty($fieldsToUpdate)) {
            Response::json([
                'status' => 'error',
                'message' => 'No fields to update'
            ], Response::BAD_REQUEST);
            exit;
        }

        // Construct the query
        $query = 'UPDATE tbl_lernende SET ' . implode(', ', $fieldsToUpdate) . ' WHERE id_lernende = :id';

        // Execute the query
        $stmt = $db->query($query);
        $stmt->execute($queryParams);
        // Success response
        Response::json([
            'status' => 'success',
            'message' => 'Lernende updated successfully!'
        ], Response::OK);
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while updating the lernende',
            'error' => $e->getMessage()
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}
