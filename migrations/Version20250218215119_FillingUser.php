<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218215119_FillingUser extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Ajout manuelle, mot de passe hashé au préalable avec `php -r "echo password_hash('MDP', PASSWORD_BCRYPT) . PHP_EOL;"`
        $this->addSql('INSERT INTO user (username, password, email, balance, roles) VALUES 
            ("testuser", "$2y$12$g43SCbLDgdmFjEGqLxpwSuK5FVQrvUcM9B9kinoya4NkhqtSDTrBG", "test@example.com", 100.00, "[\"ROLE_USER\"]"),
            ("testdev", "$2y$12$V7XijqgY5925/INubD9ZDe1UNE6iZ4UZt.HTp3R6e4DiA7yCzCwy6", "dev@example.com", 200.00, "[\"ROLE_USER\"]"),
            ("testadmin", "$2y$12$YpmGO2GLEZsrCIld3/FGWeewdJLKxF13WP8jRJp4wtkvVI5cyDiCe", "admin@example.com", 500.00, "[\"ROLE_ADMIN\"]")
        ');
    

        // Ajout d'articles de test
        $this->addSql("INSERT INTO article (author_id, name, description, price, published_at) VALUES 
            (1, 'Article 1', 'Description de l\'article 1', 9.99, NOW()),
            (2, 'Article 2', 'Description de l\'article 2', 19.99, NOW())
        ");
    }
    
    public function down(Schema $schema): void
    {
        // Suppression des données insérées
        $this->addSql("DELETE FROM article WHERE name IN ('Article 1', 'Article 2')");
        $this->addSql("DELETE FROM user WHERE username IN ('testuser', 'testdev', 'testadmin')");
    }
    
}
