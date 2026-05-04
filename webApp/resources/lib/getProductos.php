<?php

function getProductos($id, $palabra, $logeado) {

    if ($id == 0) {
        $productos = ProductoDB::getProductos();

    } else {
        $productos = ProductoDB::getProductosPorCategoriaId($id);
    }

    foreach ($productos as $producto) {
        if (!$palabra=="") {
            $arr = explode(" ", $producto['nombre']);
            if (in_array($palabra, $arr)){
                ?>
                <div class="card m-2" style="width: 15rem;">
                    <img src="../resources/uploads/<?= $producto['imagen'] ?>" class="card-img-top mt-3" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"><?= $producto['nombre'] ?></h5>
                        <p class="card-text "><?= substr($producto['descripcion'], 0, 150) . '...' ?></p>
                        <p class="card-text ">$<?= $producto['precio'] ?></p>
                        <a href="vista_previa.php?id=<?= $producto['id'] ?>" class="btn btn-primary">Comprar</a>
                    </div>
                </div>
            <?php }
        } else {
            ?>
            <div class="card m-2" style="width: 15rem;">
                <img src="../resources/uploads/<?= $producto['imagen'] ?>" class="card-img-top mt-3" alt="...">
                <div class="card-body">
                    <h5 class="card-title"><?= $producto['nombre'] ?></h5>
                    <p class="card-text "><?= substr($producto['descripcion'], 0, 150) . '...' ?></p>
                    <p class="card-text ">$<?= $producto['precio'] ?></p>
                    <a href="vista_previa.php?id=<?= $producto['id'] ?>" class="btn btn-primary">Comprar</a>
                </div>
            </div>
            <?php
        }
    }
}
