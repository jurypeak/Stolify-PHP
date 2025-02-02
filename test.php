
// Include the Composer autoload file
require __DIR__ . '/vendor/autoload.php';  // Adjust the path if needed

use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;
use MailerSend\Exceptions\MailerSendException;

try {
    // Initialize MailerSend with the API key
    $mailersend = new MailerSend(['api_key' => '']); // Make sure to use your real API key

    // Define recipients
    $recipients = [
        new Recipient('jurypeak@gmail.com', 'Recipient Name'), // Replace with the correct recipient details
    ];

    // Personalization data: Add values for personalization keys
    $personalization = [
        new Personalization('jurypeak@gmail.com', [
            'name' => 'James', // Replace with actual data
            'account' => ['name' => 'Stolify'], // Replace with actual account name
            'support_email' => 'jurypeak@outlook.com'
        ]),
    ];

    // Define email parameters
    $emailParams = (new EmailParams())
        ->setFrom('MS_gveXs0@trial-o65qngkn7q8gwr12.mlsender.net') // Your from email address
        ->setFromName('Your Name') // Your name
        ->setRecipients($recipients) // Add the recipients
        ->setSubject('Reset Password') // Subject of the email
        ->setTemplateId('0r83ql3qn504zw1j') // Your template ID from MailerSend
        ->setPersonalization($personalization); // Pass the personalization data

    // Send the email
    $response = $mailersend->email->send($emailParams);

    // If no exceptions were thrown, the email was successfully sent
    echo "Email has been sent successfully!";
} catch (MailerSendException $e) {
    // Handle errors if the email could not be sent
    echo 'Error sending email: ' . $e->getMessage();
}


