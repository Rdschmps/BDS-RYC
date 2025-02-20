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
            ("testadmin", "$2y$12$YpmGO2GLEZsrCIld3/FGWeewdJLKxF13WP8jRJp4wtkvVI5cyDiCe", "admin@example.com", 500.00, "[\"ROLE_ADMIN\"]");
        ');
    

        // Ajout d'articles de test
        $this->addSql("INSERT INTO article (author_id, name, description, price, published_at, image_url) VALUES 
            (1, 'Bonnet', 'Bonnet en tricot épais, alliant douceur et chaleur pour un confort optimal. Son design soigné, orné du logo BDS brodé, en fait un accessoire incontournable pour l’hiver.', 9.99, NOW(), 'Bonnet.png'),
            (1, 'Casquette', 'Casquette ajustable en coton premium, légère et respirante. Sa broderie précise du logo BDS assure un rendu élégant et durable, parfait pour un look sportif et moderne.', 14.99, NOW(), 'Cap-1.png'),
            (1, 'Tasse', 'Tasse en céramique robuste, dotée d\'une impression haute qualité du logo BDS. Son revêtement résistant assure une longue durée de vie, idéale pour vos pauses café ou thé.', 9.99, NOW(), 'Cup-1.png'),
            (1, 'Gourde-Alu', 'Gourde en aluminium légère et ultra-résistante, conçue pour maintenir vos boissons fraîches ou chaudes plus longtemps. Son design élégant et son logo BDS gravé en font un accessoire pratique et stylé.', 19.99, NOW(), 'Gourds-alu-1.png'),
            (1, 'Gourde-Sport', 'Gourde de sport en plastique sans BPA, ergonomique et facile à transporter. Son bouchon anti-fuite et sa prise en main optimisée garantissent une hydratation efficace lors de vos entraînements.', 19.99, NOW(), 'Gourds-sport-1.png'),
            (1, 'Hoodie', 'Sweat à capuche confectionné dans un mélange de coton doux et résistant. Son intérieur molletonné apporte une sensation de chaleur et de confort, tandis que son design épuré aux couleurs du BDS assure un style unique.', 29.99, NOW(), 'Hoodie-men.png'),
            (1, 'T-shirt', 'T-shirt en tissu technique léger, offrant respirabilité et confort optimal. Son impression durable du logo BDS garantit un look dynamique et moderne, adapté aussi bien au sport qu\'au quotidien.', 14.99, NOW(), 'Men T Shirt-1.png'),
            (1, 'Tote Bag', 'Tote bag en coton biologique, robuste et respectueux de l’environnement. Son format pratique et son design élégant avec logo BDS en font l’allié idéal pour transporter vos affaires avec style.', 12.99, NOW(), 'Tote Bag-1.png');
        ");
         // Ajout d'articles de test
         $this->addSql("INSERT INTO stock (article_id, quantity) VALUES 
         (1, 200),
         (2, 20),
         (3, 30),
         (4, 50),
        
         (5, 45),
         (6,  450),
         (7, 2000),
         (8,  450);
     ");


    }
    
    public function down(Schema $schema): void
    {
        // Suppression des données insérées
        $this->addSql("DELETE FROM article WHERE name IN ('Article 1', 'Article 2')");
        $this->addSql("DELETE FROM user WHERE username IN ('testuser', 'testdev', 'testadmin')");
    }
    
}
