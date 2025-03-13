<?php
require '../vendor/autoload.php';

use Dotenv\Dotenv;
use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;
use MailerSend\Exceptions\MailerSendException;

$response = array();

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {

    header('Content-Type: application/json');

    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

        $email = $_POST['email'];

        try {
            $mailersend = new MailerSend(['api_key' => $_ENV['API_KEY']]);  // Replace with your API key

            $recipients = [
                new Recipient($email, 'User'),
            ];

            $personalization = [
                new Personalization($email, [
                    'name' => 'User',
                    'account' => ['name' => 'Stolify'],
                    'support_email' => 'MS_gveXs0@trial-o65qngkn7q8gwr12.mlsender.net'
                ]),
            ];

            $emailParams = (new EmailParams())
                ->setFrom('MS_gveXs0@trial-o65qngkn7q8gwr12.mlsender.net')
                ->setFromName('Stolify Support')
                ->setRecipients($recipients)
                ->setSubject('Password Reset Request')
                ->setTemplateId('0r83ql3qn504zw1j')
                ->setPersonalization($personalization);

            $response = $mailersend->email->send($emailParams);
            echo json_encode(['status' => 'success', 'message' => 'Email has been sent successfully!']);
            exit;
        } catch (MailerSendException $e) {
            error_log('MailerSend Error: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error sending email: ' . $e->getMessage()]);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stolify</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../mobile.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>
    <script src="../miscellaneous/background.js"></script>
    <script src="../handlers/handleEmail.js"></script>
    <script src="../miscellaneous/utils.js"></script>
</head>
<body>

<div class="color-block">
    <header>
        <div class="logo-title">
            <img src="../media/logo.svg" alt="Logo" class="logo">
            <h1>Stolify</h1>
        </div>
        <h2 class="subheading">We may get sued ;)</h2>
    </header>

    <form id="resetPasswordForm">
        <div class="input-container">
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <input type="submit" value="Reset Password">
    </form>
    <a href="login.php" class="return-to-login">Go Back</a>
</div>

<footer>
    <a href="mailto:support@stolify.com" class="footer-email">support@stolify.com</a> |
    <p>&copy; <span id="year"></span> Stolify. All rights reserved.</p>
</footer>

</body>
</html>





