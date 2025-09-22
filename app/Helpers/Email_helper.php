<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once APPPATH . 'ThirdParty/PHPMailer/Exception.php';
require_once APPPATH . 'ThirdParty/PHPMailer/PHPMailer.php';
require_once APPPATH . 'ThirdParty/PHPMailer/SMTP.php';

function sendVerificationEmail($to, $subject, $bodyHtml)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username   = 'agonosmaybelle@gmail.com';
        $mail->Password   = 'vgkhmnbhyijirajt';// ✅ use Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Sender and receiver
        $mail->setFrom('agonosmaybelle@gmail.com', 'CSPC CHRE System');
        $mail->addAddress($to);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $bodyHtml;

        return $mail->send();
    } catch (Exception $e) {
        log_message('error', 'Email error: ' . $mail->ErrorInfo);
        return false;
    }
}
