<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class EmailSendLogs extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('email_send_logs', [
            'id'          => false,         // デフォルトの id を無効化
            'primary_key' => 'id',          // 自分で PK 定義
            'comment'     => 'メール送信履歴',
        ]);

        $table
            ->addColumn('id', 'biginteger', [
                'signed'   => false,
                'identity' => true,                 // AUTO_INCREMENT
                'comment'  => 'メール送信履歴ID',
            ])
            ->addColumn('ticket_purchase_id', 'biginteger', [
                'signed'  => false,
                'null'    => false,
                'comment' => 'チケット購入ID（参照用・FKなし）',
            ])
            ->addColumn('email', 'varbinary', [
                'limit'   => 254,
                'null'    => false,
                'comment' => 'メールアドレス',
            ])
            ->addColumn('purchaser_name', 'string', [
                'limit'   => 128,
                'null'    => false,
                'comment' => '購入者名',
            ])
            ->addColumn('quantity', 'integer', [
                'signed'  => false,
                'null'    => false,
                'comment' => '購入枚数',
            ])
            ->addColumn('subject', 'string', [
                'limit'   => 255,
                'null'    => false,
                'comment' => 'メールtitle',
            ])
            ->addColumn('body', 'text', [
                'null'    => false,
                'comment' => 'メール本文',
            ])
            ->addColumn('sent_at', 'datetime', [
                'null'    => false,
                'comment' => '送信日時',
            ])
            ->addColumn('created_at', 'datetime', ['null' => false])
            ->addColumn('updated_at', 'datetime', ['null' => false])

            ->addIndex(['ticket_purchase_id'], ['name' => 'idx_email_logs_purchase_ref'])
            ->addIndex(['email'], ['name' => 'idx_email_logs_email'])
            ->addIndex(['sent_at'], ['name' => 'idx_email_logs_sent_at'])

            ->create();
    }
}
