<?php

// 完了ページの表示

declare(strict_types=1);

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// テンプレートエンジンを使う
require_once __DIR__ . '/../vendor/autoload.php';
$loader = new FilesystemLoader(__DIR__ . '/../views');
$twig = new Environment($loader, [
  // 開発時だけ有効化
  // 'strict_variables' => true,
]);

// 表示
echo $twig->render('fin_print.twig', []);
