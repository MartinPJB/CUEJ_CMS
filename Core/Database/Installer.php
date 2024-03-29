<?php

namespace Core\Database;

use Core\Database\Manager;
use Core\Config;

/**
 * Database installer | Handles all actions related to the database installation
 */
class Installer
{
  /**
   * Prevents direct instantiation of the class
   */
  private function __construct()
  {
  }

  /**
   * Install the CMS by creating the database and all needed tables
   */
  public static function install()
  {
    self::createDatabase();
    self::createTables();
    self::initializeData();
  }

  /**
   * Uninstall the CMS by deleting the database
   */
  public static function uninstall()
  {
    Manager::dropDatabase(Manager::getCurrentDatabaseName());
  }

  /**
   * Create the database in order to use the CMS
   */
  private static function createDatabase()
  {
    Manager::createDatabase(Config::get('database_name'));
    Manager::connectToDatabase(Config::get('database_name'));
  }

  /**
   * Create all needed tables in the database in order to use the CMS
   */
  private static function createTables()
  {
    // Site settings table
    Manager::createTable('site_settings', [
      'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
      'name' => 'VARCHAR(255) NOT NULL',
      'description' => 'TEXT NOT NULL',
      'theme' => 'VARCHAR(255) NOT NULL DEFAULT "default"',
      'site_language' => 'VARCHAR(255) NOT NULL DEFAULT "fr"',
      'default_route' => 'VARCHAR(255) NOT NULL DEFAULT "articles"',
    ]);

    // Users table
    Manager::createTable('users', [
      'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
      'username' => 'VARCHAR(255) NOT NULL',
      'password' => 'VARCHAR(255) NOT NULL',
      'email' => 'VARCHAR(255) NOT NULL',
      'role' => 'VARCHAR(255) NOT NULL',
    ]);

    // Media table
    Manager::createTable('medias', [
      'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
      'name' => 'VARCHAR(255) NOT NULL',
      'type' => 'VARCHAR(255) NOT NULL',
      'size' => 'INT(6) UNSIGNED NOT NULL',
      'path' => 'VARCHAR(255) NOT NULL',
      'alt' => 'VARCHAR(255) NOT NULL',
      'uploaded_at' => 'DATETIME NOT NULL',
      'hash' => 'VARCHAR(255) NOT NULL DEFAULT ""',
    ]);

    // Categories table
    Manager::createTable('categories', [
      'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
      'name' => 'VARCHAR(255) NOT NULL',
      'description' => 'TEXT NOT NULL',
      'image' => 'INT(6) UNSIGNED NULL DEFAULT NULL',
    ], [
      'FOREIGN KEY (image) REFERENCES medias (id) ON DELETE SET NULL'
    ]);

    // Articles table
    Manager::createTable('articles', [
      'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
      'title' => 'VARCHAR(255) NOT NULL',
      'description' => 'TEXT NOT NULL',
      'author_id' => 'INT(6) UNSIGNED NULL',
      'date' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP',
      'image' => 'INT(6) UNSIGNED NULL DEFAULT NULL',
      'category_id' => 'INT(6) UNSIGNED NULL',
      'tags' => 'VARCHAR(255) NOT NULL DEFAULT ""',
      'draft' => 'BOOLEAN NOT NULL DEFAULT TRUE',
      'published' => 'BOOLEAN NOT NULL DEFAULT FALSE',
    ], [
      'FOREIGN KEY (author_id) REFERENCES users (id) ON DELETE SET NULL',
      'FOREIGN KEY (image) REFERENCES medias (id) ON DELETE SET NULL',
      'FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL'
    ]);

    // Blocks table
    Manager::createTable('blocks', [
      'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
      'name' => 'VARCHAR(255) NOT NULL',
      'json_content' => 'JSON NOT NULL',
      'article_id' => 'INT(6) UNSIGNED NULL',
      'type' => 'VARCHAR(255) NOT NULL',
      'weight' => 'INT(6) UNSIGNED NULL',
      'media' => 'INT(6) UNSIGNED NULL DEFAULT NULL',
    ], [
      'FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE SET NULL',
      'FOREIGN KEY (media) REFERENCES medias (id) ON DELETE SET NULL'
    ]);
  }

  /**
   * Sets up all the needed data in the database in order to use the CMS
   */
  private static function initializeData()
  {
    // Site settings
    Manager::create('site_settings', [
      'id' => 1, // The ID is always 1, there can only be one row in the table 'site_settings
      'name' => Config::get('site_default_name'),
      'description' => Config::get('site_default_description'),
      'site_language' => Config::get('site_language'),
      'default_route' => Config::get('site_default_route'),
    ]);

    // Admin user
    Manager::create('users', [
      'username' => Config::get('admin_name'),
      'password' => Config::get('admin_password'),
      'email' => Config::get('admin_email'),
      'role' => 'admin',
    ]);

    // Media
    Manager::create('medias', [
      'name' => 'Default',
      'type' => 'image/jpeg',
      'size' => 0,
      'path' => 'https://picsum.photos/seed/' . rand() . '/400/250',
      'alt' => 'Default image',
      'uploaded_at' => date('Y-m-d H:i:s'),
    ]);

    // Categories
    Manager::create('categories', [
      'name' => 'Uncategorized',
      'description' => 'This is the default category. You can edit it or delete it.',
      'image' => 1,
    ]);

    // Articles
    Manager::create('articles', [
      'title' => 'Welcome to BLOG_CMS',
      'description' => 'This is your first article. You can edit it or delete it.',
      'author_id' => 1,
      'date' => date('Y-m-d H:i:s'),
      'image' => 1,
      'category_id' => 1,
      'tags' => 'welcome, first, article',
      'draft' => false,
      'published' => true,
    ]);
  }
}
