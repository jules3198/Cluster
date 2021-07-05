<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210705235109 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activate (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, status INT NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_B66604ADA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE badges (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_78F6539A5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bid (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, professional_id INT DEFAULT NULL, capital DOUBLE PRECISION DEFAULT \'0\' NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, nb_promotion INT NOT NULL, backup_promotion LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_4AF2B3F371F7E88B (event_id), INDEX IDX_4AF2B3F3DB77003 (professional_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversations (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_C2521BF17E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation_user (conversation_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5AECB5559AC0396 (conversation_id), INDEX IDX_5AECB555A76ED395 (user_id), PRIMARY KEY(conversation_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, status VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, nb_participants INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, price DOUBLE PRECISION NOT NULL, number_of_visits INT DEFAULT 0, UNIQUE INDEX UNIQ_3BAE0AA7989D9B62 (slug), INDEX IDX_3BAE0AA7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_restriction (event_id INT NOT NULL, restriction_id INT NOT NULL, INDEX IDX_D110484E71F7E88B (event_id), INDEX IDX_D110484EE6160631 (restriction_id), PRIMARY KEY(event_id, restriction_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_images (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, updated_at DATETIME NOT NULL, image_name VARCHAR(255) DEFAULT NULL, INDEX IDX_D286C93871F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_picture (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, picture_name VARCHAR(255) NOT NULL, picture_size VARCHAR(255) NOT NULL, INDEX IDX_938CE62671F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, conversation_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, message LONGTEXT NOT NULL, INDEX IDX_DB021E969AC0396 (conversation_id), INDEX IDX_DB021E96A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participants (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_7169709271F7E88B (event_id), INDEX IDX_71697092A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, link VARCHAR(255) NOT NULL, uploaded_at DATETIME NOT NULL, INDEX IDX_16DB4F8971F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restriction (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_photo (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, image_name VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_F6757F40A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, siret VARCHAR(14) DEFAULT NULL, siren VARCHAR(9) DEFAULT NULL, rating INT DEFAULT 0 NOT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, exp INT DEFAULT 0 NOT NULL, token_reset VARCHAR(255) DEFAULT NULL, last_activity DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, premium TINYINT(1) DEFAULT \'0\' NOT NULL, premium_end_date DATE DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, active TINYINT(1) NOT NULL, activation_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_friends (user_id INT NOT NULL, friend_id INT NOT NULL, INDEX IDX_79E36E63A76ED395 (user_id), INDEX IDX_79E36E636A5458E8 (friend_id), PRIMARY KEY(user_id, friend_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_badge (user_id INT NOT NULL, badge_id INT NOT NULL, INDEX IDX_1C32B345A76ED395 (user_id), INDEX IDX_1C32B345F7A2C2FC (badge_id), PRIMARY KEY(user_id, badge_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activate ADD CONSTRAINT FK_B66604ADA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F371F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F3DB77003 FOREIGN KEY (professional_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE conversations ADD CONSTRAINT FK_C2521BF17E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE conversation_user ADD CONSTRAINT FK_5AECB5559AC0396 FOREIGN KEY (conversation_id) REFERENCES conversations (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conversation_user ADD CONSTRAINT FK_5AECB555A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE event_restriction ADD CONSTRAINT FK_D110484E71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_restriction ADD CONSTRAINT FK_D110484EE6160631 FOREIGN KEY (restriction_id) REFERENCES restriction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_images ADD CONSTRAINT FK_D286C93871F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_picture ADD CONSTRAINT FK_938CE62671F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E969AC0396 FOREIGN KEY (conversation_id) REFERENCES conversations (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE participants ADD CONSTRAINT FK_7169709271F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE participants ADD CONSTRAINT FK_71697092A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F8971F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_photo ADD CONSTRAINT FK_F6757F40A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_friends ADD CONSTRAINT FK_79E36E63A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_friends ADD CONSTRAINT FK_79E36E636A5458E8 FOREIGN KEY (friend_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_badge ADD CONSTRAINT FK_1C32B345A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_badge ADD CONSTRAINT FK_1C32B345F7A2C2FC FOREIGN KEY (badge_id) REFERENCES badges (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE event_user');
        $this->addSql('DROP TABLE inscription');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_badge DROP FOREIGN KEY FK_1C32B345F7A2C2FC');
        $this->addSql('ALTER TABLE conversation_user DROP FOREIGN KEY FK_5AECB5559AC0396');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E969AC0396');
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F371F7E88B');
        $this->addSql('ALTER TABLE event_restriction DROP FOREIGN KEY FK_D110484E71F7E88B');
        $this->addSql('ALTER TABLE event_images DROP FOREIGN KEY FK_D286C93871F7E88B');
        $this->addSql('ALTER TABLE event_picture DROP FOREIGN KEY FK_938CE62671F7E88B');
        $this->addSql('ALTER TABLE participants DROP FOREIGN KEY FK_7169709271F7E88B');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F8971F7E88B');
        $this->addSql('ALTER TABLE event_restriction DROP FOREIGN KEY FK_D110484EE6160631');
        $this->addSql('ALTER TABLE activate DROP FOREIGN KEY FK_B66604ADA76ED395');
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F3DB77003');
        $this->addSql('ALTER TABLE conversations DROP FOREIGN KEY FK_C2521BF17E3C61F9');
        $this->addSql('ALTER TABLE conversation_user DROP FOREIGN KEY FK_5AECB555A76ED395');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7A76ED395');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96A76ED395');
        $this->addSql('ALTER TABLE participants DROP FOREIGN KEY FK_71697092A76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE user_photo DROP FOREIGN KEY FK_F6757F40A76ED395');
        $this->addSql('ALTER TABLE user_friends DROP FOREIGN KEY FK_79E36E63A76ED395');
        $this->addSql('ALTER TABLE user_friends DROP FOREIGN KEY FK_79E36E636A5458E8');
        $this->addSql('ALTER TABLE user_badge DROP FOREIGN KEY FK_1C32B345A76ED395');
        $this->addSql('CREATE TABLE event_user (event_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_92589AE2A76ED395 (user_id), INDEX IDX_92589AE271F7E88B (event_id), PRIMARY KEY(event_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE inscription (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, event_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_5E90F6D671F7E88B (event_id), INDEX IDX_5E90F6D6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE activate');
        $this->addSql('DROP TABLE badges');
        $this->addSql('DROP TABLE bid');
        $this->addSql('DROP TABLE conversations');
        $this->addSql('DROP TABLE conversation_user');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_restriction');
        $this->addSql('DROP TABLE event_images');
        $this->addSql('DROP TABLE event_picture');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE participants');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE restriction');
        $this->addSql('DROP TABLE user_photo');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE user_friends');
        $this->addSql('DROP TABLE user_badge');
    }
}
