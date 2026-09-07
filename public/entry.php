<?php

declare(strict_types=1);

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// tokenの把握
if ('' === ($token = strval($_GET['token'] ?? ''))) {
    // tokenがないのでinputに飛ばす
    header('Location: /index.php');
    exit;
}

// テンプレートエンジンを使う
require_once __DIR__ . '/../vendor/autoload.php';
$loader = new FilesystemLoader(__DIR__ . '/../views');
$twig = new Environment($loader, [
  // 開発時だけ有効化
  // 'strict_variables' => true,
]);

/* tokenの確認 */
// DBハンドルの取得
$config = require __DIR__ . '/../config.php';
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
    // データの取得
    // プリペアドステートメント
    $sql = 'SELECT * FROM ticket_purchases WHERE token = :token;';
    $pre = $dbh->prepare($sql);
    //
    $pre->bindValue(':token', $token, PDO::PARAM_STR);
    //
    $pre->execute();
    $datum = $pre->fetch();
} catch (\PDOException $e) {
    // XXX 暫定: 本来はlogに出力する & エラーページを出力する
    echo $e->getMessage();
    exit;
}

// なかったらエラー出力
if (false === $datum) {
    echo $twig->render('entry_error.twig');
    exit;
}

// あったら最低限の情報表示
echo $twig->render('entry.twig', [
    'purchaser_name' => $datum['purchaser_name'],
    'quantity' => (int)$datum['quantity'],
]);
