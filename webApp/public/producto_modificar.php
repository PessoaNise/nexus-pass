<?php

session_start();
if (isset($_SESSION['usuario'])) {

    include '../resources/db/ProductoDB.php';
    include '../resources/lib/sanitizacion.php';

    if (!isset($_POST['modificar'])) { 
        $producto = ProductoDB::getProductoPorId($_POST['id']);

        if (!$producto) {
            header("Location:productos_ver.php");
            exit();
        }

        $id = $_POST['id'];
        $nombre = $producto['nombre'];
        $categoriaSel = $producto['categoria_id'];
        $precio = $producto['precio'];
        $descripcion = $producto['descripcion'];
        $imagenOriginal = $producto['imagen'];
        $stock = $producto['stock'];
        $activo = $producto['activo'];
    }

    $errores = [];
    if (isset($_POST['modificar'])) { 
        $id = $_POST['id'];
        $imagenOriginal = $_POST["imagenOriginal"];

        if (empty($_POST['nombre'])) {
            $errores['nombre'] = "se requiere el nombre del producto";
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

        $activo = isset($_POST['activo']) ? 1 : 0;

        if (count($errores) == 0) {
            $nombreImagen = $imagenOriginal; // Conservar original si no se sube una nueva
            
            if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) { 
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
                    $errorProceso = "Hubo un problema guardando la nueva imagen.";
                }
            }
            
            if (!isset($errorProceso)) {
                $arreglo = [
                    'id' => $id,
                    'categoria_id' => $categoriaSel,
                    'nombre' => $nombre,
                    'descripcion' => $descripcion,
                    'precio' => $precio,
                    'imagen' => $nombreImagen,
                    'stock' => $stock,
                    'activo' => $activo
                ];
                
                $resultadoActualizar = ProductoDB::modificaProducto($arreglo);
                
                if ($resultadoActualizar === false) {
                    $errorProceso = "Hubo un problema al actualizar el producto en la base de datos.";
                }
            }
        }
    }

    $PageTitle = "Modificar producto";

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
        <div class="container-md mt-5" data-aos="fade-up">
            <h2 class="text-center text-light mb-4" style="font-family: var(--heading-font); font-weight: 700;">Modificar y Desactivar Producto</h2>

            <div class="card p-5" style="background: var(--surface-color); border: 1px solid rgba(255,255,255,0.05); border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                <form method="POST" novalidate action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" enctype="multipart/form-data">
                    <input type="hidden" name="imagenOriginal" value="<?= htmlspecialchars($imagenOriginal) ?>">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

                    <?php if (!empty($imagenOriginal) && file_exists('../resources/uploads/' . $imagenOriginal)): ?>
                    <div class="text-center mb-4">
                        <img src="<?= '../resources/uploads/' . htmlspecialchars($imagenOriginal) ?>" style="height: 150px; border-radius: 10px; border: 2px solid rgba(255,255,255,0.1);">
                        <p class="text-muted mt-2" style="font-size: 13px;">Imagen actual</p>
                    </div>
                    <?php endif; ?>

                    <div class="row row-cols-md-2 row-cols-1 g-4">
                        <div class="col">
                            <div class="mb-3">
                                <label for="nombre" class="form-label" style="color: #adb5bd; font-weight: 600;">Nombre producto</label>
                                <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($nombre) ?>" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color);">
                                <span class="text-danger" style="font-size:12px;"><?php if (isset($errores['nombre'])) print($errores['nombre']) ?></span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="categoria_id" style="color: #adb5bd; font-weight: 600;">Clasificación (Categoría):</label>
                                <select class="form-select" id="categoria_id" name="categoria_id" style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color);">
                                    <?php
                                    include_once '../resources/db/CategoriaDB.php';
                                    $categorias = CategoriaDB::getCategorias();
                                    foreach ($categorias as $categoria): ?>
                                        <option style="color: black;" value="<?= $categoria['id'] ?>" <?php if ($categoria['id'] == $categoriaSel) print('selected') ?>> <?= htmlspecialchars($categoria['nombre']) ?></option>
                                    <?php endforeach ?>
                                </select>
                                <span class="text-danger" style="font-size:12px;"><?php if (isset($errores['categoria_id'])) print($errores['categoria_id']) ?></span>
                            </div>

                            <div class="mb-3">
                                <label for="precio" class="form-label" style="color: #adb5bd; font-weight: 600;">Precio Público ($)</label>
                                <input type="text" class="form-control" name="precio" value="<?= htmlspecialchars($precio) ?>" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color);">
                                <span class="text-danger" style="font-size:12px;"><?php if (isset($errores['precio'])) print($errores['precio']) ?></span>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label" for="imagen" style="color: #adb5bd; font-weight: 600;">Reemplazar Fotografía:</label>
                                <input class="form-control" type="file" name="imagen" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color);">
                                <span class="text-muted" style="font-size:12px;">Déjalo en blanco si no quieres cambiar la imagen.</span>
                            </div>
                        </div>

                        <div class="col">
                            <div class="mb-3">
                                <label for="descripcion" class="form-label" style="color: #adb5bd; font-weight: 600;">Descripción Detallada</label>
                                <textarea id="descripcion" class="form-control" rows="5" name="descripcion" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color);"><?php if (isset($descripcion)) print(htmlspecialchars($descripcion)) ?></textarea>
                                <span class="text-danger" style="font-size:12px;"><?php if (isset($errores['descripcion'])) print($errores['descripcion']) ?></span>
                            </div>

                            <div class="row g-3">
                                <div class="col-6">
                                    <label for="stock" class="form-label" style="color: #adb5bd; font-weight: 600;">Stock en almacén</label>
                                    <input type="number" class="form-control" name="stock" value="<?= htmlspecialchars($stock) ?>" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--default-color);" placeholder="Ej: -1 para Ilimitado">
                                    <span class="text-danger" style="font-size:12px;"><?php if (isset($errores['stock'])) print($errores['stock']) ?></span>
                                </div>
                                <div class="col-6 d-flex align-items-center mt-5">
                                    <div class="form-check form-switch ps-5">
                                        <input class="form-check-input" type="checkbox" role="switch" id="activo" name="activo" <?= ($activo == 1) ? 'checked' : '' ?> style="transform: scale(1.5);">
                                        <label class="form-check-label ms-3" for="activo" style="color: #adb5bd; font-weight: 600;">Publicado (Activo)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-5">
                        <a href="productos_ver.php" class="btn btn-outline-light px-5 py-2" style="border-radius: 50px;">Cancelar</a>
                        <button type="submit" name="modificar" class="btn btn-primary px-5 py-2" style="background-color: var(--accent-color); border: none; font-weight: bold; border-radius: 50px;">Guardar Cambios</button>
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
            if (categorySelect.selectedIndex >= 0 && categorySelect.value !== "") {
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
                text: "<?=$errorProceso ?>",
                icon: "error",
                timer: 4000
            });
        </script>
    <?php endif ?>

    <?php if (isset($resultadoActualizar) && $resultadoActualizar !== false): ?>
        <script>
            Swal.fire({
                title: "¡Éxito!",
                text: "El inventario del producto ha sido actualizado.",
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
