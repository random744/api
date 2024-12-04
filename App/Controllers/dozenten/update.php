<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

// ID aus der URL für das Update lesen
$id = $params['id']; // Assuming $params['id'] enthält die Dozenten-ID

// JSON-Eingabe lesen
$input = json_decode(file_get_contents('php://input'), true);

// Eingabedaten sanitizen
$vorname = htmlspecialchars($input['vorname'] ?? '', ENT_QUOTES, 'UTF-8');
$nachname = htmlspecialchars($input['nachname'] ?? '', ENT_QUOTES, 'UTF-8');
$strasse = htmlspecialchars($input['strasse'] ?? '', ENT_QUOTES, 'UTF-8');
$plz = htmlspecialchars($input['plz'] ?? '', ENT_QUOTES, 'UTF-8');
$ort = htmlspecialchars($input['ort'] ?? '', ENT_QUOTES, 'UTF-8');
$fk_land = (int)($input['fk_land'] ?? 0); // Assuming fk_land is an integer
$geschlecht = htmlspecialchars($input['geschlecht'] ?? '', ENT_QUOTES, 'UTF-8');
$telefon = htmlspecialchars($input['telefon'] ?? '', ENT_QUOTES, 'UTF-8');
$handy = htmlspecialchars($input['handy'] ?? '', ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($input['email'] ?? '', ENT_QUOTES, 'UTF-8');
$birthdate = htmlspecialchars($input['birthdate'] ?? '', ENT_QUOTES, 'UTF-8');

// Eingaben validieren
$errors = [];
if (empty($vorname)) $errors['vorname'] = 'Vorname is required.';
if (empty($nachname)) $errors['nachname'] = 'Nachname is required.';
if (empty($strasse)) $errors['strasse'] = 'Strasse is required.';
if (empty($plz)) $errors['plz'] = 'PLZ is required.';
if (empty($ort)) $errors['ort'] = 'Ort is required.';
if (empty($fk_land)) $errors['fk_land'] = 'Land is required.';
if (empty($geschlecht)) $errors['geschlecht'] = 'Geschlecht is required.';
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

// Update-Query für den Dozenten
$query = 'UPDATE tbl_dozenten SET 
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
            birthdate = :birthdate 
          WHERE id_dozent = :id';

try {
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
        ':birthdate' => $birthdate,
        ':id' => $id
    ]);

    Response::json([
        'status' => 'success',
        'message' => 'Dozent updated successfully!'
    ], Response::OK);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while updating the Dozent',
        'error' => $e->getMessage()
    ], Response::SERVER_ERROR);
}
