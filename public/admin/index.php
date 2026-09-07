<?php

declare(strict_types=1);

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// セッション開始
ob_start();
session_start();

// テンプレートエンジンを使う
require_once __DIR__ . '/../../vendor/autoload.php';
$loader = new FilesystemLoader(__DIR__ . '/../../views');
$twig = new Environment($loader);

// セッションの内容を確認
$input = $_SESSION['input'] ?? [];
$errord = $_SESSION['errord'] ?? [];
// セッションの内容をクリア
unset($_SESSION['input'], $_SESSION['errord']);

// 表示
echo $twig->render('admin/index.twig', [
    'input' => $input,
    'errord' => $errord,
]);
