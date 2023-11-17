<?php

namespace App\Models;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer {

    private PHPMailer $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);

        $this->mailer->SMTPDebug = 0;
        $this->mailer->isSMTP();
        $this->mailer->Host = $_ENV["MAIL_HOST"];
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $_ENV["MAIL_USERNAME"];
        $this->mailer->Password = $_ENV["MAIL_PASSWORD"];
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mailer->Port = 465;
    }

    public function renderTemplate(string $path, array $data) {
        extract($data);
        ob_start();
        include '../views/'.$path;
        return ob_get_clean();
    }

    public function sendMail(string $mailAddress, string $subject, string $template, array $data) {
        try {
            $this->mailer->setFrom($_ENV["MAIL_USERNAME"], 'OG-GG');
            $this->mailer->addAddress($mailAddress);
    
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $this->renderTemplate($template, $data);
    
            $this->mailer->send();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

}