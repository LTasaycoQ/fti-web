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
    $mail->isSMTP();
    $mail->Host       = "smtp.gmail.com";        
    $mail->SMTPAuth   = true;
    $mail->Username   = "luistasayco3030@gmail.com"; 
    $mail->Password   = "ukvsnclrgycsxhrh";      
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom("no-reply@terraandina.com", "Formulario TerraAndina"); 

    $mail->addReplyTo($email, $nombre);

    $mail->addAddress("luistasayco3030@gmail.com");

    $mail->addBCC($email);

    $mail->isHTML(false);
    $mail->Subject = $asunto;
    $mail->Body =
        "Nombre: $nombre\n" .
        "Email: $email\n\n" .
        "Mensaje:\n$mensaje\n\n" .
        "Este mensaje fue enviado desde el formulario de TerraAndina Hotel.";

    $mail->send();

    echo json_encode([
        "ok" => true,
        "message" => "Correo enviado correctamente y copia al cliente"
    ]);
} catch (Exception $e) {
    echo json_encode([
        "ok" => false,
        "error" => "No se pudo enviar el correo",
        "detail" => $mail->ErrorInfo
    ]);
}
