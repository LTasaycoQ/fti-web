<?php
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "ok" => false,
        "error" => "Método no permitido"
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

// O si usas form-data, puedes usar $_POST directo
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

$destinatario = "luistasayco3030@gmail.com"; // CAMBIA ESTO

$cabeceras = "From: $email\r\n";
$cabeceras .= "Reply-To: $email\r\n";
$cabeceras .= "Content-Type: text/plain; charset=UTF-8\r\n";

$contenido  = "Nombre: $nombre\n";
$contenido .= "Email: $email\n\n";
$contenido .= "Mensaje:\n$mensaje";

$enviado = mail($destinatario, $asunto, $contenido, $cabeceras);

if ($enviado) {
    echo json_encode([
        "ok" => true,
        "message" => "Mensaje enviado correctamente"
    ]);
} else {
    echo json_encode([
        "ok" => false,
        "error" => "No se pudo enviar el correo"
    ]);
}
