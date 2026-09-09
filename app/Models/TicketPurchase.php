<?php

declare(strict_types=1);
class TicketPurchase{
    public function __construct(private PDO $dbh){ 
    }

    public function getList(): array{
        $stmt = $this->dbh->prepare('SELECT * FROM ticket_purchases ORDER BY created_at DESC LIMIT 100');
        $stmt->execute();
        $list = $stmt->fetchAll();

        return $list;
        
    }
}