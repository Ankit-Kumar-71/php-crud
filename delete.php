
<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Method not allowed.');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    exit('Invalid student ID.');
}

$stmt = $conn->prepare(
    "DELETE FROM students WHERE id = :id"
);

$stmt->execute(['id' => $id]);

if ($stmt->rowCount() === 0) {
    http_response_code(404);
    exit('Student not found.');
}

header('Location: index.php?msg=deleted');
exit;
