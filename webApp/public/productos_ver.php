<?php

session_start();
if (isset($_SESSION['usuario'])) {

    $PageTitle = "Ver productos";

    include '../resources/templates/head.html';
    include '../resources/templates/administrador_navegacion.html';

    ?>
    <main class="main">
        <section class="section dark-background" style="min-height: 80vh; padding-top: 100px; padding-bottom: 60px;">
            <div class="container-fluid px-4 px-md-5" data-aos="fade-up">

                <div class="section-title text-center mb-5">
                    <h2 style="color: #ededed; font-family: var(--heading-font); font-weight: 700; text-transform: uppercase;">Inventario de Productos</h2>
                    <p style="color: var(--accent-color);">Gestión General del Catálogo Activo e Inactivo</p>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        
                        <!-- Barra de Búsqueda Glassmorphism -->
                        <div class="row mb-4">
                            <div class="col-md-6 offset-md-3">
                                <div class="input-group" style="background: var(--surface-color); border-radius: 50px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                                    <span class="input-group-text border-0 text-light" style="background: transparent; padding-left: 20px;"><i class="bi bi-search"></i></span>
                                    <input class="form-control border-0 text-light shadow-none" style="background: transparent;" type="text" id="busqueda" onkeyup="funcionBuscar()" placeholder="Buscar producto por nombre..." title="Escribe un producto">
                                </div>
                            </div>
                        </div>

                        <!-- Card Contenedora de Tabla -->
                        <div class="card p-0" style="background: var(--surface-color); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); overflow: hidden;">
                            <div class="table-responsive p-3">
                                <table class="table text-light table-hover align-middle mb-0" id="tabla" style="--bs-table-bg: transparent; --bs-table-color: #ededed; --bs-table-hover-bg: rgba(255,255,255,0.02);">
                                    <thead>
                                        <tr style="border-bottom: 2px solid rgba(255,255,255,0.1);">
                                            <th class="border-0 text-center text-uppercase" style="color: #adb5bd; font-size: 12px; letter-spacing: 1px;">Imagen</th>
                                            <th class="border-0 text-center text-uppercase" style="color: #adb5bd; font-size: 12px; letter-spacing: 1px;">Nombre</th>
                                            <th class="border-0 text-center text-uppercase" style="color: #adb5bd; font-size: 12px; letter-spacing: 1px;">Categoría</th>
                                            <th class="border-0 text-center text-uppercase" style="color: #adb5bd; font-size: 12px; letter-spacing: 1px;">Descripción</th>
                                            <th class="border-0 text-center text-uppercase" style="color: #adb5bd; font-size: 12px; letter-spacing: 1px;">Stock</th>
                                            <th class="border-0 text-center text-uppercase" style="color: #adb5bd; font-size: 12px; letter-spacing: 1px;">Precio</th>
                                            <th class="border-0 text-center text-uppercase" style="color: #adb5bd; font-size: 12px; letter-spacing: 1px;">Estatus</th>
                                            <th class="border-0 text-center text-uppercase" style="color: #adb5bd; font-size: 12px; letter-spacing: 1px;">Creación</th>
                                            <th class="border-0 text-center text-uppercase" style="color: #adb5bd; font-size: 12px; letter-spacing: 1px;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    include '../resources/db/ProductoDB.php';
                                    $productos = ProductoDB::getProductos();
                                    foreach ($productos as $producto):?>
                                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                            <td class="text-center py-3">
                                                <?php if (!empty($producto['imagen']) && file_exists('../resources/uploads/' . $producto['imagen'])): ?>
                                                    <img src="<?= '../resources/uploads/' . $producto['imagen'] ?>" style="height: 50px; width: 50px; object-fit: contain; border-radius: 8px; background: rgba(255,255,255,0.02); padding: 5px;">
                                                <?php else: ?>
                                                    <div style="height: 50px; width: 50px; background: rgba(255,255,255,0.05); display:inline-flex; align-items:center; justify-content:center; border-radius: 8px;"><i class="bi bi-image" style="color: #666;"></i></div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center font-monospace fw-bold" style="color: #ededed; font-size: 14px;"><?= htmlspecialchars($producto['nombre']) ?></td>
                                            <td class="text-center">
                                                <?php
                                                $cat_nombre = htmlspecialchars($producto['categoria_nombre']);
                                                $is_beneficio = (stripos($producto['categoria_nombre'], 'Beneficio') !== false || stripos($producto['categoria_nombre'], 'Cupones') !== false);
                                                
                                                if ($is_beneficio) {
                                                    $badge_bg = "rgba(255, 193, 7, 0.15)";
                                                    $badge_border = "#ffc107";
                                                    $badge_color = "#ffc107";
                                                    $icon = "bi-ticket-perforated-fill";
                                                } else {
                                                    $badge_bg = "rgba(var(--accent-color-rgb), 0.15)";
                                                    $badge_border = "var(--accent-color)";
                                                    $badge_color = "var(--accent-color)";
                                                    $icon = "bi-card-heading";
                                                }
                                                ?>
                                                <span class="badge" style="background-color: <?= $badge_bg ?>; border: 1px solid <?= $badge_border ?>; color: <?= $badge_color ?>; border-radius: 50px; padding: 6px 12px; font-weight: 600; letter-spacing: 0.5px;">
                                                    <i class="bi <?= $icon ?> me-1"></i> <?= $cat_nombre ?>
                                                </span>
                                            </td>
                                            <td class="text-center" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #adb5bd; font-size: 13px;"><?= htmlspecialchars($producto['descripcion']) ?></td>
                                            <td class="text-center">
                                                <?php if ($producto['stock'] < 0): ?>
                                                    <span class="badge" style="background-color: var(--accent-color); font-size: 12px; border-radius: 50px;"><i class="bi bi-infinity"></i></span>
                                                <?php elseif ($producto['stock'] <= 5 && $producto['stock'] > 0): ?>
                                                    <span class="badge text-danger fw-bold" style="background: rgba(220,53,69,0.1); border: 1px solid #dc3545; border-radius: 50px;"><i class="bi bi-exclamation-circle me-1"></i><?= $producto['stock'] ?></span>
                                                <?php elseif ($producto['stock'] == 0): ?>
                                                    <span class="badge text-danger fw-bold" style="background: rgba(220,53,69,0.1); border: 1px solid #dc3545; border-radius: 50px;">Agotado</span>
                                                <?php else: ?>
                                                    <span class="text-light fw-bold" style="color: #ededed;"><?= $producto['stock'] ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center font-monospace" style="color: #28a745; font-weight: 600;">$<?= number_format($producto['precio'], 2) ?></td>
                                            <td class="text-center">
                                                <?php if ($producto['activo'] == 1): ?>
                                                    <i class="bi bi-circle-fill text-success" style="font-size: 10px;" title="Activo"></i>
                                                <?php else: ?>
                                                    <i class="bi bi-circle-fill text-secondary" style="font-size: 10px;" title="Inactivo"></i>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center font-monospace" style="font-size: 12px; color: #adb5bd;"><?= date('d/m/Y', strtotime($producto['fecha_creacion'])) ?></td>
                                            <td class="text-center">
                                                <form action="producto_modificar.php" method="POST" class="m-0">
                                                    <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                                                    <button class="btn btn-sm" type="submit" style="background: transparent; border: 1px solid var(--accent-color); color: var(--accent-color); border-radius: 50px; font-size: 12px; padding: 4px 12px; transition: 0.3s;" onmouseover="this.style.background='var(--accent-color)'; this.style.color='var(--background-color)';" onmouseout="this.style.background='transparent'; this.style.color='var(--accent-color)';"><i class="bi bi-pencil-square me-1"></i> Editar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </section>
    </main>

    <script>
        function funcionBuscar() {
            let textoBuscar, tabla, renglones, primerCelda, renglon, textoCelda;
            textoBuscar = document.getElementById("busqueda").value.toUpperCase();
            tabla = document.getElementById("tabla");
            renglones = tabla.getElementsByTagName("tr");
            // renglones[0] es el encabezado de thead, iteramos sobre el tbody
            let tbodyRows = tabla.querySelectorAll('tbody tr');
            for (let i = 0; i < tbodyRows.length; i++) {
                primerCelda = tbodyRows[i].getElementsByTagName("td")[1]; // Nombre = índice 1
                if (primerCelda) {
                    textoCelda = primerCelda.textContent || primerCelda.innerText;
                    if (textoCelda.toUpperCase().indexOf(textoBuscar) > -1) {
                        tbodyRows[i].style.display = "";
                    } else {
                        tbodyRows[i].style.display = "none";
                    }
                }
            }
        }
    </script>

    <?php
    include '../resources/templates/footer.html';
    include '../resources/templates/scripts.html';
    include '../resources/templates/fin.html';

} else {
    header("Location:login_error.php");
    exit();
}
