<?php
// Start output buffering to prevent any unwanted output before JSON response.
ob_start();

// Include Composer's autoload file (make sure MailerSend is installed using Composer)
require __DIR__ . '/vendor/autoload.php';  // Adjust path if needed

// Use statements to include classes from MailerSend
use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;
use MailerSend\Exceptions\MailerSendException;

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {

    header('Content-Type: application/json');

    // Validate the email
    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

        $email = $_POST['email'];

        try {
            // Initialize MailerSend with your API key
            $mailersend = new MailerSend(['api_key' => '']);  // Replace with your API key

            // Define recipient(s)
            $recipients = [
                new Recipient($email, 'User'),
            ];

            // Personalize email content
            $personalization = [
                new Personalization($email, [
                    'name' => 'User',
                    'account' => ['name' => 'Stolify'],
                    'support_email' => 'MS_gveXs0@trial-o65qngkn7q8gwr12.mlsender.net'
                ]),
            ];

            // Define email parameters
            $emailParams = (new EmailParams())
                ->setFrom('MS_gveXs0@trial-o65qngkn7q8gwr12.mlsender.net')
                ->setFromName('Stolify Support')
                ->setRecipients($recipients)
                ->setSubject('Password Reset Request')
                ->setTemplateId('0r83ql3qn504zw1j')
                ->setPersonalization($personalization);

            // Send the email
            $response = $mailersend->email->send($emailParams);

            // Return success response
            echo json_encode(['status' => 'success', 'message' => 'Email has been sent successfully!']);
            exit;
        } catch (MailerSendException $e) {
            // Log the error and return failure response
            error_log('MailerSend Error: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error sending email: ' . $e->getMessage()]);
            exit;
        }
    } else {
        // Return error for invalid email
        echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
        exit;
    }
}

ob_end_flush();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stolify</title>
    <!-- Include jQuery (needed for toastr to work with AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Your custom JS -->
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="style.css"> <!-- Link to the external CSS file -->
</head>
<body>

<div class="color-block">
    <header>
        <div class="logo-title">
            <img src="media/logo.svg" alt="Logo" class="logo">
            <h1>Stolify</h1>
        </div>
        <h2 class="subheading">We may get sued ;)</h2>
    </header>

    <!-- Email reset form -->
    <form id="resetPasswordForm">
        <input type="email" id="email" name="email" placeholder="Enter your email" required><br><br>
        <input type="submit" value="Send Link">
    </form>
    <a href="index.php" class="return-to-index">Go Back</a>
</div>

<!-- Footer -->
<footer>
    <a href="mailto:support@stolify.com" class="footer-email">support@stolify.com</a> |
    <p>&copy; <span id="year"></span> Stolify. All rights reserved.</p>
</footer>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>

</body>
</html>





