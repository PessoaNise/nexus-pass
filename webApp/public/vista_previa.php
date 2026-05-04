<?php
session_start();
$PageTitle = "Vista previa";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include '../resources/templates/head.html'; ?>
</head>
<body class="index-page">
<?php
// Determinamos qué header incluir (con sesión o público)
if (isset($_SESSION['usuario'])) {
    if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'admin') {
        include '../resources/templates/administrador_navegacion.html';
        $logeado = true;
    } else {
        include '../resources/templates/cliente_navegacion.html';
        $logeado = true;
    }
} else {
    include '../resources/templates/header.html';
    $logeado = false;
}

include_once '../resources/db/ProductoDB.php';
$producto = ProductoDB::getProductoPorId($_GET['id']);
?>
<main class="main">
    <section class="section dark-background" style="min-height: 80vh; padding-top: 130px; padding-bottom: 60px;">
        <div class="container" data-aos="fade-up">

            <div class="row align-items-center justify-content-center">
                <div class="col-lg-10">
                    <div class="card bg-transparent border-0" style="background: var(--surface-color) !important; border: 1px solid rgba(255,255,255,0.05) !important; border-radius: 20px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.5);">
                        <div class="row g-0">
                            <!-- Columna Foto -->
                            <div class="col-md-5 d-flex align-items-center justify-content-center p-5" style="background: rgba(255,255,255,0.02)">
                                <img src="../resources/uploads/<?= htmlspecialchars($producto['imagen']) ?>" class="img-fluid" alt="<?= htmlspecialchars($producto['nombre']) ?>" onerror="this.onerror=null; this.src='assets/img/nexus-logo.png'; this.style.filter='invert(50%)';" style="max-height: 400px; object-fit: contain; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.4));">
                            </div>

                            <!-- Columna Info -->
                            <div class="col-md-7">
                                <div class="card-body p-5 d-flex flex-column h-100 justify-content-center">
                                    <div class="mb-2">
                                        <span class="badge" style="background-color: var(--accent-color); font-size: 13px; font-weight: 600; letter-spacing: 1px;"><?= htmlspecialchars($producto['categoria_nombre']) ?></span>
                                    </div>
                                    <h2 class="card-title mt-2 mb-4" style="color: #ededed; font-family: var(--heading-font); font-weight: 700; font-size: 2.5rem;"><?= htmlspecialchars($producto['nombre']) ?></h2>
                                    
                                    <?php if ($producto['precio'] > 0): ?>
                                        <h4 class="mb-4" style="color: #75b798; font-weight: 800; font-size: 2rem;">$<?= number_format($producto['precio'], 2) ?></h4>
                                    <?php else: ?>
                                        <h4 class="mb-4" style="color: var(--accent-color); font-weight: 800; font-size: 2rem;"><i class="bi bi-star-fill"></i> Beneficio Exclusivo</h4>
                                    <?php endif; ?>
                                    <h6 style="color: #ededed; font-weight: 600;">Acerca del producto</h6>
                                    <p class="card-text mb-4" style="color: #adb5bd; line-height: 1.8;">
                                        <?= nl2br(htmlspecialchars($producto['descripcion'])) ?>
                                    </p>
                                    
                                    <div class="mb-5 d-flex align-items-center">
                                        <?php if ($producto['precio'] > 0): ?>
                                            <?php if ($producto['categoria_id'] == 1): ?>
                                                <span style="color: #adb5bd; font-size: 15px; background: rgba(255,255,255,0.05); padding: 5px 15px; border-radius: 5px;"><i class="bi bi-calendar-check" style="color: var(--accent-color);"></i> Vigencia: 30 Días</span>
                                            <?php elseif ($producto['stock'] < 0): ?>
                                                <span style="color: #adb5bd; font-size: 15px; background: rgba(255,255,255,0.05); padding: 5px 15px; border-radius: 5px;"><i class="bi bi-infinity" style="color: var(--accent-color);"></i> Stock Ilimitado</span>
                                            <?php elseif ($producto['stock'] == 0): ?>
                                                <span class="text-danger" style="font-size: 15px; background: rgba(220,53,69,0.1); padding: 5px 15px; border-radius: 5px;"><i class="bi bi-x-circle"></i> Agotado</span>
                                            <?php else: ?>
                                                <span style="color: #adb5bd; font-size: 15px;"><i class="bi bi-box-seam me-2"></i> <?= $producto['stock'] ?> unidades disponibles</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <span class="ms-4 text-muted" style="font-size: 13px;"><i class="bi bi-calendar3 me-1"></i> Añadido: <?= date('M Y', strtotime($producto['fecha_creacion'])) ?></span>
                                    </div>

                                    <div class="mt-auto d-flex gap-3">
                                        <?php if ($producto['stock'] == 0 && $producto['precio'] > 0): ?>
                                            <button class="btn btn-secondary rounded-pill py-3 px-5 fw-bold w-100" disabled style="opacity: 0.5; font-size: 1.1rem;">No Disponible</button>
                                        <?php else: ?>
                                            <a href="../resources/lib/cartAction.php?action=addToCart&id=<?= $producto["id"]; ?>" class="btn btn-primary rounded-pill py-3 px-4 fw-bold flex-grow-1 text-center" style="background-color: var(--accent-color); border: none; font-size: 1.1rem; box-shadow: 0 5px 15px rgba(255, 77, 79, 0.3);">
                                                <i class="bi bi-plus-circle me-2"></i> <?= $producto['precio'] == 0 ? 'Añadir beneficio' : 'Agregar al carrito' ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="catalogo.php" class="btn btn-outline-light rounded-pill"><i class="bi bi-arrow-left"></i> Volver al Catálogo</a>
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
?>
