<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$start_time = microtime(true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['cName']));
    $from = filter_var(trim($_POST['cEmail']), FILTER_SANITIZE_EMAIL);
    $subject = $name . ' - ' . htmlspecialchars(trim($_POST['cSubject']));
    $message = htmlspecialchars(trim($_POST['cMessage']));
    $receiving_email_address = 'hello@birawaargantawirya.co.id';

    // Log time after input processing
    error_log('Input processing time: ' . (microtime(true) - $start_time) . ' seconds');

    // Validate email
    if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'mail.birawaargantawirya.co.id';
        $mail->SMTPAuth = true;
        $mail->Username = 'hello@birawaargantawirya.co.id'; // SMTP username
        $mail->Password = 'Locus123_'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use SSL encryption
        $mail->Port = 465;

        // Log time after SMTP configuration
        error_log('SMTP configuration time: ' . (microtime(true) - $start_time) . ' seconds');

        // Recipients
        $mail->setFrom($from, $name);
        $mail->addAddress($receiving_email_address);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();

        // Log time after sending email
        error_log('Email sending time: ' . (microtime(true) - $start_time) . ' seconds');

        echo json_encode(['status' => 'success', 'message' => 'Mail Sent. Thanks For Contacting Us']);
    } catch (Exception $e) {
        error_log('Mail sending failed. Error: ' . $mail->ErrorInfo);
        echo json_encode(['status' => 'error', 'message' => 'Mail Sending Failed.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

error_log('Total execution time: ' . (microtime(true) - $start_time) . ' seconds');
?>
