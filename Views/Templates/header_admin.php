<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="description" content="Descripción de página web para los buscadores">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Autor del sitio web">
    <meta name="theme-color" content="#009688">
    <link rel="shortcut icon" href="<?= media(); ?>/images/favicon.ico">
    <title><?= isset($data['page_tag']) ? htmlspecialchars(
                $data['page_tag'],
                ENT_QUOTES,
                'UTF-8'
            ) : 'Sin título'; ?></title>
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/main.css">
    <!-- CSS personalizado-->
    <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/style.css">
</head>

<body class="app sidebar-mini">
    <!-- Navbar-->
    <header class="app-header">
        <a class="app-header__logo" href="<?= base_url() ?>/dashboard">Tienda Virtual</a>
        <!-- Sidebar toggle button-- >-->
        <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar">
            <i class="fas fa-bars"></i>
        </a>
        <!-- Navbar Right Menu-->
        <ul class="app-nav">
            <!-- User Menu-->
            <li class="dropdown">
                <a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu">
                    <i class="fa fa-user fa-lg"></i></a>
                <ul class="dropdown-menu settings-menu dropdown-menu-right">
                    <li><a class="dropdown-item" href="<?= base_url() ?>/opciones">
                            <i class="fa fa-cog fa-lg"></i> Opciones</a>
                    </li>
                    <li><a class="dropdown-item" href="<?= base_url() ?>/perfil">
                            <i class="fa fa-user fa-lg"></i> Perfil</a>
                    </li>
                    <li><a class="dropdown-item" href="<?= base_url() ?>/logout">
                            <i class="fa fa-sign-out fa-lg"></i> Cerrar sesión</a>
                    </li>
                </ul>
            </li>
        </ul>
    </header>
    <?php require_once("nav_admin.php"); ?>