<?php

session_start();
if (isset($_SESSION['usuario'])) {

    $PageTitle = "Cliente";

    include_once '../resources/db/PedidoDB.php';
    include_once '../resources/db/ItemPedidoDB.php';

    include_once '../resources/templates/head.html';
    include_once '../resources/templates/cliente_navegacion.html';

    ?>

    <main class="main">
        <section class="section dark-background" style="min-height: 80vh; padding-top: 100px; padding-bottom: 60px;">
            <div class="container" data-aos="fade-up">

                <div class="section-title text-center mb-5">
                    <h2
                        style="color: #ededed; font-family: var(--heading-font); font-weight: 700; text-transform: uppercase;">
                        Mis Beneficios y Suscripciones</h2>
                    <p style="color: var(--accent-color);">Administra tus beneficios mensuales y el historial de tus
                        adquisiciones.</p>
                </div>

                <?php
                $membresia_id = PedidoDB::getMembresiaActiva($_SESSION['id_usuario']);
                $beneficios_usados = PedidoDB::getCantidadBeneficiosUsados($_SESSION['id_usuario']);
                $limit = 0;
                $membresia_nombre = "Ninguna";
                $color_badge = "var(--surface-color)";
                if ($membresia_id == 1) {
                    $limit = 5;
                    $membresia_nombre = "Bronce";
                    $color_badge = "#cd7f32";
                } elseif ($membresia_id == 2) {
                    $limit = 10;
                    $membresia_nombre = "Plata";
                    $color_badge = "#c0c0c0";
                } elseif ($membresia_id == 3) {
                    $limit = 15;
                    $membresia_nombre = "Oro";
                    $color_badge = "#ffd700";
                }
                ?>

                <div class="row justify-content-center mb-5">
                    <div class="col-lg-10">
                        <div class="card p-4"
                            style="background: var(--surface-color); border: 1px solid rgba(255,255,255,0.05); border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                            <div class="row align-items-center">
                                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                                    <h4 style="color: #ededed; font-family: var(--heading-font); margin-bottom: 5px;">
                                        Membresía Activa</h4>
                                    <?php if ($limit > 0): ?>
                                        <span class="badge"
                                            style="background-color: <?= $color_badge ?>; color: #000; font-size: 1rem; padding: 8px 15px; border-radius: 50px;"><i
                                                class="bi bi-star-fill me-2"></i><?= $membresia_nombre ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"
                                            style="font-size: 1rem; padding: 8px 15px; border-radius: 50px;">Sin
                                            membresía</span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 text-center text-md-end">
                                    <h4 style="color: #ededed; font-family: var(--heading-font); margin-bottom: 5px;">
                                        Beneficios Activos</h4>
                                    <h3 style="color: var(--accent-color); font-weight: 800; margin-bottom: 0;">
                                        <?= $beneficios_usados ?> / <?= $limit > 0 ? $limit : '-' ?>
                                    </h3>
                                    <?php if ($limit > 0 && $beneficios_usados >= $limit): ?>
                                        <small class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>Límite
                                            alcanzado</small>
                                    <?php elseif ($limit > 0): ?>
                                        <small style="color: #adb5bd;">Aún puedes elegir <?= $limit - $beneficios_usados ?>
                                            beneficios más.</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                // Obtener todos los beneficios activos del usuario
                $beneficios_activos_lista = [];
                $ordenes_usuario = PedidoDB::getOrdenesDeClientePorIdCliente($_SESSION['id_usuario']);
                if (!empty($ordenes_usuario)) {
                    foreach ($ordenes_usuario as $o) {
                        $items = ItemPedidoDB::getDatosItemsOrdenPorIdOrden($o['id']);
                        foreach ($items as $it) {
                            if ($it['precio'] == 0 && stripos(strtolower($it['nombre']), 'tarjeta') === false) {
                                $beneficios_activos_lista[] = $it;
                            }
                        }
                    }
                }
                ?>

                <?php if (count($beneficios_activos_lista) > 0): ?>
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-10">
                        <h4 style="color: #ededed; font-family: var(--heading-font); margin-bottom: 20px;"><i class="bi bi-grid me-2 text-warning"></i> Galería de Beneficios</h4>
                        <div class="row g-3">
                            <?php foreach ($beneficios_activos_lista as $ben): ?>
                            <div class="col-6 col-md-4 col-lg-3" data-aos="zoom-in">
                                <div class="card bg-transparent border-0 text-center p-3" style="background: var(--surface-color) !important; border-radius: 15px; border: 1px solid rgba(255,255,255,0.05) !important;">
                                    <img src="../resources/uploads/<?= htmlspecialchars($ben['nombre_archivo']) ?>" alt="<?= htmlspecialchars($ben['nombre']) ?>" style="width: 100%; height: 80px; object-fit: contain; border-radius: 10px; background: rgba(255,255,255,0.05); padding: 10px; margin-bottom: 10px;" onerror="this.onerror=null; this.src='assets/img/nexus-logo.png'; this.style.filter='invert(50%)';">
                                    <h6 style="color: #ededed; font-size: 14px; margin-bottom: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($ben['nombre']) ?></h6>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="accordion custom-accordion" id="accordionPedidos">
                            <?php
                            $i = 1;
                            $ordenes = PedidoDB::getOrdenesDeClientePorIdCliente($_SESSION['id_usuario']);

                            if (empty($ordenes)) {
                                echo '<div class="alert text-center" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #adb5bd; border-radius: 15px; padding: 40px; backdrop-filter: blur(10px);"><i class="bi bi-bag-x fs-1 d-block mb-3"></i>No tienes pedidos registrados aún.</div>';
                            }

                            foreach ($ordenes as $orden): ?>

                                <div class="accordion-item mb-3"
                                    style="background: var(--surface-color); border: 1px solid rgba(255,255,255,0.05); border-radius: 15px !important; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button <?php if ($i != 1)
                                            print ('collapsed') ?>" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse-<?= $i ?>"
                                            aria-expanded="<?php ($i == 1) ? print ('true') : print ('false') ?>"
                                            aria-controls="collapse-<?= $i ?>"
                                            style="background: transparent; box-shadow: none; padding: 20px 25px;">
                                            <div class="w-100 d-flex flex-wrap justify-content-between align-items-center me-3">
                                                <div>
                                                    <span
                                                        style="color: #adb5bd; font-size: 13px; display: block; margin-bottom: 5px;">Pedido
                                                        #<?= $orden['id'] ?></span>
                                                    <h6 style="color: #ededed; font-weight: 600; margin-bottom: 0;"><i
                                                            class="bi bi-calendar3 me-2"
                                                            style="color: var(--accent-color);"></i> <?= $orden['fecha'] ?></h6>
                                                </div>
                                                <div class="text-end mt-2 mt-sm-0">
                                                    <?php if (strtolower($orden['estado']) !== 'pendiente'): ?>
                                                        <span class="badge"
                                                            style="background: rgba(40, 167, 69, 0.2); color: #28a745; border: 1px solid rgba(40,167,69,0.3); border-radius: 50px; padding: 6px 12px; margin-bottom: 5px;"><?= $orden['estado'] ?></span>
                                                    <?php endif; ?>
                                                    <h5 style="color: var(--accent-color); font-weight: 700; margin-bottom: 0;">
                                                        $<?= number_format($orden['total'], 2) ?></h5>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse-<?= $i ?>"
                                        class="accordion-collapse collapse <?php ($i == 1) ? print ('show') : print ('') ?>"
                                        data-bs-parent="#accordionPedidos">
                                        <div class="accordion-body"
                                            style="background: rgba(0,0,0,0.2); border-top: 1px solid rgba(255,255,255,0.05); padding: 0;">
                                            <?php
                                            $items = ItemPedidoDB::getDatosItemsOrdenPorIdOrden($orden['id']);
                                            foreach ($items as $item): ?>
                                                <div class="row align-items-center p-4 border-bottom m-0"
                                                    style="border-color: rgba(255,255,255,0.05) !important;">
                                                    <div class="col-md-2 text-center mb-3 mb-md-0">
                                                        <img src="../resources/uploads/<?= $item['nombre_archivo'] ?>"
                                                            alt="<?= $item['nombre'] ?>" class="img-fluid"
                                                            style="border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); max-height: 80px; object-fit: cover;">
                                                    </div>
                                                    <div class="col-md-5 mb-3 mb-md-0">
                                                        <h6
                                                            style="color: #ededed; font-weight: 600; font-size: 15px; margin-bottom: 5px;">
                                                            <?= $item['nombre'] ?>
                                                        </h6>
                                                        <span style="color: #adb5bd; font-size: 13px;">Cantidad: <strong
                                                                style="color: white;"><?= $item['cantidad'] ?></strong></span>
                                                    </div>
                                                    <div class="col-md-5 text-md-end">
                                                        <p style="color: #adb5bd; font-size: 13px; margin-bottom: 2px;">Precio
                                                            Unitario: $<?= number_format($item['precio'], 2) ?></p>
                                                        <p style="color: #ededed; font-weight: 700; margin-bottom: 0;">Subtotal:
                                                            <span
                                                                style="color: var(--accent-color);">$<?= number_format($item['cantidad'] * $item['precio'], 2) ?></span>
                                                        </p>
                                                    </div>
                                                </div>
                                            <?php endforeach ?>
                                            <div class="p-3 text-end" style="background: rgba(255,255,255,0.02);">
                                                <form action="ticket_generar.php" method="get" target="_blank" class="m-0">
                                                    <input type="hidden" name="idOrden" value="<?= $orden['id'] ?>">
                                                    <button type="submit" class="btn btn-sm"
                                                        style="background: transparent; border: 1px solid var(--accent-color); color: var(--accent-color); border-radius: 50px; font-size: 12px;">Descargar
                                                        Recibo</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $i++;
                            endforeach ?>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <?php
    include_once '../resources/templates/footer.html';
    include_once '../resources/templates/scripts.html';
    include_once '../resources/templates/fin.html';

} else {
    header("Location:login_error.php");
    exit();
}

