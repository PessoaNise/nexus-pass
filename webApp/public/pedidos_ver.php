<?php

session_start();
if (isset($_SESSION['usuario'])) {

    $PageTitle = "Pedidos";

    include '../resources/db/PedidoDB.php';
    include '../resources/db/ItemPedidoDB.php';

    include '../resources/templates/head.html';
include '../resources/templates/administrador_navegacion.html';

    ?>

    <main class="main">
        <section class="section dark-background" style="min-height: 80vh; padding-top: 100px; padding-bottom: 60px;">
            <div class="container" data-aos="fade-up">

                <div class="section-title text-center mb-5">
                    <h2 style="color: #ededed; font-family: var(--heading-font); font-weight: 700; text-transform: uppercase;">Gestión General de Pedidos</h2>
                    <p style="color: var(--accent-color);">Supervisión de Todas las Órdenes Registradas</p>
                </div>

                <div class="row justify-content-center mb-5">
                    <div class="col-lg-10">
                        <div class="section-title text-center mb-4">
                            <h3 style="color: #ededed; font-family: var(--heading-font); font-weight: 600;">Estado de Membresías por Cliente</h3>
                        </div>
                        <div class="accordion custom-accordion" id="accordionMembresias">
                            <?php
                            require_once '../resources/db/UsuarioDB.php';
                            $clientes = UsuarioDB::getTodosClientes();
                            
                            $j = 1;
                            foreach ($clientes as $cli):
                                $membresia_id = PedidoDB::getMembresiaActiva($cli['id']);
                                $beneficios_usados = PedidoDB::getCantidadBeneficiosUsados($cli['id']);
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

                                // Obtener beneficios activos del usuario
                                $beneficios_activos_lista = [];
                                $ordenes_usuario = PedidoDB::getOrdenesDeClientePorIdCliente($cli['id']);
                                if (!empty($ordenes_usuario)) {
                                    foreach ($ordenes_usuario as $o) {
                                        $items_c = ItemPedidoDB::getDatosItemsOrdenPorIdOrden($o['id']);
                                        foreach ($items_c as $it) {
                                            if ($it['precio'] == 0 && stripos(strtolower($it['nombre']), 'tarjeta') === false) {
                                                $beneficios_activos_lista[] = $it;
                                            }
                                        }
                                    }
                                }
                            ?>
                                <div class="accordion-item mb-3" style="background: var(--surface-color); border: 1px solid rgba(255,255,255,0.05); border-radius: 15px !important; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMem-<?= $j ?>" aria-expanded="false" aria-controls="collapseMem-<?= $j ?>" style="background: transparent; box-shadow: none; padding: 20px 25px;">
                                            <div class="w-100 d-flex flex-wrap justify-content-between align-items-center me-3">
                                                <div>
                                                    <span style="color: #adb5bd; font-size: 13px; display: block; margin-bottom: 5px;">Usuario: <strong style="color:#ededed;"><?= htmlspecialchars($cli['usuario']) ?></strong></span>
                                                    <h6 style="color: #ededed; font-weight: 600; margin-bottom: 0;">
                                                        <?php if ($limit > 0): ?>
                                                            <span class="badge" style="background-color: <?= $color_badge ?>; color: #000;"><i class="bi bi-star-fill me-1"></i> <?= $membresia_nombre ?></span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Sin membresía</span>
                                                        <?php endif; ?>
                                                    </h6>
                                                </div>
                                                <div class="text-end mt-2 mt-sm-0">
                                                    <p style="color: #adb5bd; font-size: 13px; margin-bottom: 2px;">Beneficios Usados</p>
                                                    <h5 style="color: var(--accent-color); font-weight: 700; margin-bottom: 0;">
                                                        <?= $beneficios_usados ?> / <?= $limit > 0 ? $limit : '-' ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapseMem-<?= $j ?>" class="accordion-collapse collapse" data-bs-parent="#accordionMembresias">
                                        <div class="accordion-body" style="background: rgba(0,0,0,0.2); border-top: 1px solid rgba(255,255,255,0.05); padding: 20px;">
                                            <?php if (count($beneficios_activos_lista) > 0): ?>
                                                <div class="row g-3">
                                                    <?php foreach ($beneficios_activos_lista as $ben): ?>
                                                    <div class="col-6 col-md-4 col-lg-3">
                                                        <div class="card bg-transparent border-0 text-center p-2" style="background: rgba(255,255,255,0.02) !important; border-radius: 10px; border: 1px solid rgba(255,255,255,0.05) !important;">
                                                            <img src="../resources/uploads/<?= htmlspecialchars($ben['nombre_archivo']) ?>" alt="<?= htmlspecialchars($ben['nombre']) ?>" style="width: 100%; height: 60px; object-fit: contain; border-radius: 8px; background: rgba(255,255,255,0.05); padding: 5px; margin-bottom: 10px;" onerror="this.onerror=null; this.src='assets/img/nexus-logo.png'; this.style.filter='invert(50%)';">
                                                            <h6 style="color: #ededed; font-size: 13px; margin-bottom: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($ben['nombre']) ?></h6>
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-center text-muted mb-0">Este usuario no tiene beneficios activos.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                $j++;
                            endforeach; 
                            ?>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="accordion custom-accordion" id="accordionAdminPedidos">
                            <?php
                            $i = 1;
                            $ordenes = PedidoDB::getOrdenes();
                            
                            if (empty($ordenes)) {
                                echo '<div class="alert text-center" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #adb5bd; border-radius: 15px; padding: 40px; backdrop-filter: blur(10px);"><i class="bi bi-inbox fs-1 d-block mb-3"></i>No existen pedidos en todo el sistema.</div>';
                            }
                            
                            foreach ($ordenes as $orden): ?>

                                <div class="accordion-item mb-3" style="background: var(--surface-color); border: 1px solid rgba(255,255,255,0.05); border-radius: 15px !important; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button <?php if ($i != 1) print('collapsed') ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= $i ?>"
                                                aria-expanded="<?php ($i == 1) ? print('true') : print('false') ?>"
                                                aria-controls="collapse-<?= $i ?>" style="background: transparent; box-shadow: none; padding: 20px 25px;">
                                            <div class="w-100 d-flex flex-wrap justify-content-between align-items-center me-3">
                                                <div>
                                                    <span style="color: #adb5bd; font-size: 13px; display: block; margin-bottom: 5px;">Pedido global #<?= $orden['id'] ?> - Cliente: <strong style="color:#ededed;"><?= htmlspecialchars($orden['nombre_usuario']) ?></strong></span>
                                                    <h6 style="color: #ededed; font-weight: 600; margin-bottom: 0;"><i class="bi bi-calendar-check me-2" style="color: var(--accent-color);"></i> <?= $orden['fecha'] ?></h6>
                                                </div>
                                                <div class="text-end mt-2 mt-sm-0">
                                                    <span class="badge" style="background: rgba(23, 162, 184, 0.2); color: #17a2b8; border: 1px solid rgba(23,162,184,0.3); border-radius: 50px; padding: 6px 12px; margin-bottom: 5px;"><?= $orden['estado'] ?></span>
                                                    <h5 style="color: var(--accent-color); font-weight: 700; margin-bottom: 0;">$<?= number_format($orden['total'], 2) ?></h5>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse-<?= $i ?>" class="accordion-collapse collapse <?php ($i == 1) ? print('show') : print('') ?>" data-bs-parent="#accordionAdminPedidos">
                                        <div class="accordion-body" style="background: rgba(0,0,0,0.2); border-top: 1px solid rgba(255,255,255,0.05); padding: 0;">
                                            <?php
                                            $items = ItemPedidoDB::getDatosItemsOrdenPorIdOrden($orden['id']);
                                            foreach ($items as $item): ?>
                                                <div class="row align-items-center p-4 border-bottom m-0" style="border-color: rgba(255,255,255,0.05) !important;">
                                                    <div class="col-md-2 text-center mb-3 mb-md-0">
                                                        <img src="../resources/uploads/<?= $item['nombre_archivo'] ?>" alt="<?= $item['nombre'] ?>" class="img-fluid" style="border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); max-height: 80px; object-fit: cover;">
                                                    </div>
                                                    <div class="col-md-5 mb-3 mb-md-0">
                                                        <h6 style="color: #ededed; font-weight: 600; font-size: 15px; margin-bottom: 5px;"><?= $item['nombre'] ?></h6>
                                                        <span style="color: #adb5bd; font-size: 13px;">Cantidad del Cliente: <strong style="color: white;"><?= $item['cantidad'] ?></strong></span>
                                                    </div>
                                                    <div class="col-md-5 text-md-end">
                                                        <p style="color: #adb5bd; font-size: 13px; margin-bottom: 2px;">Precio BD: $<?= number_format($item['precio'], 2) ?></p>
                                                        <p style="color: #ededed; font-weight: 700; margin-bottom: 0;">Facturado: <span style="color: var(--accent-color);">$<?= number_format($item['cantidad'] * $item['precio'], 2) ?></span></p>
                                                    </div>
                                                </div>
                                            <?php endforeach ?>
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
    include '../resources/templates/footer.html';
    include '../resources/templates/scripts.html';
    include '../resources/templates/fin.html';

} else {
    header("Location:login_error.php");
    exit();
}

