<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class TicketPurchases extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('ticket_purchases', [
            'id'          => false,   // デフォルトの id を無効化
            'primary_key' => 'id',    // 自分で PK 定義
            'comment' => 'チケット購入',
        ]);

        $table
            ->addColumn('id', 'biginteger', [
                'signed' => false,
                'identity' => true, // AUTO_INCREMENT
                'comment' => 'チケット購入ID',
            ])
            ->addColumn('email', 'varbinary', [
                'limit' => 254,
                'null' => false,
                'comment' => 'メールアドレス',
            ])
            ->addColumn('purchaser_name', 'string', [
                'limit' => 128,
                'null' => false,
                'comment' => '購入者名',
            ])
            ->addColumn('quantity', 'integer', [
                'signed' => false,
                'null' => false,
                'comment' => '購入枚数',
            ])
            ->addColumn('token', 'varbinary', [
                'limit' => 255,
                'null' => false,
                'comment' => '識別用トークン',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => false,
            ])
            ->addIndex(['email'], [
                'name' => 'idx_ticket_purchases_email',
            ])
            ->addIndex(['token'], [
                'name' => 'idx_ticket_purchases_token',
            ])
            ->create();
    }
}
