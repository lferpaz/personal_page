<?php
session_start();
header('Content-Type: application/json');

$to_email = 'luisfernando.paz99@gmail.com';
$subject = 'New Contact Form Submission';

// Verifica método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Verifica campos requeridos
if (!isset($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['message'], $_POST['csrf_token'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Verifica CSRF token
if (!isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

// Verifica petición AJAX
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Sanea entradas
$name    = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
$email   = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$phone   = filter_var(trim($_POST['phone']), FILTER_SANITIZE_STRING);
$message = filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING);

// Valida email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

// Arma mensaje
$headers = "From: $name <$email>\r\nReply-To: $email\r\n";
$body  = "Name: $name\n";
$body .= "Email: $email\n";
$body .= "Phone: $phone\n";
$body .= "Message:\n$message\n";

// Envía correo
if (mail($to_email, $subject, $body, $headers)) {
    // Regenera token CSRF
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    echo json_encode(['success' => true, 'message' => 'Email sent successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send email']);
}
?>
