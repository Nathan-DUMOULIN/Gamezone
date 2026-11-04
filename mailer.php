<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/phpmailer/Exception.php';
require_once __DIR__ . '/phpmailer/PHPMailer.php';
require_once __DIR__ . '/phpmailer/SMTP.php';

function envoiMail($destinataire, $objet, $message)
{
    $mail = new PHPMailer(true);

    try {
        // Configuration SMTP Hostinger
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'contact@siolapie.com';
        $mail->Password   = '3+oNjEt1/';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Exp�diteur
        $mail->setFrom('contact@siolapie.com', 'BTS SIO');

        // Destinataire
        $mail->addAddress($destinataire);

        // Contenu
        $mail->Subject = $objet;
        $mail->Body    = $message;
        $mail->CharSet = 'UTF-8';

        // Envoi
        $mail->send();

        return true;

    } catch (Exception $e) {

        // Pour le d�veloppement uniquement
        // echo "Erreur : " . $mail->ErrorInfo;

        return false;
    }
}
