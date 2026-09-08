<?php

// セッション開始
ob_start();
session_start();

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// テンプレートエンジンを使う
require_once __DIR__ . '/../vendor/autoload.php';
$loader = new FilesystemLoader(__DIR__ . '/../views');
$twig = new Environment($loader, [
  // 開発時だけ有効化
  // 'strict_variables' => true,
]);