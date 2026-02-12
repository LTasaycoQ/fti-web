<?php

header("Content-Type: application/json");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/vendor/autoload.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["ok" => false, "error" => "Método no permitido"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$nombre  = trim($data["nombre"] ?? "");
$email   = trim($data["email"] ?? "");
$asunto  = trim($data["asunto"] ?? "");
$mensaje = trim($data["mensaje"] ?? "");

if (!$nombre || !$email || !$asunto || !$mensaje) {
    echo json_encode(["ok" => false, "error" => "Faltan campos"]);
    exit;
}

// Sanitizar básico
$nombre  = htmlspecialchars($nombre, ENT_QUOTES, "UTF-8");
$email   = filter_var($email, FILTER_SANITIZE_EMAIL);
$asunto  = htmlspecialchars($asunto, ENT_QUOTES, "UTF-8");
$mensaje = htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8");

// ---------- CONFIG SMTP ----------
function crearMailer() {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = "smtp.gmail.com";
    $mail->SMTPAuth   = true;
    $mail->Username   = "luistasayco3030@gmail.com";
    $mail->Password   = "ukvsnclrgycsxhrh";   
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->isHTML(false);
    return $mail;
}

try {
    $mail1 = crearMailer();

    // Con Gmail debes usar tu propio correo como From
    $mail1->setFrom("luistasayco3030@gmail.com", "Formulario TerraAndina");

    $mail1->addReplyTo($email, $nombre);

    // Destinatario principal
    $mail1->addAddress("luis.tasayco@vallegrande.edu.pe");

    $mail1->Subject = $asunto;
    $mail1->Body =
        "Nueva Consulta - WEB Terra Andina Mansion Colonial:\n\n" .
        "Nombre: $nombre\n" .
        "Email: $email\n\n" .
        "Mensaje:\n$mensaje\n";

    $mail1->send();

    $mail2 = crearMailer();

    $mail2->setFrom("luistasayco3030@gmail.com", "Formulario TerraAndina");
    $mail2->addAddress($email, $nombre);

    $mail2->Subject = "Hemos recibido tu consulta";
    $mail2->Body =
        "Hola $nombre,\n\n" .
        "Hemos recibido tu mensaje correctamente. Esto es lo que nos enviaste:\n\n" .
        "Asunto: $asunto\n\n" .
        "Mensaje:\n$mensaje\n\n" .
        "Pronto nos pondremos en contacto contigo.\n\n" .
        "— TerraAndina Hotel";

    $mail2->send();

    echo json_encode([
        "ok" => true,
        "message" => "Mensaje enviado al destinatario y confirmación al cliente"
    ]);
} catch (Exception $e) {
    echo json_encode([
        "ok" => false,
        "error" => "No se pudo enviar el correo",
        "detail" => $e->getMessage()
    ]);
}
