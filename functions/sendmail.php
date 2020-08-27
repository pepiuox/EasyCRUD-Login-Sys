<?php
require 'PHPMailer/src/PHPMailer.php';

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = 'shared36.accountservergroup.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'contact@labemotion.net';
    $mail->Password = '3n^@N3TRYe3h';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('from@gfg.com', 'Name');
    $mail->addAddress('receiver1@gfg.com');
    $mail->addAddress('receiver2@gfg.com', 'Name');

    $mail->isHTML(true);
    $mail->Subject = 'Subject';
    $mail->Body = 'HTML message body in <b>bold</b> ';
    $mail->AltBody = 'Body in plain text for non-HTML mail clients';
    $mail->send();
    echo "Mail has been sent successfully!";
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?> 
