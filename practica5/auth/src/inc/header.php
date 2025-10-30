<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metadatos básicos del documento -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Hoja de estilo externa de ejemplo -->
    <link rel="stylesheet" href="https://www.phptutorial.net/app/css/style.css">
    <!-- El título puede ser inyectado por la vista llamando a view('header', ['title' => '...']) -->
    <title><?= $title ?? 'Home' ?></title>
</head>
<body>
<!-- Contenedor principal -->
<main>
<?php flash() ?>
<!-- Muestra todos los mensajes flash pendientes (si los hay) -->