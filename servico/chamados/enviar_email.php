<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require  __DIR__ . '/../PHPMailer/src/Exception.php';
require  __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require  __DIR__ . '/../PHPMailer/src/SMTP.php';

function enviarEmail($destino, $novaSenha)
{
    $timezone = new DateTimeZone('America/Sao_Paulo');
    $dataHora = new DateTime('now', $timezone);
    $horarioDeAlteracao = $dataHora->format('d/m/Y H:i:s');
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    $mail->Username = 'med.passcontato@gmail.com';
    $mail->Password = 'mnjh wwtr bsdh vidh';

    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Desabilitar verificação SSL (apenas desenvolvimento)
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];

    $mail->setFrom('med.passcontato@gmail.com', 'MedPass');

    $mail->addAddress($destino);

    $mail->isHTML(true);

    $mail->Subject = "Nova senha - MedPass";

    $mail->Body = "

    <h2>Recuperação de senha</h2>
    <p>Uma nova senha foi gerada para sua conta no dia <strong>$horarioDeAlteracao</strong> (horário de Brasília).</p>

    <p>Sua nova senha é:</p>

    <h3>$novaSenha</h3>

    <p><strong>Atenção:</strong> Esta senha é apenas temporária, para que você possa acessar sua conta, pois a senha original foi esquecida.</p>
    <p>Recomendamos alterar sua senha após o login para uma senha mais segura.</p>
    <p>Se você não solicitou essa alteração, recomendamos verificar sua conta e alterar sua senha imediatamente para proteger sua segurança.</p>
    <p>Se foi você que solicitou, ignore esta ultima mensagem.</p>

";

    $mail->send();
}
