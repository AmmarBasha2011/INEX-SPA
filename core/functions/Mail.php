<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * A class for sending emails using PHPMailer.
 */
class Mail
{
    /**
     * The PHPMailer instance.
     *
     * @var PHPMailer
     */
    private $mailer;

    /**
     * The last error message.
     *
     * @var string
     */
    public $errorInfo;

    /**
     * Creates a new Mail instance.
     */
    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->setup();
    }

    /**
     * Sets up the mailer with the configuration from the .env file.
     *
     * @throws Exception If the mailer setup fails.
     */
    private function setup()
    {
        try {
            $mailerType = getEnvValue('MAIL_MAILER') ?: 'smtp';

            if ($mailerType === 'smtp') {
                $this->mailer->isSMTP();
                $this->mailer->Host = getEnvValue('MAIL_HOST');
                $this->mailer->SMTPAuth = true;
                $this->mailer->Username = getEnvValue('MAIL_USERNAME');
                $this->mailer->Password = getEnvValue('MAIL_PASSWORD');
                $this->mailer->SMTPSecure = getEnvValue('MAIL_ENCRYPTION');
                $this->mailer->Port = getEnvValue('MAIL_PORT');
            } elseif ($mailerType === 'sendmail') {
                $this->mailer->isSendmail();
            } else {
                throw new Exception("Unsupported mailer type: {$mailerType}");
            }

            $this->mailer->setFrom(getEnvValue('MAIL_FROM_ADDRESS'), getEnvValue('MAIL_FROM_NAME'));
        } catch (Exception $e) {
            throw new Exception("PHPMailer setup failed: {$e->getMessage()}");
        }
    }

    /**
     * Adds a recipient to the email.
     *
     * @param string $email The recipient's email address.
     * @param string $name  The recipient's name.
     */
    public function addRecipient($email, $name = '')
    {
        $this->mailer->addAddress($email, $name);
    }

    /**
     * Sets the subject of the email.
     *
     * @param string $subject The subject of the email.
     */
    public function setSubject($subject)
    {
        $this->mailer->Subject = $subject;
    }

    /**
     * Sets the body of the email.
     *
     * @param string $body The body of the email.
     */
    public function setBody($body)
    {
        $this->mailer->Body = $body;
    }

    /**
     * Sets whether the email is HTML or not.
     *
     * @param bool $ishtml True if the email is HTML, false otherwise.
     */
    public function isHTML($ishtml)
    {
        $this->mailer->isHTML($ishtml);
    }

    /**
     * Sends the email.
     *
     * @return bool True on success, false on failure.
     */
    public function send()
    {
        try {
            $this->mailer->send();

            return true;
        } catch (Exception $e) {
            $this->errorInfo = $this->mailer->ErrorInfo;

            return false;
        }
    }
}
