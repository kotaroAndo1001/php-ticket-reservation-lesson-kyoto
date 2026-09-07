<?php

declare(strict_types=1);

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// セッション開始
ob_start();
session_start();

// セッションの内容を確認
$input = $_SESSION['input'] ?? [];
$errord = $_SESSION['errord'] ?? [];
// セッションの内容をクリア
unset($_SESSION['input'], $_SESSION['errord']);

// テンプレートエンジンを使う
require_once __DIR__ . '/../vendor/autoload.php';
$loader = new FilesystemLoader(__DIR__ . '/../views');
$twig = new Environment($loader, [
  // 開発時だけ有効化
  // 'strict_variables' => true,
]);



// 表示
echo $twig->render('index.twig', [
    'input' => $input,
    'errord' => $errord,
]);
