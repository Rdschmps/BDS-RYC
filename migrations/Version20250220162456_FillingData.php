<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250220162456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO user (username, password, email, balance, roles) VALUES 
        ("testuser", "$2y$12$g43SCbLDgdmFjEGqLxpwSuK5FVQrvUcM9B9kinoya4NkhqtSDTrBG", "test@example.com", 100.00, "[\"ROLE_USER\"]"),
        ("testdev", "$2y$12$V7XijqgY5925/INubD9ZDe1UNE6iZ4UZt.HTp3R6e4DiA7yCzCwy6", "dev@example.com", 200.00, "[\"ROLE_USER\"]"),
        ("testadmin", "$2y$12$YpmGO2GLEZsrCIld3/FGWeewdJLKxF13WP8jRJp4wtkvVI5cyDiCe", "admin@example.com", 500.00, "[\"ROLE_ADMIN\"]")
        ');

        $this->addSql("INSERT INTO article (name, description, price, quantity, image_url, published_at) VALUES ('Article 1', 'Description de l\'article 1', 19.99, 10, 'image1.jpg', NOW()),('Article 2', 'Description de l\'article 2', 49.99, 5, 'image2.jpg', NOW())");

        $this->addSql("INSERT INTO cart (items, status, total_price, created_at, user_id) VALUES ('[]', 'pending', 0.00, NOW(), 1)");

        $this->addSql("INSERT INTO invoice (transaction_date, amount, billing_address, billing_city, billing_postal_code, user_id, cart_id) VALUES (NOW(), 69.98, '123 Rue du Test', 'Paris', '75001', 1, 1)");
    }
    

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM invoice WHERE user_id IN (SELECT id FROM user WHERE username IN ("testuser", "testdev", "testadmin"))');

        $this->addSql('DELETE FROM cart WHERE user_id IN (SELECT id FROM user WHERE username IN ("testuser", "testdev", "testadmin"))');
    
        $this->addSql('DELETE FROM article WHERE name IN ("Article 1", "Article 2")');
    
        $this->addSql('DELETE FROM user WHERE username IN ("testuser", "testdev", "testadmin")');
    }
    
}
