<?php
session_start();
include_once '../resources/db/CarroDB.php';
include_once '../resources/db/UsuarioDB.php'; // Para si queremos leer info

// Initialize shopping cart class
if (!isset($cart)) {
    $cart = new Cart;
}

// If the cart is empty, redirect to the products page
if ($cart->total_items() <= 0) {
    header("Location: index.php");
    exit();
}

$dir = [];
if (isset($_SESSION['id_usuario'])) {
    require_once '../resources/db/PersonaDB.php';
    $personaDBInfo = new PersonaDB();
    $dir = $personaDBInfo->getDireccionPorIdUsuario($_SESSION['id_usuario']);
    if (!$dir)
        $dir = [];
}

$requires_shipping = false;
foreach ($cart->contents() as $item) {
    if ($item['price'] > 0) {
        $requires_shipping = true;
        break;
    }
}

$PageTitle = "Checkout Seguro";

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include '../resources/templates/head.html'; ?>
    <style>
        .accordion-button {
            background-color: var(--surface-color);
            color: #ededed;
            font-family: var(--heading-font);
            font-weight: 600;
        }

        .accordion-button:not(.collapsed) {
            background-color: rgba(255, 77, 79, 0.1);
            color: var(--accent-color);
            box-shadow: none;
        }

        .accordion-button::after {
            filter: invert(1);
        }

        .accordion-item {
            background-color: var(--surface-color);
            border: 1px solid rgba(255, 255, 255, 0.05);
            margin-bottom: 10px;
            border-radius: 10px !important;
            overflow: hidden;
        }

        .accordion-body {
            background-color: rgba(0, 0, 0, 0.2);
            color: #adb5bd;
        }

        .form-control-dark {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .form-control-dark::placeholder {
            color: #adb5bd !important;
            opacity: 1;
        }

        .form-control-dark:-ms-input-placeholder {
            color: #adb5bd !important;
        }

        .form-control-dark::-ms-input-placeholder {
            color: #adb5bd !important;
        }

        .form-control-dark:focus {
            background-color: rgba(255, 255, 255, 0.08);
            border-color: var(--accent-color);
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(255, 77, 79, 0.25);
        }

        .form-control-dark option {
            background-color: var(--surface-color, #222);
            color: #fff;
        }
    </style>
</head>

<body class="index-page">
    <?php
    $logeado = false;
    if (isset($_SESSION['usuario'])) {
        if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'admin') {
            include '../resources/templates/administrador_navegacion.html';
        } else {
            include '../resources/templates/cliente_navegacion.html';
        }
        $logeado = true;
    } else {
        include '../resources/templates/header.html';
    }

    $statusMsg = '';
    $sessData = !empty($_SESSION['sessData']) ? $_SESSION['sessData'] : '';
    if (!empty($sessData['status']['msg'])) {
        $statusMsg = $sessData['status']['msg'];
        $statusMsgType = $sessData['status']['type'];
        unset($_SESSION['sessData']['status']);
    }

    ?>

    <main class="main">
        <section class="section dark-background" style="min-height: 80vh; padding-top: 130px; padding-bottom: 60px;">
            <div class="container" data-aos="fade-up">

                <div class="row mb-5">
                    <div class="col-12 text-center">
                        <h2
                            style="font-family: var(--heading-font); font-weight: 700; font-size: 34px; color: #ededed;">
                            Finalizar <span style="color: var(--accent-color);">Compra</span>
                        </h2>
                        <p style="color: #adb5bd;">Estás a unos pasos de unificar tu mundo.</p>
                    </div>
                </div>

                <?php if (!empty($statusMsg)): ?>
                    <div class="alert <?= ($statusMsgType == 'success') ? 'alert-success' : 'alert-danger' ?> alert-dismissible fade show"
                        role="alert">
                        <?= htmlspecialchars($statusMsg); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="post" action="../resources/lib/cartAction.php">
                    <input type="hidden" name="action" value="placeOrderWHOOP" />

                    <div class="row g-5">
                        <!-- IZQUIERDA: ACORDEÓN DE CHECKOUT -->
                        <div class="col-lg-7">
                            <div class="accordion" id="checkoutAccordion">

                                <!-- PASO 1: CUENTA -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                            <i class="bi bi-1-circle-fill me-2 fs-5"></i> 1. Tu Cuenta
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#checkoutAccordion">
                                        <div class="accordion-body p-4">
                                            <?php if ($logeado): ?>
                                                <div class="text-center py-3">
                                                    <i class="bi bi-check-circle-fill text-success"
                                                        style="font-size: 3rem;"></i>
                                                    <h4 class="mt-3 text-light">Has iniciado sesión</h4>
                                                    <p>Estás comprando como
                                                        <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong>
                                                    </p>
                                                    <button type="button"
                                                        class="btn btn-outline-light rounded-pill mt-2 px-4"
                                                        onclick="document.getElementById('headingTwo').querySelector('button').click();">Continuar
                                                        al Envío</button>
                                                </div>
                                            <?php else: ?>
                                                <p class="mb-4">Ingresa tu correo y define una contraseña para crear tu
                                                    cuenta rápidamente y gestionar tus pases posteriormente.</p>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label" style="color: #ededed;">Nombre de
                                                            Usuario</label>
                                                        <input type="text" name="usuario"
                                                            class="form-control form-control-dark"
                                                            placeholder="Ej. jperez12" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" style="color: #ededed;">Correo
                                                            Electrónico</label>
                                                        <input type="email" name="correo"
                                                            class="form-control form-control-dark"
                                                            placeholder="correo@ejemplo.com" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" style="color: #ededed;">Contraseña</label>
                                                        <input type="password" name="pwd"
                                                            class="form-control form-control-dark"
                                                            placeholder="Mínimo 8 caracteres" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" style="color: #ededed;">Confirmar
                                                            Contraseña</label>
                                                        <input type="password" name="pwd2"
                                                            class="form-control form-control-dark" required>
                                                    </div>
                                                </div>
                                                <div class="text-end mt-4">
                                                    <button type="button" class="btn btn-outline-light rounded-pill px-4"
                                                        onclick="document.getElementById('headingTwo').querySelector('button').click();">Siguiente
                                                        Paso <i class="bi bi-arrow-down"></i></button>
                                                </div>
                                                <div class="mt-3 text-center" style="font-size: 13px;">
                                                    ¿Ya tienes cuenta? <a href="login.php"
                                                        style="color: var(--accent-color);">Inicia Sesión aquí</a> antes de
                                                    continuar.
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- PASO 2: ENVÍO / DATOS -->
                                <div class="accordion-item" <?= ($logeado && !$requires_shipping) ? 'style="display: none;"' : '' ?>>
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                            aria-expanded="false" aria-controls="collapseTwo">
                                            <i
                                                class="bi <?= $requires_shipping ? 'bi-2-circle-fill' : 'bi-person-circle' ?> me-2 fs-5"></i>
                                            2. <?= $requires_shipping ? 'Datos de Envío' : 'Datos Personales' ?>
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                        aria-labelledby="headingTwo" data-bs-parent="#checkoutAccordion">
                                        <div class="accordion-body p-4">
                                            <?php if ($requires_shipping): ?>
                                                <p class="mb-4">Ingresa la dirección física a donde enviaremos tu pedido.
                                                </p>
                                            <?php else: ?>
                                                <p class="mb-4">Por favor completa tus datos personales.</p>
                                            <?php endif; ?>

                                            <div class="row g-3">
                                                <?php if (!$logeado): ?>
                                                    <div class="col-md-6">
                                                        <label class="form-label text-light">Nombres *</label>
                                                        <input type="text" name="nombre"
                                                            class="form-control form-control-dark" maxlength="50" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label text-light">Apellidos *</label>
                                                        <input type="text" name="apellidos"
                                                            class="form-control form-control-dark" maxlength="50" required>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label text-light">Teléfono Móvil *</label>
                                                        <input type="text" name="telefono"
                                                            class="form-control form-control-dark" maxlength="15" required>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($requires_shipping): ?>
                                                    <div class="col-md-8">
                                                        <label class="form-label text-light">Calle *</label>
                                                        <input type="text" name="calle"
                                                            class="form-control form-control-dark"
                                                            placeholder="Ej. Av. Reforma"
                                                            value="<?= htmlspecialchars($dir['calle'] ?? '') ?>" maxlength="100" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label text-light">No. Ext.</label>
                                                        <input type="text" name="noExterior"
                                                            class="form-control form-control-dark"
                                                            value="<?= htmlspecialchars($dir['numero_exterior'] ?? '') ?>" maxlength="20">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label text-light">No. Int.</label>
                                                        <input type="text" name="noInterior"
                                                            class="form-control form-control-dark"
                                                            value="<?= htmlspecialchars($dir['numero_interior'] ?? '') ?>" maxlength="20">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label text-light">Colonia *</label>
                                                        <input type="text" name="colonia"
                                                            class="form-control form-control-dark" placeholder="Ej. Centro"
                                                            value="<?= htmlspecialchars($dir['colonia'] ?? '') ?>" maxlength="100" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label text-light">Código Postal *</label>
                                                        <input type="text" name="cp" class="form-control form-control-dark"
                                                            placeholder="Ej. 12345"
                                                            value="<?= htmlspecialchars($dir['codigo_postal'] ?? '') ?>"
                                                            maxlength="10" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label text-light">Ciudad *</label>
                                                        <input type="text" name="ciudad"
                                                            class="form-control form-control-dark" placeholder="Ej. CDMX"
                                                            value="<?= htmlspecialchars($dir['ciudad'] ?? '') ?>" maxlength="100" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label text-light">Estado *</label>
                                                        <input type="text" name="estado"
                                                            class="form-control form-control-dark"
                                                            placeholder="Ej. Ciudad de México"
                                                            value="<?= htmlspecialchars($dir['estado'] ?? '') ?>" maxlength="100" required>
                                                    </div>
                                                <?php else: ?>
                                                    <input type="hidden" name="calle" value="N/A">
                                                    <input type="hidden" name="noExterior" value="N/A">
                                                    <input type="hidden" name="noInterior" value="N/A">
                                                    <input type="hidden" name="colonia" value="N/A">
                                                    <input type="hidden" name="cp" value="00000">
                                                    <input type="hidden" name="ciudad" value="N/A">
                                                    <input type="hidden" name="estado" value="N/A">
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-end mt-4">
                                                <button type="button" class="btn btn-outline-light rounded-pill px-4"
                                                    onclick="document.getElementById('headingThree').querySelector('button').click();">Siguiente
                                                    Paso <i class="bi bi-arrow-down"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PASO 3: PAGO -->
                                <?php if ($cart->total() > 0): ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingThree">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                aria-expanded="false" aria-controls="collapseThree">
                                                <i class="bi bi-3-circle-fill me-2 fs-5"></i> 3. Método de Pago
                                            </button>
                                        </h2>
                                        <div id="collapseThree" class="accordion-collapse collapse"
                                            aria-labelledby="headingThree" data-bs-parent="#checkoutAccordion">
                                            <div class="accordion-body p-4">

                                                <div class="mb-4 d-flex justify-content-between align-items-center">
                                                    <span>Tarjetas aceptadas:</span>
                                                    <div>
                                                        <img src="assets/img/visa.jpg" alt="Visa"
                                                            style="height: 30px; border-radius: 4px;" class="me-2">
                                                        <img src="assets/img/mastercard.jpg" alt="Mastercard"
                                                            style="height: 30px; border-radius: 4px;">
                                                    </div>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-12">
                                                        <label class="form-label text-light">Propietario de la Tarjeta
                                                            *</label>
                                                        <input type="text" name="propietario"
                                                            class="form-control form-control-dark"
                                                            placeholder="Como aparece en la tarjeta" maxlength="100" required>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label text-light">Número de Tarjeta *</label>
                                                        <input type="text" name="numTarjeta"
                                                            class="form-control form-control-dark"
                                                            placeholder="0000 0000 0000 0000" maxlength="16" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label text-light">Fecha de Expiración *</label>
                                                        <div class="d-flex">
                                                            <select class="form-select form-control-dark me-2" name="mes"
                                                                required>
                                                                <option value="" disabled selected>Mes</option>
                                                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                                                    <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>">
                                                                        <?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>
                                                                    </option>
                                                                <?php endfor; ?>
                                                            </select>
                                                            <select class="form-select form-control-dark" name="anio"
                                                                required>
                                                                <option value="" disabled selected>Año</option>
                                                                <?php for ($i = 24; $i <= 35; $i++): ?>
                                                                    <option value="<?= $i ?>">20<?= $i ?></option>
                                                                <?php endfor; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label text-light">Código de Seguridad (CVV)
                                                            *</label>
                                                        <input type="text" name="cvv" class="form-control form-control-dark"
                                                            placeholder="123" maxlength="4" required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>

                        <!-- DERECHA: RESUMEN DE COMPRA -->
                        <div class="col-lg-5">
                            <div class="card border-0 p-4"
                                style="background: var(--surface-color); border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); position: sticky; top: 100px;">
                                <h4 class="mb-4"
                                    style="color: #ededed; font-family: var(--heading-font); border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 15px;">
                                    Resumen de Pedido <span class="badge rounded-pill bg-secondary ms-2 text-white"
                                        style="font-size: 14px;"><?php echo $cart->total_items(); ?></span>
                                </h4>

                                <ul class="list-group list-group-flush mb-4 bg-transparent">
                                    <?php
                                    if ($cart->total_items() > 0):
                                        $cartItems = $cart->contents();
                                        foreach ($cartItems as $item):
                                            ?>
                                            <li class="list-group-item d-flex justify-content-between lh-condensed bg-transparent px-0"
                                                style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                                <div class="d-flex align-items-center">
                                                    <img src="../resources/uploads/<?= htmlspecialchars($item["image"]) ?>"
                                                        style="width: 40px; height: 40px; object-fit: contain; border-radius: 5px; background: rgba(255,255,255,0.02);"
                                                        class="me-3"
                                                        onerror="this.onerror=null; this.src='assets/img/nexus-logo.png';">
                                                    <div>
                                                        <h6 class="my-0 text-light"><?= htmlspecialchars($item["name"]) ?></h6>
                                                        <small style="color: #adb5bd;">Cantidad: <?= $item["qty"] ?> x
                                                            $<?= number_format($item["price"], 2) ?></small>
                                                    </div>
                                                </div>
                                                <span
                                                    class="text-light fw-bold align-self-center">$<?= number_format($item["subtotal"], 2) ?></span>
                                            </li>
                                        <?php endforeach; endif; ?>

                                    <li class="list-group-item d-flex justify-content-between bg-transparent px-0 py-3 mt-2"
                                        style="border-top: 2px solid rgba(255,255,255,0.1) !important;">
                                        <span style="color: #adb5bd;">Envío</span>
                                        <span class="text-success fw-bold">Gratis</span>
                                    </li>
                                    <li
                                        class="list-group-item d-flex justify-content-between bg-transparent px-0 py-3 border-0">
                                        <span style="font-size: 1.2rem; color: #ededed;">Gran Total</span>
                                        <strong
                                            style="font-size: 1.5rem; color: #75b798;">$<?= number_format($cart->total(), 2) ?></strong>
                                    </li>
                                </ul>

                                <button class="btn btn-primary btn-lg w-100 rounded-pill fw-bold" type="submit"
                                    style="background-color: var(--accent-color); border: none; font-size: 1.1rem; box-shadow: 0 5px 15px rgba(255,77,79,0.3);">
                                    <?= $cart->total() == 0 ? 'Activar Beneficios <i class="bi bi-star-fill ms-2"></i>' : 'Confirmar y Pagar <i class="bi bi-bag-check ms-2"></i>' ?>
                                </button>
                                <p class="text-center mt-3 mb-0" style="font-size: 12px; color: #adb5bd;">
                                    <i class="bi bi-lock-fill"></i> Tus datos están cifrados de extremo a extremo (SSL).
                                </p>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </section>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const checkForm = document.querySelector('form');
            if (checkForm) {
                checkForm.addEventListener('submit', function (e) {
                    // Validación tarjeta expirada
                    const mesSelect = document.querySelector('select[name="mes"]');
                    const anioSelect = document.querySelector('select[name="anio"]');
                    if (mesSelect && anioSelect && mesSelect.value && anioSelect.value) {
                        const today = new Date();
                        const currentYear = today.getFullYear() % 100;
                        const currentMonth = today.getMonth() + 1;

                        const expYear = parseInt(anioSelect.value);
                        const expMonth = parseInt(mesSelect.value);

                        if (expYear < currentYear || (expYear === currentYear && expMonth < currentMonth)) {
                            e.preventDefault();
                            e.stopPropagation();
                            const accordion = anioSelect.closest('.accordion-collapse');
                            if (accordion && !accordion.classList.contains('show')) {
                                new bootstrap.Collapse(accordion, { toggle: false }).show();
                            }
                            return Swal.fire({
                                icon: 'error',
                                title: 'Tarjeta Expirada',
                                text: 'La fecha de expiración de tu tarjeta ya ha pasado.',
                                confirmButtonColor: 'var(--accent-color)',
                                background: '#222222',
                                color: '#fff'
                            });
                        }
                    }

                    if (!checkForm.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();

                        const firstInvalid = checkForm.querySelector(':invalid');
                        if (firstInvalid) {
                            const accordion = firstInvalid.closest('.accordion-collapse');
                            if (accordion && !accordion.classList.contains('show')) {
                                const bsCollapse = new bootstrap.Collapse(accordion, { toggle: false });
                                bsCollapse.show();
                                setTimeout(() => { firstInvalid.focus(); }, 400);
                            } else {
                                firstInvalid.focus();
                            }

                            Swal.fire({
                                icon: 'warning',
                                title: 'Faltan Datos',
                                text: 'Por favor, completa correctamente los campos obligatorios.',
                                confirmButtonColor: 'var(--accent-color)',
                                background: '#222222',
                                color: '#fff'
                            });
                        }
                    }
                }, false);
            }
        });
    </script>

    <?php
    include '../resources/templates/footer.html';
    include '../resources/templates/scripts.html';
    include '../resources/templates/fin.html';
    ?>