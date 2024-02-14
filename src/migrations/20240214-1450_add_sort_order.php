<?php

use app\base\Migration;

class AddSortOrderMigration extends Migration
{
    public function run(): void
    {
        $query = 'ALTER TABLE list_contact  ADD COLUMN sort_order integer NOT NULL DEFAULT 0';
        $this->pdo->query($query);
    }
}


