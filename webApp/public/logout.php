<?php
session_start();

// Resguardamos el carrito antes de destruirlo
$cartBackup = isset($_SESSION['cart_contents']) ? $_SESSION['cart_contents'] : null;

session_unset();
session_destroy();
session_start();
session_regenerate_id(true);

// Restauramos el carrito en la nueva sesión
if ($cartBackup) {
    $_SESSION['cart_contents'] = $cartBackup;
}

// Eliminar cookies de sesión si existen
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

header("Location: index.php");
exit();
