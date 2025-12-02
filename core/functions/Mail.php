<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    private $mailer;
    public $errorInfo;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->setup();
    }

    private function setup()
    {
        try {
            $mailerType = getEnvValue('MAIL_MAILER') ?: 'smtp';

            if ($mailerType === 'smtp') {
                $this->mailer->isSMTP();
                $this->mailer->Host       = getEnvValue('MAIL_HOST');
                $this->mailer->SMTPAuth   = true;
                $this->mailer->Username   = getEnvValue('MAIL_USERNAME');
                $this->mailer->Password   = getEnvValue('MAIL_PASSWORD');
                $this->mailer->SMTPSecure = getEnvValue('MAIL_ENCRYPTION');
                $this->mailer->Port       = getEnvValue('MAIL_PORT');
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

    public function addRecipient($email, $name = '')
    {
        $this->mailer->addAddress($email, $name);
    }

    public function setSubject($subject)
    {
        $this->mailer->Subject = $subject;
    }

    public function setBody($body)
    {
        $this->mailer->Body = $body;
    }

    public function isHTML($ishtml)
    {
        $this->mailer->isHTML($ishtml);
    }

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
