<?php
if (!session_id()) {
    session_start();
}
include_once '../resources/db/UsuarioDB.php';

$message = '';
$type = '';

if (!empty($_GET["id"])) {
    $resultado = UsuarioDB::activaUsuarioById($_GET["id"]);

    if ($resultado) {
        $message = "¡Felicidades! Tu cuenta ha sido activada exitosamente.";
        $type = "success";
    } else {
        $message = "El enlace de activación no es válido o la cuenta ya estaba activada.";
        $type = "danger";
    }
} else {
    $message = "ID de cuenta no proporcionado.";
    $type = "warning";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include_once '../resources/templates/head.html'; ?>
</head>

<body class="index-page">

    <?php include_once '../resources/templates/header.html'; ?>

    <main class="main">
        <section class="section dark-background"
            style="min-height: 80vh; display: flex; align-items: center; justify-content: center;">
            <div class="container" data-aos="fade-up">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-8 text-center">
                        <div class="card p-5"
                            style="background: var(--surface-color); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.2);">

                            <div class="mb-4">
                                <?php if ($type == 'success'): ?>
                                    <div
                                        style="background-color: rgba(25, 135, 84, 0.1); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: #75b798; font-size: 40px;">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                <?php elseif ($type == 'danger' || $type == 'error'): ?>
                                    <div
                                        style="background-color: rgba(220, 53, 69, 0.1); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: #ea868f; font-size: 40px;">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </div>
                                <?php else: ?>
                                    <div
                                        style="background-color: rgba(255, 193, 7, 0.1); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: #ffc107; font-size: 40px;">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <h3 style="font-family: var(--heading-font); font-weight: 700; font-size: 24px; color: #ededed;"
                                class="mb-3">
                                Activación de Cuenta
                            </h3>

                            <p style="color: #adb5bd; font-size: 15px;" class="mb-4">
                                <?php echo $message; ?>
                            </p>

                            <?php if ($type == 'success'): ?>
                                <a href="login.php" class="btn btn-primary px-5 py-2"
                                    style="border-radius: 50px; background-color: var(--accent-color); border: none; font-weight: 600;">Ir
                                    al Iniciar Sesión</a>
                            <?php else: ?>
                                <a href="index.php" class="btn btn-outline-light px-5 py-2"
                                    style="border-radius: 50px;">Volver al Inicio</a>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include_once '../resources/templates/footer.html'; ?>
    <?php include_once '../resources/templates/scripts.html'; ?>
</body>

</html>