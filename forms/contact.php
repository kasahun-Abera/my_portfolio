<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include the PHPMailer library
require '../vendor/autoload.php'; // Path to the PHPMailer autoload file

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r", "\n"), array(" ", " "), $name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    // Set the recipient email address
    $recipient = "kasahunabera81@gmail.com"; // Your email address

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP server configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'lilkasabera@gmail.com'; // Your SMTP username
        $mail->Password = 'ghjjfekzntuqjzra'; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom($email, $name); // Sender's email and name
        $mail->addAddress($recipient); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject; // Subject line
        $mail->Body = "Name: $name<br>Email: $email<br><br>Message:<br>$message"; // Email body
        $mail->AltBody = "Name: $name\nEmail: $email\n\nMessage:\n$message"; // Plain text body for non-HTML email clients

        // Send the email
        $mail->send();
        http_response_code(200); // Set a 200 OK response code
        echo json_encode(["status" => "success", "message" => "Thank you! Your message has been sent."]);
    } catch (Exception $e) {
        http_response_code(500); // Set a 500 Internal Server Error response code
        echo json_encode(["status" => "error", "message" => "Oops! Something went wrong and we couldn't send your message. Mailer Error: {$mail->ErrorInfo}"]);
    }
} else {
    // Not a POST request
    http_response_code(403); // Set a 403 Forbidden response code
    echo json_encode(["status" => "error", "message" => "There was a problem with your submission, please try again."]);
}
?>