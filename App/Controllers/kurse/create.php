<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

// JSON-Eingabe lesen
$input = json_decode(file_get_contents('php://input'), true);

// Eingabedaten sanitizen
$kursnummer = htmlspecialchars($input['kursnummer'] ?? '', ENT_QUOTES, 'UTF-8');
$kursthema = htmlspecialchars($input['kursthema'] ?? '', ENT_QUOTES, 'UTF-8');
$inhalt = htmlspecialchars($input['inhalt'] ?? '', ENT_QUOTES, 'UTF-8');
$fk_dozent = (int)($input['fk_dozent'] ?? 0);
$startdatum = $input['startdatum'] ?? '';
$enddatum = $input['enddatum'] ?? '';
$dauer = (int)($input['dauer'] ?? 0);

// Eingaben validieren
$errors = [];
if (empty($kursnummer)) $errors['kursnummer'] = 'Kursnummer is required.';
if (empty($kursthema)) $errors['kursthema'] = 'Kurthema is required.';
if (empty($inhalt)) $errors['inhalt'] = 'Content is required.';
if ($fk_dozent <= 0) $errors['fk_dozent'] = 'Valid Dozent ID is required.';
if (empty($startdatum)) $errors['startdatum'] = 'Start date is required.';
if (empty($enddatum)) $errors['enddatum'] = 'End date is required.';
if ($dauer <= 0) $errors['dauer'] = 'Duration is required and must be a positive integer.';

if (!empty($errors)) {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid input',
        'data' => $errors
    ], Response::BAD_REQUEST);
    exit;
}

// Einfügen des neuen Kurses in die Datenbank
$query = 'INSERT INTO tbl_kurse (kursnummer, kursthema, inhalt, fk_dozent, startdatum, enddatum, dauer)
          VALUES (:kursnummer, :kursthema, :inhalt, :fk_dozent, :startdatum, :enddatum, :dauer)';

try {
    $stmt = $db->query($query);
    $stmt->execute([
        ':kursnummer' => $kursnummer,
        ':kursthema' => $kursthema,
        ':inhalt' => $inhalt,
        ':fk_dozent' => $fk_dozent,
        ':startdatum' => $startdatum,
        ':enddatum' => $enddatum,
        ':dauer' => $dauer
    ]);

    Response::json([
        'status' => 'success',
        'message' => 'Course created successfully!'
    ], Response::CREATED);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while creating the course',
        'error' => $e->getMessage()
    ], Response::SERVER_ERROR);
}
