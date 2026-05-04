<?php

function sanitizacion($data) {
    $data = trim($data); // Elimina espacio en blanco (u otro tipo de caracteres) del inicio y el final de la cadna
    $data = stripslashes($data); // (\) se convierte en () y Barras invertidas dobles (\\) se convierten en una sencilla (\).
    // Se remueve htmlspecialchars para no hacer doble escape en la base de datos
    return $data;
}