<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// ID aus der URL lesen (zum Updaten)
$id = $params['id'];  // Assuming $params['id'] enthält die ID der Lernenden

// Überprüfen, ob die ID vorhanden und gültig ist
if (isset($id) && ctype_digit($id)) {
    $id = (int)$id;

    try {
        // JSON-Eingabe lesen
        $input = json_decode(file_get_contents('php://input'), true);

        // Eingabedaten sanitizen
        $vorname = htmlspecialchars($input['vorname'] ?? '', ENT_QUOTES, 'UTF-8');
        $nachname = htmlspecialchars($input['nachname'] ?? '', ENT_QUOTES, 'UTF-8');
        $strasse = htmlspecialchars($input['strasse'] ?? '', ENT_QUOTES, 'UTF-8');
        $plz = htmlspecialchars($input['plz'] ?? '', ENT_QUOTES, 'UTF-8');
        $ort = htmlspecialchars($input['ort'] ?? '', ENT_QUOTES, 'UTF-8');
        $fk_land = isset($input['fk_land']) ? (int)$input['fk_land'] : null;
        $geschlecht = isset($input['geschlecht']) ? htmlspecialchars($input['geschlecht'], ENT_QUOTES, 'UTF-8') : null;
        $telefon = htmlspecialchars($input['telefon'] ?? '', ENT_QUOTES, 'UTF-8');
        $handy = htmlspecialchars($input['handy'] ?? '', ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($input['email'] ?? '', ENT_QUOTES, 'UTF-8');
        $email_privat = htmlspecialchars($input['email_privat'] ?? '', ENT_QUOTES, 'UTF-8');
        $birthdate = $input['birthdate'] ?? '';

        // Eingaben validieren
        $errors = [];
        if (empty($vorname)) $errors['vorname'] = 'Vorname is required.';
        if (empty($nachname)) $errors['nachname'] = 'Nachname is required.';
        if (empty($strasse)) $errors['strasse'] = 'Strasse is required.';
        if (empty($plz)) $errors['plz'] = 'PLZ is required.';
        if (empty($ort)) $errors['ort'] = 'Ort is required.';
        if ($fk_land === null) $errors['fk_land'] = 'Land is required.';
        if (empty($telefon)) $errors['telefon'] = 'Telefon is required.';
        if (empty($handy)) $errors['handy'] = 'Handy is required.';
        if (empty($email)) $errors['email'] = 'Email is required.';
        if (empty($birthdate)) $errors['birthdate'] = 'Birthdate is required.';

        if (!empty($errors)) {
            Response::json([
                'status' => 'error',
                'message' => 'Invalid input',
                'data' => $errors
            ], Response::BAD_REQUEST);
            exit;
        }

        // Überprüfen, ob das Land existiert
        if ($fk_land !== null) {
            $checkCountryQuery = 'SELECT id_countries FROM tbl_countries WHERE id_countries = :fk_land';
            $stmt = $db->query($checkCountryQuery);
            $stmt->execute([':fk_land' => $fk_land]);
            $country = $stmt->fetch();

            if (!$country) {
                Response::json([
                    'status' => 'error',
                    'message' => 'Invalid country ID provided'
                ], Response::BAD_REQUEST);
                exit;
            }
        }

        // Update-Query für den Lernenden
        $query = 'UPDATE tbl_lernende SET 
                    vorname = :vorname, 
                    nachname = :nachname, 
                    strasse = :strasse, 
                    plz = :plz, 
                    ort = :ort, 
                    fk_land = :fk_land, 
                    geschlecht = :geschlecht, 
                    telefon = :telefon, 
                    handy = :handy, 
                    email = :email, 
                    email_privat = :email_privat, 
                    birthdate = :birthdate 
                  WHERE id_lernende = :id';

        // SQL-Abfrage ausführen
        $stmt = $db->query($query);
        $stmt->execute([
            ':vorname' => $vorname,
            ':nachname' => $nachname,
            ':strasse' => $strasse,
            ':plz' => $plz,
            ':ort' => $ort,
            ':fk_land' => $fk_land,
            ':geschlecht' => $geschlecht,
            ':telefon' => $telefon,
            ':handy' => $handy,
            ':email' => $email,
            ':email_privat' => $email_privat,
            ':birthdate' => $birthdate,
            ':id' => $id
        ]);

        // Erfolgsantwort
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
