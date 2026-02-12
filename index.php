<?php
header("Content-Type: application/json");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/vendor/autoload.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "ok" => false,
        "error" => "Método no permitido"
    ]);
    exit;
}

// Leer JSON del body
$data = json_decode(file_get_contents("php://input"), true);

$nombre  = htmlspecialchars($data["nombre"] ?? "");
$email   = htmlspecialchars($data["email"] ?? "");
$asunto  = htmlspecialchars($data["asunto"] ?? "");
$mensaje = htmlspecialchars($data["mensaje"] ?? "");

if (!$nombre || !$email || !$asunto || !$mensaje) {
    echo json_encode([
        "ok" => false,
        "error" => "Faltan campos"
    ]);
    exit;
}

$mail = new PHPMailer(true);

try {
    // Configuración SMTP
    $mail->isSMTP();
    $mail->Host       = "smtp.gmail.com";
    $mail->SMTPAuth   = true;
    $mail->Username   = "luistasayco3030@gmail.com";   // TU GMAIL
    $mail->Password   = "ukvsnclrgycsxhrh";            // TU APP PASSWORD
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // =========================
    // 1) CORREO PARA TI
    // =========================
    $mail->setFrom("luistasayco3030@gmail.com", "Mi Web");
    $mail->addAddress("luistasayco3030@gmail.com"); // donde recibes
    $mail->addReplyTo($email, $nombre); // para responder al usuario

    $mail->isHTML(false);
    $mail->Subject = $asunto;
    $mail->Body =
        "Nombre: $nombre\n" .
        "Email: $email\n\n" .
        "Mensaje:\n$mensaje";

    $mail->send();

    // =========================
    // 2) CORREO DE CONFIRMACIÓN AL USUARIO
    // =========================
    $mail->clearAddresses();
    $mail->clearReplyTos();

    $mail->setFrom("luistasayco3030@gmail.com", "Mi Web");
    $mail->addAddress($email, $nombre);

    $mail->Subject = "Hemos recibido tu mensaje";
    $mail->Body =
        "Hola $nombre,\n\n" .
        "Gracias por contactarnos. Hemos recibido tu mensaje correctamente y te responderemos lo antes posible.\n\n" .
        "Resumen de tu mensaje:\n" .
        "Asunto: $asunto\n\n" .
        "$mensaje\n\n" .
        "Saludos,\nMi Web";

    $mail->send();

    echo json_encode([
        "ok" => true,
        "message" => "Mensaje enviado y confirmación enviada al usuario"
    ]);
} catch (Exception $e) {
    echo json_encode([
        "ok" => false,
        "error" => "No se pudo enviar el correo",
        "detail" => $mail->ErrorInfo
    ]);
}
