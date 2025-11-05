<?php

// Lo que hace el defined y die es evitar el acceso directo al archivo header.php desde el navegador
if (!defined('ROOT_PATH')) {

    die('Acceso no permitido.');
}

$listPage = 'listActivities.php';
$createPage = 'createActivity.php';
$logoSrc = 'assets/img/small-logo_1.jpg';
$uploadIcon = '<span class="octicon octicon-cloud-upload"></span>';

$pageTitle = $pageTitle ?? "4VGym";
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/octicons/3.5.0/octicons.min.css">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-light navbar-fixed-top navbar-expand-md" role="navigation">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarToggler02">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="navbar-brand" href="<?= htmlspecialchars($listPage) ?>">
                    <img class="img-fluid rounded d-inline-block align-top" src="<?= htmlspecialchars($logoSrc) ?>" alt="4VGYM Logo" width="30" height="30">
                    4VGYM
                </a>
            </li>
        </ul>
        <div class="ml-auto">
            <a type="button" class="btn btn-info" href="<?= htmlspecialchars($createPage) ?>">
                <?= $uploadIcon ?> Subir Actividad
            </a>
        </div>
    </div>
</nav>

<div class="main-content">