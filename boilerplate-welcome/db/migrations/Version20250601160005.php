<?php

declare(strict_types=1);

namespace Migrations\Welcome;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250601160005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create welcome.quotes table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE welcome.quotes (quote VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, id UUID NOT NULL, PRIMARY KEY(id))
        SQL);

        $this->addSql(<<<'SQL'
INSERT INTO
    welcome.quotes (quote, author, created_at, updated_at, id)
VALUES ('The journey of a thousand miles begins with a single step.', 'Lao Tzu', NOW(), NOW(), '603948a1-bd3e-44ac-a007-7929cc061b38'),
       ('In the middle of every difficulty lies opportunity.', 'Albert Einstein', NOW(), NOW(), '1af29d5d-0294-4467-8add-be5595b0e5da'),
       ('Your time is limited, so don’t waste it living someone else’s life.', 'Steve Jobs', NOW(), NOW(), 'af285ef9-c345-4e2b-81b0-8192a34c4e2e')
SQL
);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP TABLE welcome.quotes
        SQL);
    }
}
