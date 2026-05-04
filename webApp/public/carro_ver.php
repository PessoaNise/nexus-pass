<?php
session_start();
include_once '../resources/db/CarroDB.php';
include_once '../resources/db/ProductoDB.php';

// Initialize shopping cart class
if (!isset($cart)) {
    $cart = new Cart;
}

$PageTitle = "Tu Carrito";

$statusMsg = '';
$statusType = '';
if (!empty($_SESSION['sessData']['status']['msg'])) {
    $statusType = $_SESSION['sessData']['status']['type'];
    $statusMsg = $_SESSION['sessData']['status']['msg'];
    unset($_SESSION['sessData']['status']);
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include_once '../resources/templates/head.html'; ?>
</head>

<body class="index-page">
    <?php
    $logeado = false;
    if (isset($_SESSION['usuario'])) {
        if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'admin') {
            include_once '../resources/templates/administrador_navegacion.html';
        } else {
            include_once '../resources/templates/cliente_navegacion.html';
        }
        $logeado = true;
    } else {
        include_once '../resources/templates/header.html';
    }
    ?>

    <main class="main">
        <section class="section dark-background" style="min-height: 80vh; padding-top: 130px; padding-bottom: 60px;">
            <div class="container" data-aos="fade-up">

                <div class="row mb-4 pb-2" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <div class="col-12">
                        <h2
                            style="font-family: var(--heading-font); font-weight: 700; font-size: 34px; color: #ededed;">
                            Mi <span style="color: var(--accent-color);">Carrito</span>
                        </h2>
                        <p style="color: #adb5bd; font-size: 16px;">Revisa los elementos antes de proceder a la
                            configuración de cuenta y pago.</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card bg-transparent border-0"
                            style="background: var(--surface-color) !important; border: 1px solid rgba(255,255,255,0.05) !important; border-radius: 20px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.5); padding: 20px;">

                            <div class="table-responsive">
                                <table class="table table-dark table-hover align-middle"
                                    style="background: transparent;">
                                    <thead>
                                        <tr style="border-bottom: 2px solid rgba(255,255,255,0.1);">
                                            <th width="12%"
                                                class="text-center bg-transparent text-secondary border-0 pb-3">Imagen
                                            </th>
                                            <th width="33%" class="bg-transparent text-secondary border-0 pb-3">Producto
                                            </th>
                                            <th width="15%"
                                                class="text-center bg-transparent text-secondary border-0 pb-3">Precio
                                            </th>
                                            <th width="15%"
                                                class="text-center bg-transparent text-secondary border-0 pb-3">Cantidad
                                            </th>
                                            <th width="15%"
                                                class="text-center bg-transparent text-secondary border-0 pb-3">Subtotal
                                            </th>
                                            <th width="10%"
                                                class="text-center bg-transparent text-secondary border-0 pb-3"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($cart->total_items() > 0):
                                            $cartItems = $cart->contents();
                                            foreach ($cartItems as $item):
                                                ?>
                                                <tr>
                                                    <td class="text-center bg-transparent"
                                                        style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                                        <div
                                                            style="background-color: rgba(255,255,255,0.02); border-radius: 10px; padding: 5px;">
                                                            <img src="../resources/uploads/<?= htmlspecialchars($item["image"]) ?>"
                                                                style="width: 60px; height: 60px; object-fit: contain;"
                                                                alt="<?= htmlspecialchars($item["name"]) ?>"
                                                                onerror="this.onerror=null; this.src='assets/img/nexus-logo.png'; this.style.filter='invert(50%)';">
                                                        </div>
                                                    </td>
                                                    <td class="bg-transparent text-light fw-bold"
                                                        style="border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 1.1rem;">
                                                        <?= htmlspecialchars($item["name"]) ?></td>
                                                    <td class="text-center bg-transparent text-light"
                                                        style="border-bottom: 1px solid rgba(255,255,255,0.05);">$
                                                        <?= number_format($item["price"], 2) ?></td>
                                                    <td class="text-center bg-transparent"
                                                        style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                                        <?php
                                                        $producto_db = ProductoDB::getProductoPorId($item['id']);
                                                        $is_membresia = ($producto_db['categoria_id'] == 1);
                                                        $max_stock = $producto_db['stock'];
                                                        ?>
                                                        <?php if ($is_membresia || $producto_db['precio'] == 0): ?>
                                                            <input class="form-control form-control-sm text-center mx-auto"
                                                                type="number" value="<?= $item["qty"]; ?>" readonly
                                                                style="width: 70px; background-color: rgba(255,255,255,0.05); color: #adb5bd; border: 1px solid rgba(255,255,255,0.2); border-radius: 5px; cursor: not-allowed;"
                                                                title="<?= $is_membresia ? 'Suscripción única' : 'Beneficio único' ?>" />
                                                        <?php else: ?>
                                                            <input class="form-control form-control-sm text-center mx-auto"
                                                                type="number" value="<?= $item["qty"]; ?>" max="<?= $max_stock ?>"
                                                                min="1"
                                                                oninput="if(this.value && parseInt(this.value) > parseInt(this.max)) this.value = this.max; if(this.value && parseInt(this.value) < parseInt(this.min)) this.value = this.min;"
                                                                onchange="updateCartItem(this, '<?= $item["rowid"] ?>')"
                                                                style="width: 70px; background-color: rgba(255,255,255,0.05); color: #fff; border: 1px solid rgba(255,255,255,0.2); border-radius: 5px;" />
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center bg-transparent text-success fw-bold"
                                                        style="border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 1.1rem;">
                                                        $ <?= number_format($item["subtotal"], 2) ?></td>
                                                    <td class="text-center bg-transparent"
                                                        style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                                        <button class="btn btn-sm text-danger"
                                                            onclick="return confirm('¿Estás seguro de eliminar este artículo?')?window.location.href='../resources/lib/cartAction.php?action=removeCartItem&id=<?= $item["rowid"]; ?>':false;"
                                                            title="Eliminar del Carrito"
                                                            style="background: rgba(220,53,69,0.1); border-radius: 50%;">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center py-5 bg-transparent border-0">
                                                    <i class="bi bi-cart-x"
                                                        style="font-size: 3rem; color: rgba(255,255,255,0.2);"></i>
                                                    <h4 class="mt-3"
                                                        style="font-family: var(--heading-font); color: #ededed;">Tu carrito
                                                        está vacío</h4>
                                                    <p style="color: var(--accent-color);">Parece que aún no has agregado
                                                        nada al carrito.</p>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <?php if ($cart->total_items() > 0): ?>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="bg-transparent border-0"></td>
                                                <td class="text-end bg-transparent border-0 text-secondary pe-4"
                                                    style="font-size: 1.2rem;"><strong>TOTAL:</strong></td>
                                                <td colspan="2" class="bg-transparent border-0 text-success"
                                                    style="font-size: 1.5rem; font-weight: 800;">
                                                    $<?= number_format($cart->total(), 2) ?></td>
                                            </tr>
                                        </tfoot>
                                    <?php endif; ?>
                                </table>
                            </div>

                            <div class="row mt-4 pt-4" style="border-top: 1px solid rgba(255,255,255,0.05);">
                                <div class="col-sm-12 col-md-6 mb-3 mb-md-0">
                                    <a href="catalogo.php" class="btn btn-outline-light rounded-pill py-2 px-4"><i
                                            class="bi bi-arrow-left me-2"></i>Seguir comprando</a>
                                </div>
                                <div class="col-sm-12 col-md-6 text-md-end">
                                    <?php if ($cart->total_items() > 0): ?>
                                        <a href="orden_pago.php" class="btn btn-primary rounded-pill py-2 px-5 fw-bold"
                                            style="background-color: var(--accent-color); border: none; font-size: 1.1rem; box-shadow: 0 4px 15px rgba(255,77,79,0.3);">
                                            <?= $cart->total() == 0 ? 'Activar Beneficios' : 'Finalizar Compra' ?> <i class="bi bi-arrow-right ms-2"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <?php
    include_once '../resources/templates/footer.html';
    include_once '../resources/templates/scripts.html';
    ?>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (!empty($statusMsg)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: '<?= $statusType == 'error' ? 'error' : 'success' ?>',
                    title: '<?= $statusType == 'error' ? '¡Atención!' : '¡Éxito!' ?>',
                    text: '<?= addslashes($statusMsg) ?>',
                    confirmButtonColor: 'var(--accent-color)',
                    background: '#222222',
                    color: '#fff'
                });
            });
        </script>
    <?php endif; ?>

    <script>
        function updateCartItem(obj, id) {
            if (!obj.value || isNaN(obj.value) || obj.value <= 0) return;
            fetch(`../resources/lib/cartAction.php?action=updateCartItem&id=${id}&qty=${obj.value}`)
                .then(response => response.text())
                .then(data => {
                    if (data === 'ok') {
                        location.reload();
                    } else if (data === 'err_stock') {
                        Swal.fire({ icon: 'error', title: 'Inventario excedido', text: 'No hay suficiente cantidad disponible para este producto.', confirmButtonColor: 'var(--accent-color)', background: '#222222', color: '#fff' })
                            .then(() => location.reload());
                    } else if (data === 'err_membresia') {
                        Swal.fire({ icon: 'error', title: 'Membresía Limitada', text: 'Las membresías son limitadas a 1 por cada usuario y no son acumulables.', confirmButtonColor: 'var(--accent-color)', background: '#222222', color: '#fff' })
                            .then(() => location.reload());
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo actualizar la cantidad. Inténtalo de nuevo.', confirmButtonColor: 'var(--accent-color)', background: '#222222', color: '#fff' })
                            .then(() => location.reload());
                    }
                })
                .catch(error => {
                    Swal.fire({ icon: 'error', title: 'Error de Red', text: 'Hubo un problema de conexión.', confirmButtonColor: 'var(--accent-color)', background: '#222222', color: '#fff' })
                        .then(() => location.reload());
                });
        }
    </script>

    <?php
    include_once '../resources/templates/fin.html';
    ?>