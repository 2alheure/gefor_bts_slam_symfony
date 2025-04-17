<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417084505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE pokemon ADD type1_id INT NOT NULL, ADD type2_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3BFAFA3E1 FOREIGN KEY (type1_id) REFERENCES type (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3AD1A0C0F FOREIGN KEY (type2_id) REFERENCES type (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_62DC90F3BFAFA3E1 ON pokemon (type1_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_62DC90F3AD1A0C0F ON pokemon (type2_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3BFAFA3E1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3AD1A0C0F
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_62DC90F3BFAFA3E1 ON pokemon
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_62DC90F3AD1A0C0F ON pokemon
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pokemon DROP type1_id, DROP type2_id
        SQL);
    }
}
