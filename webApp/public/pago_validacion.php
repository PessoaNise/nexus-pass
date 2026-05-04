<?php

session_start();
if (isset($_SESSION['usuario'])) {

    include '../resources/db/PedidoDB.php';
    include '../resources/db/ItemPedidoDB.php';

    if (!empty($_REQUEST['id'])) {
        $order_id = base64_decode($_REQUEST['id']);
        $orderData = PedidoDB::getDatosPersonaOrdenPorIdOrden($order_id);
    }

    if (empty($orderData)) {
        header("Location: index.php");
        exit();
    }

    $PageTitle = "Pagar";

    include '../resources/templates/head.html';
include '../resources/templates/cliente_navegacion.html';

    ?>

    <main class="main">
        <section class="section dark-background" style="min-height: 80vh; padding-top: 100px; padding-bottom: 60px;">
            <div class="container" data-aos="fade-up">

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                    <?php if (!empty($orderData)) : ?>
                        <!-- Alerta de éxito -->
                        <div class="alert text-center mb-5" style="background: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.3); color: #28a745; border-radius: 15px; font-weight: 500; backdrop-filter: blur(10px);">
                            <i class="bi bi-check-circle-fill fs-4 me-2"></i> Tu orden ha sido procesada exitosamente
                        </div>

                        <!-- Card de Recibo (Glassmorphism) -->
                        <div class="card p-4 p-md-5" style="background: var(--surface-color); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.2);">
                            
                            <!-- Header Info -->
                            <div class="row mb-5 border-bottom pb-4" style="border-color: rgba(255,255,255,0.1) !important;">
                                <div class="col-md-6 mb-4 mb-md-0">
                                    <h5 style="color: var(--accent-color); font-family: var(--heading-font); font-weight: 700; text-transform: uppercase; letter-spacing: 2px; font-size: 14px;">Resumen de la Orden</h5>
                                    <h3 style="color: #ededed; font-family: var(--heading-font); font-weight: 600;">Ref: #<?= $orderData['id']; ?></h3>
                                    <p style="color: #adb5bd; margin-bottom: 0;">Fecha: <?= $orderData['fecha']; ?></p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <h5 style="color: #ededed; font-family: var(--heading-font); font-size: 15px; margin-bottom: 5px;">Datos del Cliente</h5>
                                    <p style="color: #adb5bd; margin-bottom: 0; font-size: 14px;">
                                        <strong><?= $orderData['nombre'] . ' ' . $orderData['a_paterno']; ?></strong><br>
                                        <?= $orderData['correo_electronico']; ?>
                                        <?php if (!empty($orderData['calle'])): ?>
                                            <br><?= htmlspecialchars($orderData['calle']); ?><?= !empty($orderData['numero']) ? ' #' . htmlspecialchars($orderData['numero']) : '' ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Tabla de Productos -->
                            <div class="table-responsive mb-4">
                                <table class="table text-light" style="--bs-table-bg: transparent; --bs-table-color: #ededed;">
                                    <thead>
                                        <tr style="border-bottom: 2px solid rgba(255,255,255,0.1);">
                                            <th class="border-0 text-uppercase" style="color: #adb5bd; font-size: 12px; letter-spacing: 1px;">Producto</th>
                                            <th class="border-0 text-uppercase text-center" style="color: #adb5bd; font-size: 12px; letter-spacing: 1px;">Precio</th>
                                            <th class="border-0 text-uppercase text-center" style="color: #adb5bd; font-size: 12px; letter-spacing: 1px;">Cant.</th>
                                            <th class="border-0 text-uppercase text-end" style="color: #adb5bd; font-size: 12px; letter-spacing: 1px;">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $items = ItemPedidoDB::getDatosItemsOrdenPorIdOrden($orderData['id']);
                                        foreach ($items as $item) : ?>
                                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                                <td class="align-middle border-0 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <img src="../resources/uploads/<?= $item["nombre_archivo"] ?>" width="60" alt="..." style="border-radius: 8px; margin-right: 15px; background: rgba(255,255,255,0.02); padding: 5px;">
                                                        <span style="font-weight: 500;"><?= $item["nombre"]; ?></span>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center border-0 py-3" style="color: #adb5bd;">$<?= number_format($item['precio'], 2) ?></td>
                                                <td class="align-middle text-center border-0 py-3" style="color: #adb5bd;"><?= $item['cantidad'] ?></td>
                                                <td class="align-middle text-end border-0 py-3" style="font-weight: 600; color: #ededed;">$<?= number_format($item['precio'] * $item['cantidad'], 2) ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end border-0 pt-4" style="font-size: 18px; color: #adb5bd;">Total Pagado</td>
                                            <td class="text-end border-0 pt-4" style="font-size: 22px; font-weight: 700; color: var(--accent-color);">$<?= number_format($orderData['total'], 2); ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-5 pt-4 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                                <a href="catalogo.php" class="btn btn-outline-light mb-3 mb-sm-0 px-4 py-2" style="border-radius: 50px; font-weight: 500;">
                                    <i class="bi bi-arrow-left me-2"></i> Seguir Comprando
                                </a>
                                
                                <form action="ticket_generar.php" method="get" target="_blank" class="m-0">
                                    <input type="hidden" name="idOrden" value="<?= $order_id ?>">
                                    <button type="submit" class="btn px-4 py-2" style="background: var(--accent-color); color: var(--background-color); border: none; border-radius: 50px; font-weight: 600; transition: 0.3s; box-shadow: 0 4px 15px rgba(var(--accent-color-rgb), 0.3);">
                                        <i class="bi bi-file-earmark-pdf-fill me-2"></i> Descargar Ticket
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Error Estado -->
                        <div class="alert text-center" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: #dc3545; border-radius: 15px; padding: 40px; backdrop-filter: blur(10px);">
                            <i class="bi bi-exclamation-triangle-fill fs-1 d-block mb-3"></i>
                            <h4 style="font-family: var(--heading-font); color: white;">¡Hubo un error al procesar tu recibo!</h4>
                            <p style="color: #adb5bd; margin-bottom: 20px;">No se encontró información para la orden proporcionada.</p>
                            <a href="catalogo.php" class="btn btn-outline-light px-4 py-2" style="border-radius: 50px;">Volver al Inicio</a>
                        </div>
                    <?php endif ?>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <?php

    include '../resources/templates/footer.html';
    include '../resources/templates/scripts.html';
    include '../resources/templates/fin.html';

} else {
    header("Location:login_error.php");
    exit();
}

