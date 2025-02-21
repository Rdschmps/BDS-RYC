<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250221013603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO user (username, password, email, balance, profile_picture, roles) VALUES 
        ("testuser", "$2y$12$g43SCbLDgdmFjEGqLxpwSuK5FVQrvUcM9B9kinoya4NkhqtSDTrBG", "test@example.com", 100.00, "urlpicture", "[\"ROLE_USER\"]"),
        ("testdev", "$2y$12$V7XijqgY5925/INubD9ZDe1UNE6iZ4UZt.HTp3R6e4DiA7yCzCwy6", "dev@example.com", 200.00, "urlpicture", "[\"ROLE_USER\"]"),
        ("testadmin", "$2y$12$YpmGO2GLEZsrCIld3/FGWeewdJLKxF13WP8jRJp4wtkvVI5cyDiCe", "admin@example.com", 500.00, "urlpicture", "[\"ROLE_ADMIN\"]")
        ');

        $this->addSql('INSERT INTO cart (user_id, items, status, total_price, created_at) VALUES 
        (1, \'{"1": 2, "2": 1}\', "validated", 149.99, NOW()),
        (2, \'{"3": 1, "4": 3}\', "validated", 249.99, NOW()),
        (3, \'{"2": 1, "5": 2}\', "pending", 99.99, NOW())
        ');

        $this->addSql('INSERT INTO invoice (transaction_date, amount, billing_address, billing_city, billing_postal_code, user_id, cart_id) VALUES 
        (NOW(), 149.99, "10 rue du Test", "Paris", "75001", 1, 1),
        (NOW(), 249.99, "25 avenue Dev", "Lyon", "69003", 2, 2)
        ');



    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
