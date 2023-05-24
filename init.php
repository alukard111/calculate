<?php
    require_once __DIR__.'/vendor/autoload.php';

    $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/view');
    $twig = new \Twig\Environment($loader, [
        'cache' => false,
    ]);


