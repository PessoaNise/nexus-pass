<?php
session_start();
$PageTitle = "Catálogo - Nexus Pass";

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
    // Determinamos qué header incluir (con sesión o público)
    if (isset($_SESSION['usuario'])) {
        if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'admin') {
            include_once '../resources/templates/administrador_navegacion.html';
            $logeado = true;
        } else {
            include_once '../resources/templates/cliente_navegacion.html';
            $logeado = true;
        }
    } else {
        include_once '../resources/templates/header.html';
        $logeado = false;
    }

    include_once '../resources/db/ProductoDB.php';
    include_once '../resources/db/PedidoDB.php';
    include_once '../resources/db/CarroDB.php';

    $cart = new Cart();
    $in_cart_ids = [];
    if($cart->total_items() > 0) {
        foreach($cart->contents() as $item) {
            $in_cart_ids[] = $item['id'];
        }
    }

    $active_benefits_ids = [];
    if ($logeado && isset($_SESSION['id_usuario'])) {
        $ordenes_usuario = PedidoDB::getOrdenesDeClientePorIdCliente($_SESSION['id_usuario']);
        include_once '../resources/db/ItemPedidoDB.php';
        if (!empty($ordenes_usuario)) {
            foreach ($ordenes_usuario as $o) {
                $items = ItemPedidoDB::getDatosItemsOrdenPorIdOrden($o['id']);
                foreach ($items as $it) {
                    if ($it['precio'] == 0 && stripos(strtolower($it['nombre']), 'tarjeta') === false) {
                        $active_benefits_ids[] = $it['producto_id']; 
                    }
                }
            }
        }
    }

    // Atrapamos la categoría seleccionada (puede venir vacía == "Todas")
    $categoria_id = isset($_GET['categoria']) && is_numeric($_GET['categoria']) ? (int) $_GET['categoria'] : 0;

    // Atrapamos el parámetro de búsqueda textual
    $busqueda = isset($_GET['b']) ? trim($_GET['b']) : "";

    if ($categoria_id > 0) {
        $todos_productos = ProductoDB::getProductosPorCategoriaId($categoria_id);
    } else {
        $todos_productos = ProductoDB::getProductos();
    }

    // Filtramos y agrupamos
    $productos_filtrados = [];
    $productos_agrupados = [];

    foreach ($todos_productos as $p) {
        // Solo mostramos aquellos que estén ACTIVOS
        if ($p['activo'] == 1) {
            if ($busqueda !== "") {
                // Buscamos que el termo coincida en el nombre o descripción
                if (stripos($p['nombre'], $busqueda) !== false || stripos($p['descripcion'], $busqueda) !== false) {
                    $productos_filtrados[] = $p;
                    $productos_agrupados[$p['categoria_nombre']][] = $p;
                }
            } else {
                $productos_filtrados[] = $p;
                $productos_agrupados[$p['categoria_nombre']][] = $p;
            }
        }
    }

    // Si la categoría seleccionada no es 0, mostramos los productos normales sin agrupamiento estricto
// Si es 0 ("Todas"), mostramos el catálogo organizado por categorías.
    ?>
    <main class="main">
        <section class="section dark-background" style="min-height: 80vh; padding-top: 130px; padding-bottom: 60px;">
            <div class="container" data-aos="fade-up">

                <?php
                if ($logeado && isset($_SESSION['id_usuario'])) {
                    $membresia_id = PedidoDB::getMembresiaActiva($_SESSION['id_usuario']);
                    if ($membresia_id > 0) {
                        $membresia_nombre = $membresia_id == 1 ? "Bronce" : ($membresia_id == 2 ? "Plata" : "Oro");
                        $color_badge = $membresia_id == 1 ? "#cd7f32" : ($membresia_id == 2 ? "#c0c0c0" : "#ffd700");
                        echo '
                        <div class="alert mb-4 text-center" style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 15px;">
                            <span style="color: #ededed; font-size: 15px;">Estás navegando con los beneficios de tu membresía <strong style="color: '.$color_badge.';">'.$membresia_nombre.'</strong>. <a href="cliente_ver_pedidos.php" style="color: var(--accent-color); text-decoration: underline;">Ver mis beneficios</a></span>
                        </div>';
                    }
                }
                ?>

                <!-- Título de sección -->
                <div class="row mb-5 pb-2" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <div class="col-lg-8">
                        <h2
                            style="font-family: var(--heading-font); font-weight: 700; font-size: 34px; color: #ededed;">
                            Catálogo <span style="color: var(--accent-color);">NFC & Digital</span>
                        </h2>
                        <p style="color: #adb5bd; font-size: 16px;">
                            <?php if ($busqueda !== ""): ?>
                                Resultados para la búsqueda: "<strong><?= htmlspecialchars($busqueda) ?></strong>"
                            <?php else: ?>
                                Explora nuestras membresías y hardware inteligente
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end d-flex align-items-center justify-content-lg-end">
                        <span
                            style="color: #adb5bd; font-size: 14px; background: rgba(255,255,255,0.05); padding: 8px 15px; border-radius: 50px;">
                            Mostrando <?= count($productos_filtrados) ?> producto(s)
                        </span>
                    </div>
                </div>

                <?php if (count($productos_filtrados) > 0): ?>

                    <?php if ($categoria_id == 0): ?>
                        <!-- VISTA AGRUPADA (TODAS LAS CATEGORÍAS) -->
                        <?php foreach ($productos_agrupados as $nombre_categoria => $productos_cat): ?>
                            <?php
                            $cat_color = "var(--accent-color)";
                            $cat_icon = "bi-card-heading";
                            
                            $nombre_cat_upper = mb_strtoupper($nombre_categoria, 'UTF-8');
                            if (strpos($nombre_cat_upper, 'COMIDA') !== false) {
                                $cat_color = "#ff9800";
                                $cat_icon = "bi-shop";
                            } elseif (strpos($nombre_cat_upper, 'EDUCACIÓN') !== false || strpos($nombre_cat_upper, 'EDUCACION') !== false) {
                                $cat_color = "#4caf50";
                                $cat_icon = "bi-book";
                            } elseif (strpos($nombre_cat_upper, 'SERVICIOS') !== false) {
                                $cat_color = "#2196f3";
                                $cat_icon = "bi-briefcase";
                            } elseif (strpos($nombre_cat_upper, 'SALUD') !== false) {
                                $cat_color = "#e91e63";
                                $cat_icon = "bi-heart-pulse";
                            } elseif (strpos($nombre_cat_upper, 'DIVERSIÓN') !== false || strpos($nombre_cat_upper, 'DIVERSION') !== false) {
                                $cat_color = "#9c27b0";
                                $cat_icon = "bi-controller";
                            }
                            ?>

                            <div class="category-header mt-5 mb-4" data-aos="fade-up">
                                <h4
                                    style="color: <?= $cat_color ?>; font-family: var(--heading-font); border-left: 4px solid <?= $cat_color ?>; padding-left: 15px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                                    <i class="bi <?= $cat_icon ?>"></i> <?= htmlspecialchars($nombre_categoria) ?>
                                </h4>
                            </div>

                            <div class="row g-4 mb-5">
                                <?php foreach ($productos_cat as $producto): ?>
                                    <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch" data-aos="fade-up"
                                        data-aos-delay="100">
                                        <div class="catalog-card w-100">

                                            <div class="catalog-img-wrapper">
                                                <?php if ($producto['precio'] > 0): ?>
                                                    <?php if ($producto['categoria_id'] == 1): ?>
                                                        <div class="badge-stock"><i class="bi bi-calendar-check"></i> Vigencia: 30 Días</div>
                                                    <?php elseif ($producto['stock'] < 0): ?>
                                                        <div class="badge-stock"><i class="bi bi-infinity"></i> Ilimitado</div>
                                                    <?php elseif ($producto['stock'] <= 5 && $producto['stock'] > 0): ?>
                                                        <div class="badge-stock" style="color: #dc3545;"><i class="bi bi-exclamation-triangle-fill"></i> ¡Últimas <?= $producto['stock'] ?> pzas!</div>
                                                    <?php elseif ($producto['stock'] == 0): ?>
                                                        <div class="badge-stock" style="color: #dc3545; background-color: rgba(220,53,69,0.1); border-color: rgba(220,53,69,0.3);"><i class="bi bi-x-circle"></i> Agotado</div>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                                <img src="../resources/uploads/<?= htmlspecialchars($producto['imagen']) ?>"
                                                    alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                                    onerror="this.onerror=null; this.src='assets/img/nexus-logo.png'; this.style.filter='invert(50%)';">
                                            </div>

                                            <div class="card-body">
                                                <div class="catalog-category" style="color: <?= $cat_color ?>; border: 1px solid <?= $cat_color ?>; background: rgba(0,0,0,0.2); padding: 4px 10px; border-radius: 50px; display: inline-block; font-size: 12px; font-weight: 600; margin-bottom: 15px;">
                                                    <i class="bi <?= $cat_icon ?> me-1"></i> <?= htmlspecialchars($producto['categoria_nombre']) ?>
                                                </div>
                                                <h5 class="card-title"><?= htmlspecialchars($producto['nombre']) ?></h5>

                                                <p class="card-text">
                                                    <?= htmlspecialchars(strlen($producto['descripcion']) > 120 ? substr($producto['descripcion'], 0, 120) . '...' : $producto['descripcion']) ?>
                                                </p>

                                                <div class="catalog-footer">
                                                    <?php if ($producto['precio'] > 0): ?>
                                                        <div class="catalog-price">$<?= number_format($producto['precio'], 2) ?></div>
                                                    <?php else: ?>
                                                        <div class="catalog-price" style="font-size: 14px; color: var(--accent-color);"><i class="bi bi-star-fill"></i> Beneficio</div>
                                                    <?php endif; ?>

                                                    <?php if ($producto['stock'] == 0 && $producto['precio'] > 0): ?>
                                                        <button class="btn btn-secondary rounded-pill" disabled style="opacity: 0.5;">No disponible</button>
                                                    <?php else: ?>
                                                        <div class="d-flex gap-2">
                                                            <?php if ($producto['precio'] == 0): ?>
                                                                <?php if (in_array($producto['id'], $active_benefits_ids)): ?>
                                                                    <button class="btn btn-secondary rounded-pill p-2 px-3" style="font-size: 14px; opacity: 0.8;" disabled title="Ya tienes este beneficio">
                                                                        <i class="bi bi-check-circle"></i> Activo
                                                                    </button>
                                                                <?php elseif (in_array($producto['id'], $in_cart_ids)): ?>
                                                                    <a href="carro_ver.php" class="btn btn-secondary rounded-pill p-2 px-3" style="font-size: 14px; opacity: 0.8;" title="Ver en el carrito">
                                                                        <i class="bi bi-cart-check"></i> Añadido
                                                                    </a>
                                                                <?php else: ?>
                                                                    <a href="../resources/lib/cartAction.php?action=addToCart&id=<?= $producto['id'] ?>&return=catalogo" class="btn btn-primary rounded-pill p-2 px-3" style="background-color: var(--accent-color); border: none; font-size: 14px;" title="Añadir a la tarjeta">
                                                                        <i class="bi bi-plus-lg"></i> Añadir
                                                                    </a>
                                                                <?php endif; ?>
                                                                <a href="vista_previa.php?id=<?= $producto['id'] ?>" class="btn btn-outline-light rounded-pill p-2 px-3" style="font-size: 14px;" title="Ver Detalles">
                                                                    <i class="bi bi-info-circle"></i>
                                                                </a>
                                                            <?php else: ?>
                                                                <a href="vista_previa.php?id=<?= $producto['id'] ?>" class="btn btn-buy">
                                                                    <i class="bi bi-cart-plus"></i> Lo quiero
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <!-- VISTA SENCILLA (UNA SOLA CATEGORÍA SELECCIONADA EN EL BUSCADOR) -->
                        <div class="row g-4">
                            <?php foreach ($productos_filtrados as $producto): ?>
                                <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch" data-aos="fade-up"
                                    data-aos-delay="100">
                                    <div class="catalog-card w-100">
                                        <div class="catalog-img-wrapper">
                                            <?php if ($producto['precio'] > 0): ?>
                                                <?php if ($producto['categoria_id'] == 1): ?>
                                                    <div class="badge-stock"><i class="bi bi-calendar-check"></i> Vigencia: 30 Días</div>
                                                <?php elseif ($producto['stock'] < 0): ?>
                                                    <div class="badge-stock"><i class="bi bi-infinity"></i> Ilimitado</div>
                                                <?php elseif ($producto['stock'] <= 5 && $producto['stock'] > 0): ?>
                                                    <div class="badge-stock" style="color: #dc3545;"><i class="bi bi-exclamation-triangle-fill"></i> ¡Últimas <?= $producto['stock'] ?> pzas!</div>
                                                <?php elseif ($producto['stock'] == 0): ?>
                                                    <div class="badge-stock" style="color: #dc3545; background-color: rgba(220,53,69,0.1); border-color: rgba(220,53,69,0.3);"><i class="bi bi-x-circle"></i> Agotado</div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <img src="../resources/uploads/<?= htmlspecialchars($producto['imagen']) ?>"
                                                alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                                onerror="this.onerror=null; this.src='assets/img/nexus-logo.png'; this.style.filter='invert(50%)';">
                                        </div>
                                        <div class="card-body">
                                            <?php
                                            $cat_color_s = "var(--accent-color)";
                                            $cat_icon_s = "bi-card-heading";
                                            $nombre_cat_upper_s = mb_strtoupper($producto['categoria_nombre'], 'UTF-8');
                                            if (strpos($nombre_cat_upper_s, 'COMIDA') !== false) {
                                                $cat_color_s = "#ff9800"; $cat_icon_s = "bi-shop";
                                            } elseif (strpos($nombre_cat_upper_s, 'EDUCACIÓN') !== false || strpos($nombre_cat_upper_s, 'EDUCACION') !== false) {
                                                $cat_color_s = "#4caf50"; $cat_icon_s = "bi-book";
                                            } elseif (strpos($nombre_cat_upper_s, 'SERVICIOS') !== false) {
                                                $cat_color_s = "#2196f3"; $cat_icon_s = "bi-briefcase";
                                            } elseif (strpos($nombre_cat_upper_s, 'SALUD') !== false) {
                                                $cat_color_s = "#e91e63"; $cat_icon_s = "bi-heart-pulse";
                                            } elseif (strpos($nombre_cat_upper_s, 'DIVERSIÓN') !== false || strpos($nombre_cat_upper_s, 'DIVERSION') !== false) {
                                                $cat_color_s = "#9c27b0"; $cat_icon_s = "bi-controller";
                                            }
                                            ?>
                                            <div class="catalog-category" style="color: <?= $cat_color_s ?>; border: 1px solid <?= $cat_color_s ?>; background: rgba(0,0,0,0.2); padding: 4px 10px; border-radius: 50px; display: inline-block; font-size: 12px; font-weight: 600; margin-bottom: 15px;">
                                                <i class="bi <?= $cat_icon_s ?> me-1"></i> <?= htmlspecialchars($producto['categoria_nombre']) ?>
                                            </div>
                                            <h5 class="card-title"><?= htmlspecialchars($producto['nombre']) ?></h5>
                                            <p class="card-text">
                                                <?= htmlspecialchars(strlen($producto['descripcion']) > 120 ? substr($producto['descripcion'], 0, 120) . '...' : $producto['descripcion']) ?>
                                            </p>
                                            <div class="catalog-footer">
                                                <?php if ($producto['precio'] > 0): ?>
                                                    <div class="catalog-price">$<?= number_format($producto['precio'], 2) ?></div>
                                                <?php else: ?>
                                                    <div class="catalog-price" style="font-size: 14px; color: var(--accent-color);"><i class="bi bi-star-fill"></i> Beneficio</div>
                                                <?php endif; ?>

                                                <?php if ($producto['stock'] == 0 && $producto['precio'] > 0): ?>
                                                    <button class="btn btn-secondary rounded-pill" disabled style="opacity: 0.5;">No disponible</button>
                                                <?php else: ?>
                                                    <div class="d-flex gap-2">
                                                        <?php if ($producto['precio'] == 0): ?>
                                                            <?php if (in_array($producto['id'], $active_benefits_ids)): ?>
                                                                <button class="btn btn-secondary rounded-pill p-2 px-3" style="font-size: 14px; opacity: 0.8;" disabled title="Ya tienes este beneficio">
                                                                    <i class="bi bi-check-circle"></i> Activo
                                                                </button>
                                                            <?php elseif (in_array($producto['id'], $in_cart_ids)): ?>
                                                                <a href="carro_ver.php" class="btn btn-secondary rounded-pill p-2 px-3" style="font-size: 14px; opacity: 0.8;" title="Ver en el carrito">
                                                                    <i class="bi bi-cart-check"></i> Añadido
                                                                </a>
                                                            <?php else: ?>
                                                                <a href="../resources/lib/cartAction.php?action=addToCart&id=<?= $producto['id'] ?>&return=catalogo" class="btn btn-primary rounded-pill p-2 px-3" style="background-color: var(--accent-color); border: none; font-size: 14px;" title="Añadir a la tarjeta">
                                                                    <i class="bi bi-plus-lg"></i> Añadir
                                                                </a>
                                                            <?php endif; ?>
                                                            <a href="vista_previa.php?id=<?= $producto['id'] ?>" class="btn btn-outline-light rounded-pill p-2 px-3" style="font-size: 14px;" title="Ver Detalles">
                                                                <i class="bi bi-info-circle"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="vista_previa.php?id=<?= $producto['id'] ?>" class="btn btn-buy">
                                                                <i class="bi bi-cart-plus"></i> Lo quiero
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- Empty State -->
                    <div class="row g-4">
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-search" style="font-size: 3rem; color: rgba(255,255,255,0.2);"></i>
                            <h4 class="mt-4" style="color: #ededed; font-family: var(--heading-font);">No se encontraron
                                resultados</h4>
                            <p style="color: #adb5bd;">Lo sentimos, no hay productos activos que coincidan con tu criterio.
                            </p>
                            <a href="productos_visitante_muestra.php"
                                class="btn btn-outline-light rounded-pill mt-3 px-4">Ver Catálogo Completo</a>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </section>
    </main>

    <!-- Floating Cart Button specifically for Catalog -->
    <a href="carro_ver.php" class="floating-cart-btn d-flex align-items-center justify-content-center shadow-lg" title="Ver Carrito">
        <i class="bi bi-cart-fill me-2"></i> Continuar con la compra
        <?php if($cart->total_items() > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 12px; margin-top: 5px; margin-left: -10px;">
                <?= $cart->total_items() ?>
            </span>
        <?php endif; ?>
    </a>

    <style>
        .floating-cart-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: var(--accent-color);
            color: #000;
            padding: 15px 25px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 16px;
            text-decoration: none;
            z-index: 999;
            transition: all 0.3s;
        }
        .floating-cart-btn:hover {
            background-color: #e0b020;
            color: #000;
            transform: scale(1.05);
        }
        /* Ocultar el scroll top default si existe en esta página */
        #scroll-top {
            display: none !important;
        }
    </style>

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
    <?php
    include_once '../resources/templates/fin.html';
    ?>