<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../resources/db/PersonaDB.php';
require_once '../resources/db/UsuarioDB.php';
require_once '../resources/lib/sanitizacion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = sanitizacion($_POST['nombre']);
    $apellidos = sanitizacion($_POST['apellidos']);
    $telefono = sanitizacion($_POST['telefono']);
    $correo = sanitizacion($_POST['correo']);
    $usuario = sanitizacion($_POST['usuario']);
    $password = $_POST['password'];

    if (empty($nombre) || empty($apellidos) || empty($correo) || empty($usuario) || empty($password)) {
        $error = "Por favor, completa todos los campos obligatorios.";
    } else {
        $personaDB = new PersonaDB();
        $usuarioDB = new UsuarioDB();

        // 1. Insertar datos físicos con correo
        $persona_id = $personaDB->registrar($nombre, $apellidos, $telefono, $correo);

        if ($persona_id) {
            // 2. Insertar credenciales
            try {
                // Registrar (inserta activo = 0 por default)
                $usuario_id = $usuarioDB->registrar($persona_id, $usuario, $password);

                if ($usuario_id) {

                    // 3. Enviar correo de activación con PHPMailer
                    $phpmailer = new PHPMailer(true);
                    try {
                        $phpmailer->isSMTP();
                        $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
                        $phpmailer->SMTPAuth = true;
                        $phpmailer->Port = 2525;
                        $phpmailer->Username = 'ffb41d9b44cace';
                        $phpmailer->Password = '4cb962207e3c58';
                        $phpmailer->CharSet = 'UTF-8';

                        // Remitente y Destinatario
                        $phpmailer->setFrom('no-reply@nexuspass.com', 'Nexus Pass');
                        $phpmailer->addAddress($correo, $nombre . ' ' . $apellidos);

                        // Contenido
                        $phpmailer->isHTML(true);
                        $phpmailer->Subject = 'Activa tu cuenta Nexus Pass';

                        // Construir el enlace de forma dinámica sin importar la carpeta de hosting
                        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                        $base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
                        $linkActivacion = $base_url . "/usuario_activacion.php?id=" . $usuario_id;

                        $cuerpoHtml = "
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333;'>
                            <p>¡Hola {$nombre}!</p>
                            <p>Gracias por crear tu identidad digital. Para completar la apertura de tu cuenta con el usuario <strong>{$usuario}</strong>, por favor activa tu cuenta haciendo clic en el siguiente botón:</p>
                            <div style='margin: 30px 0;'>
                                <a href='{$linkActivacion}' style='background-color: #ff4d4f; color: #ffffff; padding: 15px 30px; text-decoration: none; font-weight: bold; border-radius: 5px; display: inline-block;'>Activar mi Cuenta</a>
                            </div>
                            <p>O copia y pega el siguiente enlace en tu navegador:</p>
                            <p>{$linkActivacion}</p>
                            <p>Desarrollado por Grupo Nexus.</p>
                        </div>
                        ";

                        $phpmailer->Body = $cuerpoHtml;
                        $phpmailer->AltBody = "Hola {$nombre}. Para activar tu cuenta, visita este enlace: {$linkActivacion}";

                        $phpmailer->send();

                        $exito = "Tu cuenta se creó exitosamente. Revisa tu correo ({$correo}) para activarla antes de iniciar sesión.";

                    } catch (Exception $e) {
                        $error = "La cuenta se creó, pero el correo de activación falló: {$phpmailer->ErrorInfo}";
                    }

                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $error = "El nombre de usuario o correo ya han sido registrados previamente.";
                } else {
                    $error = "Error lógico: " . $e->getMessage();
                }
            }
        } else {
            $error = "No se pudo crear la identidad personal. Verifica los datos ingresados.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include '../resources/templates/head.html'; ?>
</head>

<body class="index-page">

    <?php include '../resources/templates/header.html'; ?>

    <main class="main">
        <section class="section dark-background"
            style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding-top: 120px; padding-bottom: 60px;">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-10">
                        <div class="card p-5"
                            style="background: var(--surface-color); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.2);">

                            <div class="text-center mb-4">
                                <h3 style="font-family: var(--heading-font); font-weight: 700; font-size: 28px;">Crear
                                    Identidad Digital</h3>
                                <p style="color: #adb5bd; font-size: 15px;">Únete al ecosistema de Nexus Pass</p>
                            </div>

                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"
                                    style="background-color: rgba(220, 53, 69, 0.1); border-color: rgba(220, 53, 69, 0.3); color: #ea868f; border-radius: 10px; font-size: 14px;">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error; ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($exito)): ?>
                                <div class="alert alert-success"
                                    style="background-color: rgba(25, 135, 84, 0.1); border-color: rgba(25, 135, 84, 0.3); color: #75b798; border-radius: 10px; font-size: 14px;">
                                    <i class="bi bi-check-circle-fill me-2"></i> <?php echo $exito; ?>
                                </div>
                                <!-- Ocultar formulario si hubo éxito -->
                                <div class="text-center mt-5">
                                    <a href="login.php" class="btn btn-outline-light rounded-pill px-4">Ir al Iniciar
                                        Sesión</a>
                                </div>
                            <?php else: ?>

                                <form action="usuario_registro.php" method="POST">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label"
                                                style="font-weight: 600; font-size: 14px; color: #adb5bd;"
                                                for="nombre">Nombre(s) *</label>
                                            <input class="form-control" type="text" name="nombre" id="nombre" maxlength="50"
                                                style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color); border-radius: 10px; padding: 12px 15px;"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label"
                                                style="font-weight: 600; font-size: 14px; color: #adb5bd;"
                                                for="apellidos">Apellidos *</label>
                                            <input class="form-control" type="text" name="apellidos" id="apellidos" maxlength="100"
                                                style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color); border-radius: 10px; padding: 12px 15px;"
                                                required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" style="font-weight: 600; font-size: 14px; color: #adb5bd;"
                                            for="usuario">Nombre de Usuario (Nickname) *</label>
                                        <input class="form-control" type="text" name="usuario" id="usuario" maxlength="50"
                                            style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color); border-radius: 10px; padding: 12px 15px;"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" style="font-weight: 600; font-size: 14px; color: #adb5bd;"
                                            for="telefono">Teléfono</label>
                                        <input class="form-control" type="tel" name="telefono" id="telefono" maxlength="15"
                                            style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color); border-radius: 10px; padding: 12px 15px;">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" style="font-weight: 600; font-size: 14px; color: #adb5bd;"
                                            for="correo">Correo Electrónico *</label>
                                        <input class="form-control" type="email" name="correo" id="correo" maxlength="100"
                                            style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color); border-radius: 10px; padding: 12px 15px;"
                                            required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label" style="font-weight: 600; font-size: 14px; color: #adb5bd;"
                                            for="password">Contraseña Segura *</label>
                                        <input class="form-control" type="password" name="password" id="password" maxlength="100"
                                            style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color); border-radius: 10px; padding: 12px 15px;"
                                            required>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <button type="submit" class="btn btn-primary w-100 py-3"
                                            style="border-radius: 50px; background-color: var(--accent-color); border: none; font-weight: 600; font-size: 16px;">Generar
                                            Identidad Nexus</button>
                                    </div>
                                    <div class="mt-4 text-center">
                                        <p style="font-size: 14px; color: #adb5bd;">¿Ya estás registrado en la red? <br>
                                            <a href="login.php"
                                                style="color: var(--accent-color); text-decoration: none; font-weight: 600;">Inicia
                                                sesión aquí</a>
                                        </p>
                                    </div>
                                </form>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../resources/templates/footer.html'; ?>
    <?php include '../resources/templates/scripts.html'; ?>
</body>

</html>