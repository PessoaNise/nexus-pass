<?php
session_start();
include '../resources/db/UsuarioDB.php';

$usuario = $contrasenia = '';
$error = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = $_POST['usuario'];
    $contrasenia = $_POST['contrasenia'];

    $usuarioDB = new UsuarioDB();
    $userData = $usuarioDB->login($usuario, $contrasenia);
    
    if ($userData) {
        if ($userData['activo'] == 1) {
            $_SESSION['usuario'] = $userData['usuario'];
            $_SESSION['rol'] = $userData['rol'];
            $_SESSION['id_usuario'] = $userData['id'];
            
            if ($userData['rol'] == 'admin') {
                header("Location:administrador_vista.php");
            } else {
                if (isset($_GET['redirect']) && $_GET['redirect'] == 'checkout') {
                    header("Location: orden_pago.php");
                } else {
                    header("Location: catalogo.php");
                }
            }
            exit();
        } else {
            $error = "Tu cuenta aún no ha sido activada. Por favor revisa la bandeja de entrada de tu correo.";
        }
    } else { // Usuario o password inválido
        $error = "Credenciales incorrectas o el usuario no existe en la red.";
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
        <section class="section dark-background" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding-top: 120px;">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-8">
                        <div class="card p-5" style="background: var(--surface-color); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.2);">
                            
                            <div class="text-center mb-4">
                                <img src="assets/img/nexus-logo.png" alt="Nexus Logo" style="width: 80px; filter: invert(100%); opacity: 0.8; margin-bottom: 20px;">
                                <h3 style="font-family: var(--heading-font); font-weight: 700; font-size: 28px;">Iniciar Sesión</h3>
                                <p style="color: #adb5bd; font-size: 15px;">Accede al portal de Nexus Pass</p>
                            </div>

                            <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" style="background-color: rgba(220, 53, 69, 0.1); border-color: rgba(220, 53, 69, 0.3); color: #ea868f; border-radius: 10px; font-size: 14px;">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error; ?>
                            </div>
                            <?php endif; ?>

                            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                                <div class="mb-4">
                                    <label class="form-label" style="font-weight: 600; font-size: 14px; color: #adb5bd;" for="usuario">Nombre de Usuario</label>
                                    <input class="form-control" type="text" name="usuario" value="<?= htmlspecialchars($usuario) ?>" 
                                        style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color); border-radius: 10px; padding: 12px 15px;" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" style="font-weight: 600; font-size: 14px; color: #adb5bd;" for="contrasenia">Contraseña</label>
                                    <input class="form-control" type="password" name="contrasenia" 
                                        style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color); border-radius: 10px; padding: 12px 15px;" required>
                                </div>
                                <div class="mt-5 text-center">
                                    <button type="submit" class="btn btn-primary w-100 py-3" style="border-radius: 50px; background-color: var(--accent-color); border: none; font-weight: 600; font-size: 16px;">Acceder al Sistema</button>
                                </div>
                                <div class="mt-4 text-center">
                                    <p style="font-size: 14px; color: #adb5bd;">¿No tienes una cuenta aún? <br>
                                    <a href="usuario_registro.php" style="color: var(--accent-color); text-decoration: none; font-weight: 600;">Regístrate ahora</a></p>
                                </div>
                            </form>
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