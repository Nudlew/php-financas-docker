<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/autoload.php';

function enviarEmailAviso($assunto, $mensagem)
{
    $mail = new PHPMailer(true);

    try {
        $smtpHost = getenv('SMTP_HOST');
        $smtpPort = (int) getenv('SMTP_PORT');
        $smtpUser = getenv('SMTP_USER');
        $smtpPass = getenv('SMTP_PASS');
        $fromEmail = getenv('SMTP_FROM_EMAIL');
        $fromName = getenv('SMTP_FROM_NAME') ?: 'Sistema';
        $mailTo = getenv('MAIL_TO');

        $mail->isSMTP();
        $mail->Host = $smtpHost;
        $mail->SMTPAuth = true;
        $mail->Username = $smtpUser;
        $mail->Password = $smtpPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $smtpPort;

        $mail->CharSet = 'UTF-8';
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($mailTo);

        $mail->isHTML(false);
        $mail->Subject = $assunto;
        $mail->Body = $mensagem;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Erro ao enviar e-mail: ' . $mail->ErrorInfo);
        return false;
    }
}
