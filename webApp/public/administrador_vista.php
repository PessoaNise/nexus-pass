<?php

session_start();
if (isset($_SESSION['usuario'])) {

    $PageTitle = "Panel de Administración";

    include_once '../resources/templates/head.html';
    include_once '../resources/templates/administrador_navegacion.html';

    // Extraer el username de la sesión para dar un saludo dinámico
    $adminName = htmlspecialchars($_SESSION['usuario']);
    ?>

    <main class="main">
        <section class="section dark-background" style="min-height: 80vh; padding-top: 100px; padding-bottom: 60px;">
            <div class="container" data-aos="fade-up">

                <!-- Banner de bienvenida -->
                <div class="row align-items-center mb-5 pb-4" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <div class="col-lg-8 text-center text-lg-start mb-4 mb-lg-0">
                        <h2 style="font-family: var(--heading-font); font-weight: 700; font-size: 38px; color: #ededed;">
                            Bienvenido, <span style="color: var(--accent-color);"><?= $adminName ?></span>
                        </h2>
                        <p style="color: #adb5bd; font-size: 18px; margin-bottom: 0;">Panel principal de Administración y
                            Liderazgo de Nexus Pass</p>
                    </div>
                    <!--
                    <div class="col-lg-4 text-center text-lg-end">
                        <img src="assets/img/admin.png"
                            style="width: 120px; border-radius: 50%; opacity: 0.9; box-shadow: 0px 5px 15px rgba(0,0,0,0.3);"
                            alt="Admin Avatar"
                            onerror="this.src='assets/img/nexus-logo.png'; this.style.filter='invert(100%)'; this.style.borderRadius='0'; this.style.boxShadow='none';">
                    </div>
                    -->
                </div>

                <!-- Panel de Políticas Institucionales -->
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="card p-5"
                            style="background: var(--surface-color); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.2);">

                            <h5 class="mb-4"
                                style="color: #ededed; font-family: var(--heading-font); font-weight: 600; font-size: 24px;">
                                <i class="bi bi-shield-check me-2" style="color: var(--accent-color);"></i> Políticas y
                                Lineamientos Internos
                            </h5>

                            <p style="color: #adb5bd; font-size: 15px; margin-bottom: 30px;">
                                Es deber de todos los altos directivos y administradores de la organización recordar y
                                fomentar la siguiente visión estructural en todas sus decisiones:
                            </p>

                            <ul
                                style="list-style: none; padding-left: 0; display: grid; gap: 20px; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
                                <li
                                    style="background: rgba(255,255,255,0.02); padding: 25px; border-radius: 12px; border-top: 4px solid var(--accent-color); align-self: start;">
                                    <strong style="color: #ededed; display: block; margin-bottom: 10px; font-size: 16px;"><i
                                            class="bi bi-people-fill me-2 text-primary"></i>Soluciones a la
                                        Comunidad</strong>
                                    <span style="color: #adb5bd; font-size: 14px; line-height: 1.6;">Facilitar posibles
                                        soluciones a las necesidades de la comunidad que se encuentre en el entorno de la
                                        empresa como resultado final de la misma.</span>
                                </li>

                                <li
                                    style="background: rgba(255,255,255,0.02); padding: 25px; border-radius: 12px; border-top: 4px solid var(--accent-color); align-self: start;">
                                    <strong style="color: #ededed; display: block; margin-bottom: 10px; font-size: 16px;"><i
                                            class="bi bi-gem me-2 text-primary"></i>Satisfacción del Cliente</strong>
                                    <span style="color: #adb5bd; font-size: 14px; line-height: 1.6;">Brindar a sus clientes
                                        los productos o servicios que siempre desean impulsados por la innovación de Nexus
                                        Pass.</span>
                                </li>

                                <li
                                    style="background: rgba(255,255,255,0.02); padding: 25px; border-radius: 12px; border-top: 4px solid var(--accent-color); align-self: start;">
                                    <strong style="color: #ededed; display: block; margin-bottom: 10px; font-size: 16px;"><i
                                            class="bi bi-briefcase-fill me-2 text-primary"></i>Ambiente Laboral
                                        Óptimo</strong>
                                    <span style="color: #adb5bd; font-size: 14px; line-height: 1.6;">Proporcionar a los
                                        empleados de la organización un ambiente agradable, reconfortante, seguro y
                                        divertido como parte del estímulo para sus labores.</span>
                                </li>

                                <li
                                    style="background: rgba(255,255,255,0.02); padding: 25px; border-radius: 12px; border-top: 4px solid var(--accent-color); align-self: start;">
                                    <strong style="color: #ededed; display: block; margin-bottom: 10px; font-size: 16px;"><i
                                            class="bi bi-journal-bookmark-fill me-2 text-primary"></i>Capacitación
                                        Continua</strong>
                                    <span style="color: #adb5bd; font-size: 14px; line-height: 1.6;">Facilitar y promocionar
                                        cursos de capacitación que formen parte de un proceso obligatorio a los nuevos
                                        ingresos de la empresa.</span>
                                </li>

                                <li
                                    style="background: rgba(255,255,255,0.02); padding: 25px; border-radius: 12px; border-top: 4px solid #dc3545; align-self: start;">
                                    <strong style="color: #dc3545; display: block; margin-bottom: 10px; font-size: 16px;"><i
                                            class="bi bi-exclamation-triangle-fill me-2"></i>Cero Tolerancia</strong>
                                    <span style="color: #adb5bd; font-size: 14px; line-height: 1.6;">Rechazar
                                        categóricamente la corrupción tanto en los cargos altos como medios de la
                                        organización bajo cualquier circunstancia.</span>
                                </li>

                                <li
                                    style="background: rgba(255,255,255,0.02); padding: 25px; border-radius: 12px; border-top: 4px solid var(--accent-color); align-self: start;">
                                    <strong style="color: #ededed; display: block; margin-bottom: 10px; font-size: 16px;"><i
                                            class="bi bi-suit-heart-fill me-2 text-primary"></i>Espíritu Ético</strong>
                                    <span style="color: #adb5bd; font-size: 14px; line-height: 1.6;">Fomentar un espíritu
                                        laboral agradable tanto en líderes como empleados para el sano y correcto
                                        funcionamiento de la empresa.</span>
                                </li>

                                <li
                                    style="background: rgba(255,255,255,0.02); padding: 25px; border-radius: 12px; border-top: 4px solid var(--accent-color); align-self: start; grid-column: 1 / -1;">
                                    <strong style="color: #ededed; display: block; margin-bottom: 10px; font-size: 16px;"><i
                                            class="bi bi-stars me-2 text-primary"></i>Desarrollo Continuo</strong>
                                    <span style="color: #adb5bd; font-size: 14px; line-height: 1.6;">Formar nuevos
                                        trabajadores de forma directa e indirecta para el desarrollo óptimo empresarial
                                        liderado por Nexus.</span>
                                </li>
                            </ul>
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
