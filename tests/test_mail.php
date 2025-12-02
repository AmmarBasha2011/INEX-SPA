<?php

require_once __DIR__.'/../core/import/PHPMailer/init.php';
require_once __DIR__.'/../core/functions/PHP/getEnvValue.php';
require_once __DIR__.'/../core/functions/Mail.php';

$envBackup = null;
if (file_exists('.env')) {
    $envBackup = file_get_contents('.env');
}

// Create a dummy .env file for testing
file_put_contents('.env', '
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=testuser
MAIL_PASSWORD=testpass
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=test@example.com
MAIL_FROM_NAME=Test
');

// Instantiate the Mail class
$mail = new Mail();

// Configure the mailer
$mail->addRecipient('recipient@example.com', 'Recipient');
$mail->setSubject('Test Subject');
$mail->setBody('Test Body');
$mail->isHTML(false);

// Assert that send() returns false with dummy credentials
if ($mail->send() === false) {
    echo "Test passed!\n";
} else {
    echo "Test failed!\n";
}

// Clean up the dummy .env file
unlink('.env');

if ($envBackup !== null) {
    file_put_contents('.env', $envBackup);
}
