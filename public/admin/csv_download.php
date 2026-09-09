<?php

declare(strict_types=1);

// 共通処理の読み込み
require_once __DIR__ . '/../initialize.php';

// 認可チェック
require_once BASEPATH . '/public/admin/authorization.php';

// echo "csv_download.php";

// 必要な情報（配列の配列）を作る
$dbh = getDbh();
// var_dump($dbh);

require_once __DIR__ . '/../../app/Models/TicketPurchase.php';
// [memo]書式がPHP 8.5以降なので注意
$list = new TicketPurchase($dbh)->getList();
// var_dump($list);

/* CSVダウンロードをさせる */
$fn = 'TicketPurchase.' . date('YmdHis'). '.csv';

header('Content-type: text/csv');
header("Content-Disposition: attachment; filename={$fn}.csv");

// 出力用のストリームを開く
$fp = fopen('php://output', 'w');
if (false === $fp) {
    // XXX
    echo "fopneでなんかエラー";
    exit;
}
// 書き込む（文字コード変換はいったんオミット）
$keys = array_keys($list[0]);
fputcsv($fp, $keys, escape:'');

foreach ($list as $datum) {
    // 文字コードをSJISに変換する
    $datum_s = mb_convert_encoding($datum, 'SJIS-win', 'UTF-8');

    $r = fputcsv($fp, $datum_s, escape:'');
    if (false === $fp) {
        // XXX
        echo 'fputcsvでなんかエラー';
        exit;
    }
}
// ストリームを閉じる
fclose($fp);