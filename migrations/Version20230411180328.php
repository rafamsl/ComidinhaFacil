<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230411180328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_ingredient ALTER amount TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE weekly_ingredient DROP CONSTRAINT fk_3244bd9c1205968');
        $this->addSql('ALTER TABLE weekly_ingredient DROP CONSTRAINT fk_3244bd9c3caf64a');
        $this->addSql('DROP INDEX idx_3244bd9c3caf64a');
        $this->addSql('DROP INDEX idx_3244bd9c1205968');
        $this->addSql('ALTER TABLE weekly_ingredient ADD ingredient_id INT NOT NULL');
        $this->addSql('ALTER TABLE weekly_ingredient ADD recipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE weekly_ingredient ADD amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE weekly_ingredient DROP weekly_recipe_id');
        $this->addSql('ALTER TABLE weekly_ingredient DROP recipe_ingredient_id');
        $this->addSql('ALTER TABLE weekly_ingredient ADD CONSTRAINT FK_3244BD9C933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE weekly_ingredient ADD CONSTRAINT FK_3244BD9C59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3244BD9C933FE08C ON weekly_ingredient (ingredient_id)');
        $this->addSql('CREATE INDEX IDX_3244BD9C59D8A214 ON weekly_ingredient (recipe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE recipe_ingredient ALTER amount TYPE INT');
        $this->addSql('ALTER TABLE weekly_ingredient DROP CONSTRAINT FK_3244BD9C933FE08C');
        $this->addSql('ALTER TABLE weekly_ingredient DROP CONSTRAINT FK_3244BD9C59D8A214');
        $this->addSql('DROP INDEX IDX_3244BD9C933FE08C');
        $this->addSql('DROP INDEX IDX_3244BD9C59D8A214');
        $this->addSql('ALTER TABLE weekly_ingredient ADD weekly_recipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE weekly_ingredient ADD recipe_ingredient_id INT NOT NULL');
        $this->addSql('ALTER TABLE weekly_ingredient DROP ingredient_id');
        $this->addSql('ALTER TABLE weekly_ingredient DROP recipe_id');
        $this->addSql('ALTER TABLE weekly_ingredient DROP amount');
        $this->addSql('ALTER TABLE weekly_ingredient ADD CONSTRAINT fk_3244bd9c1205968 FOREIGN KEY (weekly_recipe_id) REFERENCES weekly_recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE weekly_ingredient ADD CONSTRAINT fk_3244bd9c3caf64a FOREIGN KEY (recipe_ingredient_id) REFERENCES recipe_ingredient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_3244bd9c3caf64a ON weekly_ingredient (recipe_ingredient_id)');
        $this->addSql('CREATE INDEX idx_3244bd9c1205968 ON weekly_ingredient (weekly_recipe_id)');
    }
}
