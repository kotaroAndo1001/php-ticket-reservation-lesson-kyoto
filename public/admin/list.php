<?php

declare(strict_types=1);

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// セッション開始
ob_start();
session_start();

// タイムゾーン
date_default_timezone_set('Asia/Tokyo');

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

/* 一覧取得 */
// DB接続情報
// DBハンドルの取得
$config = require __DIR__ . '/../../config.php';
$db_config = $config['db'];
$dsn = "mysql:dbname={$db_config['database']};host={$db_config['host']};port={$db_config['port']};charset={$db_config['charset']}";
$opt = [
    // セキュリティ上必須
    PDO::ATTR_EMULATE_PREPARES => false,  // エミュレート無効
    PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,  // 複文無効
    // お好みで
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // データ取得モード
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // エラーが発生した場合、PDOException をスロー
];
try {
    $dbh = new \PDO($dsn, $db_config['user'], $db_config['pass'], $opt);
} catch (\PDOException $e) {
    // XXX 暫定: 本来はlogに出力する & エラーページを出力する
    echo $e->getMessage();
    exit;
}

try {
    // データの登録
    // プリペアドステートメント
    $stmt = $dbh->prepare('SELECT * FROM ticket_purchases ORDER BY created_at DESC LIMIT 100');
    $stmt->execute();
    $list = $stmt->fetchAll();
} catch (\PDOException $e) {
    // XXX 暫定: 本来はlogに出力する & エラーページを出力する
    echo $e->getMessage();
    exit;
}

// 表示
$base_url = 'http://game.m-fr.net:8080/';
echo $twig->render('admin/list.twig', [
    'list' => $list,
    'base_url' => $base_url,
]);
