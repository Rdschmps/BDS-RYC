<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250220154844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE article (
            id INT AUTO_INCREMENT NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            description LONGTEXT NOT NULL, 
            price NUMERIC(10, 2) DEFAULT NULL, 
            supplierPrice NUMERIC(10, 2) DEFAULT NULL,
            quantity INT NOT NULL, 
            imageUrl VARCHAR(255) DEFAULT NULL, 
            publishedAt DATETIME NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        
        $this->addSql('CREATE TABLE cart (
            id INT AUTO_INCREMENT NOT NULL, 
            items JSON NOT NULL, 
            status VARCHAR(50) NOT NULL, 
            totalPrice NUMERIC(10, 2) DEFAULT NULL, 
            createdAt DATETIME NOT NULL, 
            user_id INT DEFAULT NULL, 
            INDEX IDX_BA388B7A76ED395 (user_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        
        $this->addSql('CREATE TABLE images (
            id INT AUTO_INCREMENT NOT NULL, 
            imageUrl VARCHAR(255) NOT NULL, 
            article_id INT DEFAULT NULL, 
            INDEX IDX_E01FBE6A7294869C (article_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        
        $this->addSql('CREATE TABLE invoice (
            id INT AUTO_INCREMENT NOT NULL, 
            transactionDate DATETIME NOT NULL, 
            amount NUMERIC(10, 2) NOT NULL, 
            billingAddress VARCHAR(255) NOT NULL, 
            billingCity VARCHAR(255) NOT NULL, 
            billingPostalCode VARCHAR(10) NOT NULL, 
            user_id INT DEFAULT NULL, 
            cart_id INT DEFAULT NULL, 
            INDEX IDX_90651744A76ED395 (user_id), 
            UNIQUE INDEX UNIQ_906517441AD5CDBF (cart_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        
        $this->addSql('CREATE TABLE user (
            id INT AUTO_INCREMENT NOT NULL, 
            username VARCHAR(255) NOT NULL, 
            password VARCHAR(255) NOT NULL, 
            email VARCHAR(255) NOT NULL, 
            balance NUMERIC(10, 2) NOT NULL, 
            profilePicture TEXT NOT NULL,
            roles JSON NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        
        $this->addSql('CREATE TABLE messenger_messages (
            id BIGINT AUTO_INCREMENT NOT NULL, 
            body LONGTEXT NOT NULL, 
            headers LONGTEXT NOT NULL, 
            queueName VARCHAR(190) NOT NULL, 
            createdAt DATETIME NOT NULL, 
            availableAt DATETIME NOT NULL, 
            deliveredAt DATETIME DEFAULT NULL, 
            INDEX IDX_75EA56E0FB7336F0 (queueName), 
            INDEX IDX_75EA56E0E3BD61CE (availableAt), 
            INDEX IDX_75EA56E016BA31DB (deliveredAt), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        
        $this->addSql('CREATE TABLE cart_items (
            id INT AUTO_INCREMENT NOT NULL,
            cart_id INT NOT NULL, 
            article_id INT NOT NULL, 
            quantity INT NOT NULL DEFAULT 1, 
            PRIMARY KEY(id), 
            INDEX IDX_CartItems_Cart (cart_id), 
            INDEX IDX_CartItems_Article (article_id), 
            CONSTRAINT FK_CartItems_Cart FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE, 
            CONSTRAINT FK_CartItems_Article FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7A76ED395');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A7294869C');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744A76ED395');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_906517441AD5CDBF');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}