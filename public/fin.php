<?php

declare(strict_types=1);

// 初期化ファイルの読み込み (ここで getDbh() や Twig が有効になります)
require_once __DIR__ . '/initialize.php';

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// タイムゾーン
date_default_timezone_set('Asia/Tokyo');

// 入力を受け取る
$input = [
    'purchaser_name' => $_POST['purchaser_name'] ?? '',
    'email' => $_POST['email'] ?? '',
    'quantity' => trim($_POST['quantity'] ?? ''),
];

/* validate */
$errord = [];
// 氏名の入力
if ($input['purchaser_name'] === '') {
    $errord['purchaser_name'] = '氏名を入力してください';
}

// メアドの確認
if ($input['email'] === '') {
    $errord['email'] = 'emailを入力してください';
} elseif (false === filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
    $errord['email'] = 'emailのフォーマットがおかしいです';
}

// チケットの枚数
if ($input['quantity'] === '') {
    $errord['quantity'] = 'チケット枚数を入力してください';
} elseif (false === filter_var($input['quantity'], FILTER_VALIDATE_INT)) {
    $errord['quantity'] = 'チケット枚数のフォーマットがおかしいです';
}

// エラーがあった場合、入力フォームに戻す
if (count($errord) > 0) {
    $_SESSION['errord'] = $errord;
    $_SESSION['input'] = $input;
    header('Location: index.php');
    exit;
}

// tokenの作成
$token = bin2hex(random_bytes(32));

/* DBへの登録 */
$dbh = getDbh();

try {
    // データの登録 (ticket_purchases テーブル)
    $sql = 'INSERT INTO ticket_purchases(email, purchaser_name, quantity, token, created_at, updated_at)
        VALUES(:email, :purchaser_name, :quantity, :token, :created_at, :updated_at);
    ';
    $pre = $dbh->prepare($sql);

    $now = date(DATE_ATOM);
    $pre->bindValue(':email', $input['email'], PDO::PARAM_STR);
    $pre->bindValue(':purchaser_name', $input['purchaser_name'], PDO::PARAM_STR);
    $pre->bindValue(':quantity', $input['quantity'], PDO::PARAM_INT);
    $pre->bindValue(':token', $token, PDO::PARAM_STR);
    $pre->bindValue(':created_at', $now, PDO::PARAM_STR);
    $pre->bindValue(':updated_at', $now, PDO::PARAM_STR);

    $pre->execute();
    
    // auto incrementされたIDを取得
    $ticket_purchase_id = $dbh->lastInsertId();

    // mailの送信準備
    $sent_at = (new DateTimeImmutable())->format('Y-m-d H:i:s');
    $base_url = 'http://game.m-fr.net:8080';
    $subject = '【チケット購入完了】チケット購入ありがとうございます';
    $body = $twig->render('ticket_purchase_complete.twig', [
        'purchaser_name' => $input['purchaser_name'],
        'quantity' => $input['quantity'],
        'base_url' => $base_url,
        'token' => $token,
    ]);

    // email_send_logs テーブルへの登録
    $sql = 'INSERT INTO email_send_logs(ticket_purchase_id, email, purchaser_name, quantity, subject, body, sent_at, created_at, updated_at)
        VALUES(:ticket_purchase_id, :email, :purchaser_name, :quantity, :subject, :body, :sent_at, :created_at, :updated_at);
    ';
    $pre2 = $dbh->prepare($sql);

    // プレースホルダに値をバインドする（※ $pre2 を使用するように修正済み）
    $pre2->bindValue(':ticket_purchase_id', $ticket_purchase_id, PDO::PARAM_INT);
    $pre2->bindValue(':email', $input['email'], PDO::PARAM_STR);
    $pre2->bindValue(':purchaser_name', $input['purchaser_name'], PDO::PARAM_STR);
    $pre2->bindValue(':quantity', $input['quantity'], PDO::PARAM_INT);
    $pre2->bindValue(':subject', $subject, PDO::PARAM_STR);
    $pre2->bindValue(':body', $body, PDO::PARAM_STR);
    $pre2->bindValue(':sent_at', $sent_at, PDO::PARAM_STR);
    
    $now = date(DATE_ATOM);
    $pre2->bindValue(':created_at', $now, PDO::PARAM_STR);
    $pre2->bindValue(':updated_at', $now, PDO::PARAM_STR);

    // 実行する
    $r = $pre2->execute();
    // var_dump($r); exit;

} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}

// 完了ページへのlocation
header('Location: fin_print.php');