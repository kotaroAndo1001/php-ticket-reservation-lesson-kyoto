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

// 認可チェック
if (false === isset($_SESSION['admin_logged_in'])) {
    // ログイン画面へ
    header('Location: /admin/index.php');
    exit;
}

// 表示
echo $twig->render('admin/top.twig');
