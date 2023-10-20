<?php

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

return new class
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('users');
        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true]);
        $table->addColumn('name', Types::STRING, ['length' => 255]);
        $table->addColumn('email', Types::STRING, ['length' => 255]);
        $table->addColumn('password', Types::STRING, ['length' => 60]);
        $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(['id']);
    }
};
