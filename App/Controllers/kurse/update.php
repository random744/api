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
$fk_dozent = isset($input['fk_dozent']) ? (int)$input['fk_dozent'] : null;
$startdatum = $input['startdatum'] ?? '';
$enddatum = $input['enddatum'] ?? '';
$dauer = (int)($input['dauer'] ?? 0);

// ID aus der URL lesen (zum Updaten)
$id = $params['id'];  // Assuming $params['id'] enthält die Kurs-ID

// Eingaben validieren
$errors = [];
if (empty($kursnummer)) $errors['kursnummer'] = 'Kursnummer is required.';
if (empty($kursthema)) $errors['kursthema'] = 'Kursthema is required.';
if (empty($inhalt)) $errors['inhalt'] = 'Inhalt is required.';
if (empty($startdatum)) $errors['startdatum'] = 'Startdatum is required.';
if (empty($enddatum)) $errors['enddatum'] = 'Enddatum is required.';
if ($dauer <= 0) $errors['dauer'] = 'Dauer must be greater than 0.';

if (!empty($errors)) {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid input',
        'data' => $errors
    ], Response::BAD_REQUEST);
    exit;
}

// Überprüfen, ob der Dozent existiert
if ($fk_dozent !== null) {
    $checkDozentQuery = 'SELECT id_dozent FROM tbl_dozenten WHERE id_dozent = :fk_dozent';
    $stmt = $db->query($checkDozentQuery);
    $stmt->execute([':fk_dozent' => $fk_dozent]);
    $dozent = $stmt->fetch();

    if (!$dozent) {
        Response::json([
            'status' => 'error',
            'message' => 'Invalid Dozent ID provided'
        ], Response::BAD_REQUEST);
        exit;
    }
}

// Update-Query für den Kurs
$query = 'UPDATE tbl_kurse SET 
            kursnummer = :kursnummer, 
            kursthema = :kursthema, 
            inhalt = :inhalt, 
            fk_dozent = :fk_dozent, 
            startdatum = :startdatum, 
            enddatum = :enddatum, 
            dauer = :dauer 
          WHERE id_kurs = :id';

try {
    $stmt = $db->query($query);
    $stmt->execute([
        ':kursnummer' => $kursnummer,
        ':kursthema' => $kursthema,
        ':inhalt' => $inhalt,
        ':fk_dozent' => $fk_dozent,
        ':startdatum' => $startdatum,
        ':enddatum' => $enddatum,
        ':dauer' => $dauer,
        ':id' => $id
    ]);

    Response::json([
        'status' => 'success',
        'message' => 'Course updated successfully!'
    ], Response::OK);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while updating the course',
        'error' => $e->getMessage()
    ], Response::SERVER_ERROR);
}
