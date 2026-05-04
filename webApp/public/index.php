<?php 
session_start(); 

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

    <?php include_once '../resources/templates/cliente_navegacion.html'; ?>

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section dark-background">

            <img src="assets/img/hero-bg.jpeg" alt="Nexus Hero Background" data-aos="fade-in" style="opacity: 0.5;">

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h2>Nexus Pass</h2>
                        <p><span class="typed"
                                data-typed-items="Un solo movimiento., Infinitas posibilidades., Tu identidad digital unificada., Movilidad sin fricciones."></span><span
                                class="typed-cursor" aria-hidden="true"></span></p>
                        <p class="mt-3 fs-5">El estándar global de identificación y pago, integrando tu vida diaria en
                            una sola credencial inteligente.</p>
                        <div class="social-links mt-4">
                            <a href="#about" class="btn btn-outline-light me-3 rounded-pill px-4 py-2"
                                style="width: auto; height: auto; border-radius: 50px !important;">Conocer Más</a>
                            <a href="#plans" class="btn btn-primary mt-auto rounded-pill py-2"
                                style="width: auto; height: auto; background-color: var(--accent-color); color: var(--contrast-color);border: none;">Planes
                                y Suscripción</a>
                        </div>
                    </div>
                </div>
            </div>

        </section><!-- /Hero Section -->

        <!-- About Section (Sobre Nexus) -->
        <section id="about" class="about section">

            <div class="container section-title" data-aos="fade-up">
                <span class="subtitle">Descubre el Sistema</span>
                <h2>¿Qué es Nexus?</h2>
                <p>Nexus es una empresa de innovación tecnológica dedicada a revolucionar la movilidad
                    urbana y la gestión de la identidad digital.</p>
            </div>

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-5">
                    <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="150">
                        <div class="profile-card p-4 text-center">
                            <div class="profile-avatar mb-4">
                                <img src="assets/img/nexus-logo.png" class="img-fluid" style="filter: invert(100%);"
                                    alt="Nexus Logo">
                            </div>
                            <h3>Tarjeta Inteligente</h3>
                            <span class="role" style="color: #adb5bd !important;">Ecosistema NFC/RFID</span>
                            <hr>
                            <p class="text-start mt-3">Diseñamos y desarrollamos ecosistemas centralizados con un único
                                objetivo: <strong>devolverle el tiempo, la seguridad y la comodidad a las
                                    personas.</strong></p>
                        </div>
                    </div>

                    <div class="col-lg-8" data-aos="fade-left" data-aos-delay="200">
                        <div class="content-wrapper">
                            <div class="bio-section">
                                <div class="section-tag">El Problema y la Solución</div>
                                <h2>Evitando la Fragmentación Diaria</h2>
                                <p>Nacemos frente a la creciente fragmentación de los servicios diarios, donde los
                                    ciudadanos deben lidiar con múltiples credenciales, tarjetas, métodos de pago y
                                    aplicaciones para navegar su entorno.</p>
                                <p>Actualmente, los usuarios deben portar tarjetas y aplicaciones para transporte,
                                    acceso a oficinas, pagos y emergencias, lo que genera fricción, pérdida de tiempo y
                                    riesgos de seguridad. Nexus centraliza todo esto con tecnología vanguardista.</p>
                            </div>

                            <div class="details-grid mt-4">
                                <div class="detail-item" data-aos="fade-up" data-aos-delay="250">
                                    <i class="bi bi-bullseye"></i>
                                    <div class="detail-content">
                                        <span>Misión</span>
                                        <strong>Facilitar la movilidad y el consumo urbano</strong>
                                    </div>
                                </div>

                                <div class="detail-item" data-aos="fade-up" data-aos-delay="300">
                                    <i class="bi bi-eye"></i>
                                    <div class="detail-content">
                                        <span>Visión</span>
                                        <strong>Estándar global unificado para 2030</strong>
                                    </div>
                                </div>

                                <div class="detail-item" data-aos="fade-up" data-aos-delay="350">
                                    <i class="bi bi-people"></i>
                                    <div class="detail-content">
                                        <span>Público Objetivo</span>
                                        <strong>Ciudadanos, estudiantes y oficinistas</strong>
                                    </div>
                                </div>

                                <div class="detail-item" data-aos="fade-up" data-aos-delay="400">
                                    <i class="bi bi-shield-check"></i>
                                    <div class="detail-content">
                                        <span>Seguridad Central</span>
                                        <strong>Protección de datos y control seguro</strong>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

            </div>

        </section><!-- /About Section -->


        <!-- Pricing Section (Services / Membresías) -->
        <section id="plans" class="services section">

            <div class="container section-title" data-aos="fade-up">
                <span class="subtitle">Nuestras Membresías</span>
                <h2>Planes y Suscripciones</h2>
                <p>Nexus Pass opera bajo un modelo de suscripción escalonado, generando un <strong>ahorro individual
                        aproximado del 80%</strong> en comparación al gasto segmentado en múltiples servicios y pases.
                </p>
            </div>

            <div class="container">
                <div class="row gy-4 justify-content-center">

                    <!-- Essential Plan -->
                    <div class="col-xl-4 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="pricing-card">
                            <img src="assets/img/tarjeta-bronce.jpeg" alt="Tarjeta Nexus Essential" class="card-img">
                            <div class="pricing-header">
                                <h3>Essential <span style="font-weight: 400;">(Bronce)</span></h3>
                                <div class="price">$149.99 <span>/mes</span></div>
                            </div>
                            <ul class="pricing-features">
                                <li><i class="bi bi-check-circle-fill"></i> Elige hasta <strong>5 beneficios</strong> exclusivos.</li>
                                <li><i class="bi bi-check-circle-fill"></i> Renovación de beneficios mensual.</li>
                                <li><i class="bi bi-check-circle-fill"></i> Acceso a hardware inteligente.</li>
                                <li style="color: #adb5bd;"><i class="bi bi-dash-circle" style="color: #adb5bd;"></i>
                                    Soporte prioritario.</li>
                                <li style="color: #adb5bd;"><i class="bi bi-dash-circle" style="color: #adb5bd;"></i>
                                    Eventos y preventas VIP.</li>
                            </ul>
                            <a href="../resources/lib/cartAction.php?action=addToCart&id=1&return=catalogo"
                                class="btn btn-outline-primary mt-auto rounded-pill py-2">Elegir Essential</a>
                        </div>
                    </div>

                    <!-- Urban Plan -->
                    <div class="col-xl-4 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="pricing-card popular">
                            <div class="popular-badge">Recomendado</div>
                            <img src="assets/img/tarjeta-plata.jpeg" alt="Tarjeta Nexus Urban" class="card-img">
                            <div class="pricing-header">
                                <h3>Urban <span style="font-weight: 400;">(Plata)</span></h3>
                                <div class="price">$329.99 <span>/mes</span></div>
                            </div>
                            <ul class="pricing-features">
                                <li><i class="bi bi-check-circle-fill"></i> Elige hasta <strong>10 beneficios</strong> exclusivos.</li>
                                <li><i class="bi bi-check-circle-fill"></i> Renovación de beneficios mensual.</li>
                                <li><i class="bi bi-check-circle-fill"></i> Acceso a hardware inteligente.</li>
                                <li><i class="bi bi-check-circle-fill"></i> Soporte prioritario.</li>
                                <li style="color: #adb5bd;"><i class="bi bi-dash-circle" style="color: #adb5bd;"></i>
                                    Eventos y preventas VIP.</li>
                            </ul>
                            <a href="../resources/lib/cartAction.php?action=addToCart&id=2&return=catalogo"
                                class="btn btn-primary mt-auto rounded-pill py-2"
                                style="background-color: var(--accent-color); border: none;">Elegir Urban</a>
                        </div>
                    </div>

                    <!-- Premium Plan -->
                    <div class="col-xl-4 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="pricing-card">
                            <img src="assets/img/tarjeta-oro.jpeg" alt="Tarjeta Nexus Premium" class="card-img">
                            <div class="pricing-header">
                                <h3>Premium <span style="font-weight: 400;">(Oro)</span></h3>
                                <div class="price">$499.99 <span>/mes</span></div>
                            </div>
                            <ul class="pricing-features">
                                <li><i class="bi bi-check-circle-fill"></i> Elige hasta <strong>15 beneficios</strong> exclusivos.</li>
                                <li><i class="bi bi-check-circle-fill"></i> Renovación de beneficios mensual.</li>
                                <li><i class="bi bi-check-circle-fill"></i> Acceso a hardware inteligente.</li>
                                <li><i class="bi bi-check-circle-fill"></i> Soporte VIP 24/7.</li>
                                <li><i class="bi bi-check-circle-fill"></i> Eventos y preventas VIP.</li>
                            </ul>
                            <a href="../resources/lib/cartAction.php?action=addToCart&id=3&return=catalogo"
                                class="btn btn-outline-primary mt-auto rounded-pill py-2">Elegir Premium</a>
                        </div>
                    </div>

                </div>
            </div>

        </section><!-- /Pricing Section -->

        <!-- Beneficios Section -->
        <section id="beneficios" class="portfolio section light-background">
            <div class="container section-title" data-aos="fade-up">
                <span class="subtitle">Beneficios Exclusivos</span>
                <h2>Sácale el máximo provecho a tu Membresía</h2>
                <p>Al adquirir un plan Nexus Pass, obtienes acceso a un catálogo rotativo de descuentos, meses gratis y
                    ventajas en nuestras marcas aliadas.</p>
            </div>

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="benefits-slider-wrapper" style="position: relative; padding: 0 50px;">
                    <div class="swiper benefits-swiper" data-aos="fade-up" data-aos-delay="200">
                        <div class="swiper-wrapper">
                        <?php
                        include_once '../resources/db/ProductoDB.php';
                        $todos = ProductoDB::getProductos();
                        $beneficios = [];
                        foreach ($todos as $p) {
                            if ($p['precio'] == 0 && $p['activo'] == 1 && strpos(strtolower($p['nombre']), 'tarjeta') === false) {
                                $beneficios[] = $p;
                            }
                        }
                        shuffle($beneficios);
                        $muestra = array_slice($beneficios, 0, 6);

                        foreach ($muestra as $prod):
                            $cat_color = "var(--accent-color)";
                            $nombre_cat_upper = mb_strtoupper($prod['categoria_nombre'], 'UTF-8');
                            if (strpos($nombre_cat_upper, 'COMIDA') !== false) {
                                $cat_color = "#ff9800";
                            } elseif (strpos($nombre_cat_upper, 'EDUCACIÓN') !== false || strpos($nombre_cat_upper, 'EDUCACION') !== false) {
                                $cat_color = "#4caf50";
                            } elseif (strpos($nombre_cat_upper, 'SERVICIOS') !== false) {
                                $cat_color = "#2196f3";
                            } elseif (strpos($nombre_cat_upper, 'SALUD') !== false) {
                                $cat_color = "#e91e63";
                            } elseif (strpos($nombre_cat_upper, 'DIVERSIÓN') !== false || strpos($nombre_cat_upper, 'DIVERSION') !== false) {
                                $cat_color = "#9c27b0";
                            }
                            ?>
                            <div class="swiper-slide">
                                <div class="portfolio-card d-flex align-items-center text-start"
                                    style="background: var(--surface-color); border: 1px solid rgba(255,255,255,0.05); border-radius: 15px; overflow: hidden; padding: 20px; height: 100%;">
                                    <div class="pe-3" style="flex: 1;">
                                        <span
                                            style="color: <?= $cat_color ?>; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;"><?= htmlspecialchars($prod['categoria_nombre']) ?></span>
                                        <h4 style="color: #ededed; font-size: 18px; margin-top: 5px;">
                                            <?= htmlspecialchars($prod['nombre']) ?>
                                        </h4>
                                        <p style="color: #adb5bd; font-size: 14px; margin-bottom: 0;">
                                            <?= htmlspecialchars($prod['descripcion']) ?>
                                        </p>
                                    </div>
                                    <div class="text-center" style="width: 100px;">
                                        <img src="../resources/uploads/<?= htmlspecialchars($prod['imagen']) ?>"
                                            alt="<?= htmlspecialchars($prod['nombre']) ?>"
                                            style="height: 100px; width: 100px; object-fit: contain; border-radius: 10px; background: rgba(255,255,255,0.1); padding: 10px;">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-pagination mt-4 position-relative"></div>
                </div>
                <!-- Navigation arrows outside the swiper container -->
                <div class="swiper-button-next" style="color: var(--accent-color); right: 10px; margin-top: -20px;"></div>
                <div class="swiper-button-prev" style="color: var(--accent-color); left: 10px; margin-top: -20px;"></div>
                </div>

                <style>
                .benefits-swiper { padding-bottom: 40px; position: relative; }
                .benefits-swiper .swiper-pagination-bullet { background: rgba(255,255,255,0.2); opacity: 1; }
                .benefits-swiper .swiper-pagination-bullet-active { background: var(--accent-color); }
                </style>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    new Swiper('.benefits-swiper', {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        loop: true,
                        autoplay: {
                            delay: 3000,
                            disableOnInteraction: false,
                        },
                        pagination: {
                            el: '.benefits-swiper .swiper-pagination',
                            clickable: true,
                        },
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        breakpoints: {
                            768: {
                                slidesPerView: 2,
                            },
                            992: {
                                slidesPerView: 3,
                            }
                        }
                    });
                });
                </script>

                <div class="text-center mt-5">
                    <a href="catalogo.php" class="btn btn-outline-light rounded-pill px-4 py-2">Ver Catálogo
                        Completo</a>
                </div>
            </div>
        </section><!-- /Beneficios Section -->

        <!-- Ecosistema ITIL Section (Resume) -->
        <section id="ecosystem" class="resume section light-background">

            <div class="container section-title" data-aos="fade-up">
                <span class="subtitle">Ecosistema ITIL</span>
                <h2>Gobierno de Servicios TI</h2>
                <p>ITIL (Information Technology Infrastructure Library) es un marco de trabajo de mejores prácticas para
                    ITSM. Ayudamos a gestionar la infraestructura central para garantizar el buen funcionamiento
                    operacional.</p>
            </div>

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-5">
                    <div class="col-lg-6">
                        <div class="experience-section">
                            <div class="section-header" data-aos="fade-right" data-aos-delay="200">
                                <div class="header-content">
                                    <span class="section-badge">Proveeduría</span>
                                    <h2>Operación del Servicio</h2>
                                </div>
                            </div>

                            <div class="experience-cards">
                                <div class="exp-card featured" data-aos="zoom-in" data-aos-delay="300">
                                    <div class="card-header">
                                        <div class="company-logo">
                                            <i class="bi bi-clipboard-pulse"></i>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h3>Servicio Principal</h3>
                                        <p class="company-name">Transacciones Seguras</p>
                                        <p class="description">Capacidad de facilitar las transacciones seguras, accesos
                                            y la gestión de identidad sin que el usuario asuma los costos o riesgos de
                                            tener que mantener la infraestructura de servidores, bases de datos o
                                            lectores NFC.</p>
                                    </div>
                                </div>

                                <div class="exp-card" data-aos="zoom-in" data-aos-delay="350">
                                    <div class="card-header">
                                        <div class="company-logo">
                                            <i class="bi bi-shield-lock"></i>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h3>El Proveedor IT</h3>
                                        <p class="company-name">Equipo Técnico Nexus</p>
                                        <p class="description">El equipo de TI responsable de mantener siempre
                                            operativos los servidores, la base de datos (usuarios, pedidos) y garantizar
                                            la seguridad absoluta contra vulnerabilidades (como inyección SQL u otros
                                            vectores de ataque).</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="education-section">
                            <div class="section-header" data-aos="fade-left" data-aos-delay="200">
                                <div class="header-content">
                                    <span class="section-badge">Participación</span>
                                    <h2>El Ecosistema Humano</h2>
                                </div>
                            </div>

                            <div class="education-timeline" data-aos="fade-left" data-aos-delay="300">
                                <div class="timeline-track">
                                    <div class="timeline-item">
                                        <div class="timeline-marker">
                                            <i class="bi bi-person-heart"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="education-meta">
                                                <span class="year-range">Usuario Beneficiario</span>
                                            </div>
                                            <h4>El Cliente</h4>
                                            <p class="description">La persona que adquiere los planes (Essential, Urban
                                                o Premium) y utiliza activamente la plataforma para transaccionar todo,
                                                a través de su credencial inteligente y el dashboard web.</p>
                                        </div>
                                    </div>

                                    <div class="timeline-item">
                                        <div class="timeline-marker">
                                            <i class="bi bi- diagram-3-fill"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="education-meta">
                                                <span class="year-range">La Red Operativa</span>
                                            </div>
                                            <h4>Stakeholders</h4>
                                            <p class="description">La red externa que fomenta y adopta el ecosistema
                                                tecnológico de manera simultánea.</p>
                                            <div class="certifications-list mt-3">
                                                <div class="cert-item"><span class="cert-name">Inversores y
                                                        Socios</span></div>
                                                <div class="cert-item"><span class="cert-name">Transporte Público
                                                        MX</span></div>
                                                <div class="cert-item"><span class="cert-name">Franquicias
                                                        Comerciales</span></div>
                                                <div class="cert-item"><span class="cert-name">Reguladores
                                                        Gubernamentales</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </section><!-- /Ecosistema ITIL Section -->

        <!-- Contact Section -->
        <section id="contact" class="contact section">

            <div class="container section-title" data-aos="fade-up">
                <span class="subtitle">Soporte</span>
                <h2>Contacta a Soporte</h2>
                <p>En Nexus Pass, el soporte y la seguridad son prioridad máxima. Si tienes un problema con tu
                    credencial inteligente, membresía o cuenta en línea, háznoslo saber.</p>
            </div>

            <div class="container">

                <div class="row gy-4">

                    <div class="col-lg-4">
                        <div class="info-item">
                            <div class="icon-wrapper">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div>
                                <h3>Oficinas Centrales</h3>
                                <p>Carretera Federal Tizayuca-Pachuca Km. 2.5, Tizayuca, Hidalgo 43800, México</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="icon-wrapper">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div>
                                <h3>Llámanos</h3>
                                <p>+52 555 123 4567</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="icon-wrapper">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div>
                                <h3>Email General</h3>
                                <p>contacto@nexuspass.com</p>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-8">
                        <form action="forms/contact.php" method="post" class="php-email-form">
                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control" placeholder="Tu Nombre Completo"
                                        required="">
                                </div>

                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email"
                                        placeholder="Tu Correo Vinculado" required="">
                                </div>

                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="subject"
                                        placeholder="Asunto / Plan actual" required="">
                                </div>

                                <div class="col-md-12">
                                    <textarea class="form-control" name="message" rows="6"
                                        placeholder="Descripción del problema o reporte..." required=""></textarea>
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="loading">Cargando</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">Tu mensaje de soporte ha sido enviado. ¡Gracias!</div>
                                    <button type="submit" class="rounded-pill">Enviar Reporte</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

            </div>

        </section><!-- /Contact Section -->

    </main>

    <?php include_once '../resources/templates/footer.html'; ?>
    <?php include_once '../resources/templates/scripts.html'; ?>
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

</body>
</html>