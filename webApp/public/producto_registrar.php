<?php

session_start();
if (isset($_SESSION['usuario'])) {

    include '../resources/db/ProductoDB.php';
    include '../resources/lib/sanitizacion.php';

    $nombre = $precio = $descripcion = $categoriaSel = $stock = "";
    $errores = [];

    if (isset($_POST['registrar'])) {

        if (empty($_POST['nombre'])) {
            $errores['nombre'] = "Se requiere el nombre del producto";
        } else {
            $nombre = sanitizacion($_POST["nombre"]);
        }

        if (empty($_POST['categoria_id'])) {
            $errores['categoria_id'] = "Se requiere una categoría";
        } else {
            $categoriaSel = $_POST["categoria_id"];
        }

        if (empty($_POST['precio']) && $_POST['precio'] !== '0' && $_POST['precio'] !== '0.00') {
            $errores['precio'] = "Se requiere el precio";
        } else {
            $precio = sanitizacion($_POST['precio']);
            if (filter_var($precio, FILTER_VALIDATE_FLOAT) === false)
                $errores['precio'] = "No es un formato de precio válido";
        }

        if (empty($_POST['descripcion'])) {
            $errores['descripcion'] = "Se requiere una descripción";
        } else {
            $descripcion = sanitizacion($_POST['descripcion']);
        }

        if (empty($_POST['stock']) && $_POST['stock'] !== '0') {
            $errores['stock'] = "Indica cuantas unidades hay en stock";
        } else {
            $stock = sanitizacion($_POST['stock']);
            if (!filter_var($stock, FILTER_VALIDATE_INT) && $stock !== '0')
                $errores['stock'] = "No es un formato entero válido";
        }

        if (count($errores) == 0) {
            $activo = isset($_POST['activo']) ? 1 : 0;

            // Subida de imagen a carpeta local /uploads sin depender de otra tabla (nuevo esquema)
            $nombreImagen = 'default.png';
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['imagen']['tmp_name'];
                $fileName = $_FILES['imagen']['name'];

                $newFileName = md5(time() . $fileName) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);

                $uploadPath = '../resources/uploads/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $dest_path = $uploadPath . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $nombreImagen = $newFileName;
                } else {
                    $errorProceso = "Hubo un problema guardando la imagen subida en el servidor.";
                }
            }

            if (!isset($errorProceso)) {
                $arregloNuevo = [
                    'categoria_id' => $categoriaSel,
                    'nombre' => $nombre,
                    'descripcion' => $descripcion,
                    'precio' => $precio,
                    'imagen' => $nombreImagen,
                    'stock' => $stock,
                    'activo' => $activo
                ];

                $resultadoRegistrarProducto = ProductoDB::insertaProducto($arregloNuevo);

                if (!$resultadoRegistrarProducto) {
                    $errorProceso = "Error en base de datos al guardar producto.";
                }
            }
        }
    }

    $PageTitle = "Registrar producto";

    include '../resources/templates/head.html';
    include '../resources/templates/administrador_navegacion.html';

    ?>
    <main class="main">
        <section class="section dark-background" style="min-height: 80vh; padding-top: 100px; padding-bottom: 60px;">
            <style>
                .form-control::placeholder {
                    color: #adb5bd !important;
                    opacity: 0.8;
                }
            </style>
            <div class="container-sm mt-5" data-aos="fade-up">
                <h2 class="text-center text-light mb-4" style="font-family: var(--heading-font); font-weight: 700;">Alta de
                    Inventario</h2>

                <div class="card p-5"
                    style="background: var(--surface-color); border: 1px solid rgba(255,255,255,0.05); border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                    <form method="POST" novalidate action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                        enctype="multipart/form-data">
                        <div class="row row-cols-md-2 row-cols-1 g-4">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label" style="color: #adb5bd; font-weight: 600;">Nombre
                                        producto</label>
                                    <input type="text" class="form-control" name="nombre"
                                        value="<?= htmlspecialchars($nombre) ?>"
                                        style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color);">
                                    <span class="text-danger" style="font-size:12px;"><?php if (isset($errores['nombre']))
                                        print ($errores['nombre']) ?></span>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="categoria_id"
                                            style="color: #adb5bd; font-weight: 600;">Clasificación (Categoría):</label>
                                        <select class="form-select" id="categoria_id" name="categoria_id"
                                            style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color);">
                                            <option value="" <?php if (empty($categoriaSel))
                                        print ('selected') ?>>Selecciona...
                                            </option>
                                            <?php
                                    include_once '../resources/db/CategoriaDB.php';
                                    $categorias = CategoriaDB::getCategorias();
                                    foreach ($categorias as $categoria): ?>
                                            <option style="color: black;" value="<?= $categoria['id'] ?>" <?php if ($categoria['id'] == $categoriaSel)
                                                  print ('selected') ?>>
                                                <?= htmlspecialchars($categoria['nombre']) ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                    <span class="text-danger" style="font-size:12px;"><?php if (isset($errores['categoria_id']))
                                        print ($errores['categoria_id']) ?></span>
                                    </div>

                                    <div class="mb-3">
                                        <label for="precio" class="form-label" style="color: #adb5bd; font-weight: 600;">Precio
                                            Público ($)</label>
                                        <input type="text" class="form-control" name="precio"
                                            value="<?= htmlspecialchars($precio) ?>"
                                        style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color);">
                                    <span class="text-danger" style="font-size:12px;"><?php if (isset($errores['precio']))
                                        print ($errores['precio']) ?></span>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="imagen"
                                            style="color: #adb5bd; font-weight: 600;">Adjuntar Fotografía:</label>
                                        <input class="form-control" type="file" name="imagen"
                                            style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color);">
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label"
                                            style="color: #adb5bd; font-weight: 600;">Descripción Detallada</label>
                                        <textarea id="descripcion" class="form-control" rows="5" name="descripcion"
                                            style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color);"><?php if (isset($descripcion))
                                        print (htmlspecialchars($descripcion)) ?></textarea>
                                        <span class="text-danger" style="font-size:12px;"><?php if (isset($errores['descripcion']))
                                        print ($errores['descripcion']) ?></span>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-6">
                                            <label for="stock" class="form-label"
                                                style="color: #adb5bd; font-weight: 600;">Stock en almacén</label>
                                            <input type="number" class="form-control" name="stock"
                                                value="<?= htmlspecialchars($stock) ?>"
                                            style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color);"
                                            placeholder="Ej: -1 para Ilimitado">
                                        <span class="text-danger" style="font-size:12px;"><?php if (isset($errores['stock']))
                                            print ($errores['stock']) ?></span>
                                        </div>
                                        <div class="col-6 d-flex align-items-center mt-5">
                                            <div class="form-check form-switch ps-5">
                                                <input class="form-check-input" type="checkbox" role="switch" id="activo"
                                                    name="activo" checked style="transform: scale(1.5);">
                                                <label class="form-check-label ms-3" for="activo"
                                                    style="color: #adb5bd; font-weight: 600;">Publicado (Activo)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-5">
                                <button type="submit" name="registrar" class="btn btn-primary px-5 py-2"
                                    style="background-color: var(--accent-color); border: none; font-weight: bold; border-radius: 50px;">Guardar
                                    Producto</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </main>

        <?php
        include '../resources/templates/footer.html';
        include '../resources/templates/scripts.html';
        ?>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('categoria_id');
            const priceInput = document.querySelector('input[name="precio"]');
            
            function checkCategory() {
                if (categorySelect.selectedIndex > 0) {
                    const selectedText = categorySelect.options[categorySelect.selectedIndex].text.toLowerCase();
                    // Si no es Membresía (o Hardware si existe), forzar precio a 0
                    if (!selectedText.includes('membresía') && !selectedText.includes('membresia') && !selectedText.includes('hardware')) {
                        priceInput.value = '0.00';
                        priceInput.readOnly = true;
                    } else {
                        priceInput.readOnly = false;
                    }
                } else {
                    priceInput.readOnly = false;
                }
            }

            categorySelect.addEventListener('change', checkCategory);
            checkCategory(); // Check on load
        });
        </script>

    <?php if (isset($errorProceso)): ?>
        <script>
            Swal.fire({
                title: "Error",
                text: "<?= $errorProceso ?>",
                icon: "error",
                timer: 4000
            });
        </script>
    <?php endif ?>

    <?php if (isset($resultadoRegistrarProducto)): ?>
        <script>
            Swal.fire({
                title: "¡Éxito!",
                text: "Producto añadido a la base de datos",
                icon: "success",
                timer: 2000
            }).then(function () {
                window.location = "productos_ver.php";
            });
        </script>
    <?php endif ?>

    <?php
    include '../resources/templates/fin.html';

} else {
    header("Location:login_error.php");
    exit();
}
