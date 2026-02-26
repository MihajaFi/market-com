<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260225224813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, color VARCHAR(7) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE merchant_item (id INT AUTO_INCREMENT NOT NULL, id_merchant INT NOT NULL, id_order INT NOT NULL, INDEX IDX_5E18EA152470C974 (id_merchant), INDEX IDX_5E18EA151BACD2A8 (id_order), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE merchants (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(20) NOT NULL, city LONGTEXT NOT NULL, status LONGTEXT NOT NULL, join_date DATETIME NOT NULL, avatar_color LONGTEXT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, unit_price DOUBLE PRECISION NOT NULL, id_product INT NOT NULL, id_order INT NOT NULL, INDEX IDX_52EA1F09DD7ADDD (id_product), INDEX IDX_52EA1F091BACD2A8 (id_order), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, order_date DATE NOT NULL, total_amount DOUBLE PRECISION NOT NULL, status VARCHAR(25) NOT NULL, address LONGTEXT NOT NULL, phone LONGTEXT NOT NULL, payment_method LONGTEXT NOT NULL, id_user INT NOT NULL, INDEX IDX_E52FFDEE6B3CA4B (id_user), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, image VARCHAR(255) DEFAULT NULL, id_merchant INT NOT NULL, category_id INT NOT NULL, INDEX IDX_D34A04AD2470C974 (id_merchant), INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE product_item (id INT AUTO_INCREMENT NOT NULL, id_product INT NOT NULL, id_promotion INT NOT NULL, INDEX IDX_92F307BFDD7ADDD (id_product), INDEX IDX_92F307BF4128C1F6 (id_promotion), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(20) NOT NULL, status VARCHAR(20) NOT NULL, id_promotion_loyalty INT NOT NULL, INDEX IDX_C11D7DD1D58D8FBB (id_promotion_loyalty), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE promotion_loyalty (id INT AUTO_INCREMENT NOT NULL, promotion_type VARCHAR(255) DEFAULT NULL, value DOUBLE PRECISION DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, conditions VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE sell (id INT AUTO_INCREMENT NOT NULL, total_sales NUMERIC(10, 2) NOT NULL, id_merchant INT NOT NULL, id_product INT NOT NULL, INDEX IDX_9B9ED07D2470C974 (id_merchant), INDEX IDX_9B9ED07DDD7ADDD (id_product), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, alert VARCHAR(250) NOT NULL, id_product INT NOT NULL, INDEX IDX_4B365660DD7ADDD (id_product), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE merchant_item ADD CONSTRAINT FK_5E18EA152470C974 FOREIGN KEY (id_merchant) REFERENCES merchants (id)');
        $this->addSql('ALTER TABLE merchant_item ADD CONSTRAINT FK_5E18EA151BACD2A8 FOREIGN KEY (id_order) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09DD7ADDD FOREIGN KEY (id_product) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F091BACD2A8 FOREIGN KEY (id_order) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD2470C974 FOREIGN KEY (id_merchant) REFERENCES merchants (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product_item ADD CONSTRAINT FK_92F307BFDD7ADDD FOREIGN KEY (id_product) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_item ADD CONSTRAINT FK_92F307BF4128C1F6 FOREIGN KEY (id_promotion) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD1D58D8FBB FOREIGN KEY (id_promotion_loyalty) REFERENCES promotion_loyalty (id)');
        $this->addSql('ALTER TABLE sell ADD CONSTRAINT FK_9B9ED07D2470C974 FOREIGN KEY (id_merchant) REFERENCES merchants (id)');
        $this->addSql('ALTER TABLE sell ADD CONSTRAINT FK_9B9ED07DDD7ADDD FOREIGN KEY (id_product) REFERENCES product (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660DD7ADDD FOREIGN KEY (id_product) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE merchant_item DROP FOREIGN KEY FK_5E18EA152470C974');
        $this->addSql('ALTER TABLE merchant_item DROP FOREIGN KEY FK_5E18EA151BACD2A8');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09DD7ADDD');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F091BACD2A8');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE6B3CA4B');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD2470C974');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product_item DROP FOREIGN KEY FK_92F307BFDD7ADDD');
        $this->addSql('ALTER TABLE product_item DROP FOREIGN KEY FK_92F307BF4128C1F6');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD1D58D8FBB');
        $this->addSql('ALTER TABLE sell DROP FOREIGN KEY FK_9B9ED07D2470C974');
        $this->addSql('ALTER TABLE sell DROP FOREIGN KEY FK_9B9ED07DDD7ADDD');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660DD7ADDD');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE merchant_item');
        $this->addSql('DROP TABLE merchants');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_item');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE promotion_loyalty');
        $this->addSql('DROP TABLE sell');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE user');
    }
}
