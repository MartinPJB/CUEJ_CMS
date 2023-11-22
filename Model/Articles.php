<?php

namespace Model;

use \DateTime;
use \Core\Database\Manager;
use \Model\Categories;

/**
 * Articles model
 */
class Articles
{
  private int $id;
  private string $title;
  private string $description;
  private int $author_id;
  private DateTime $date;
  private string $image;
  private int $category_id;
  private array $tags;
  private bool $is_draft;
  private bool $is_published;

  /**
   * Constructor for the Articles model
   *
   * @param integer $id Article ID
   * @param string $title Article title
   * @param string $description Article description
   * @param integer $author_id Author ID
   * @param string $date Article date
   * @param string $image Article image
   * @param integer $category_id Category ID
   * @param array $tags Article tags
   * @param boolean $is_draft Is the article a draft
   * @param boolean $is_published Is the article published
   */
  public function __construct(
    int $id,
    string $title,
    string $description,
    int $author_id,
    string $date,
    string $image,
    int $category_id,
    array $tags,
    bool $is_draft,
    bool $is_published
  ) {
    $this->id = $id;
    $this->title = $title;
    $this->description = $description;
    $this->author_id = $author_id;
    $this->date = new DateTime($date);
    $this->image = $image;
    $this->category_id = $category_id;
    $this->tags = $tags;
    $this->is_draft = $is_draft;
    $this->is_published = $is_published;
  }

  /**
   * Get all published articles from the database
   *
   * @return array
   */
  public static function getAllPublishedArticles(): array
  {
    $articles = Manager::read('articles', [], ['published' => 1, 'draft' => 0]);
    $result = [];

    foreach ($articles as $key => $article) {
      $result[] = new self(
        $article['id'],
        $article['title'],
        $article['description'],
        $article['author_id'],
        $article['date'],
        $article['image'],
        $article['category_id'],
        explode(",", $article['tags']),
        $article['draft'],
        $article['published']
      );
    }

    return $result;
  }

  /**
   * Get all articles from the database
   *
   * @return array
   */
  public static function getAllArticles(): array
  {
    $articles = Manager::read('articles');
    $result = [];

    foreach ($articles as $key => $article) {
      $result[] = new self(
        $article['id'],
        $article['title'],
        $article['description'],
        $article['author_id'],
        $article['date'],
        $article['image'],
        $article['category_id'],
        explode(",", $article['tags']),
        $article['draft'],
        $article['published']
      );
    }

    return $result;
  }

  /**
   * Get an article from the database by its ID
   *
   * @param integer $id Article ID
   * @return self
   */
  public static function getArticle(int $id): self
  {
    $article = Manager::read('articles', [], ['id' => $id])[0];

    return new self(
      $article['id'],
      $article['title'],
      $article['description'],
      $article['author_id'],
      $article['date'],
      $article['image'],
      $article['category_id'],
      explode(",", $article['tags']),
      $article['draft'],
      $article['published']
    );
  }

  /**
   * Create a new article in the database
   *
   * @param string $title Article title
   * @param string $description Article description
   * @param integer $author_id Author ID
   * @param string $image Article image
   * @param integer $category_id Category ID
   * @param array $tags Article tags
   * @param boolean $is_draft Is the article a draft
   * @param boolean $is_published Is the article published
   * @return void
   */
  public static function createArticle(
    string $title,
    string $description,
    int $author_id,
    string $image,
    int $category_id,
    array $tags,
    bool $is_draft,
    bool $is_published
  ): void {
    Manager::create('articles', [
      'title' => $title,
      'description' => $description,
      'author_id' => $author_id,
      'date' => date("Y-m-d H:i:s"),
      'image' => $image,
      'category_id' => $category_id,
      'tags' => implode(",", $tags),
      'draft' => $is_draft,
      'published' => $is_published
    ]);
  }

  /**
   * Update an article in the database
   *
   * @param integer $id Article ID
   * @param string $title Article title
   * @param string $description Article description
   * @param integer $author_id Author ID
   * @param string $image Article image
   * @param integer $category_id Category ID
   * @param array $tags Article tags
   * @param boolean $is_draft Is the article a draft
   * @param boolean $is_published Is the article published
   * @return void
   */
  public static function updateArticle(
    int $id,
    string $title,
    string $description,
    int $author_id,
    string $image,
    int $category_id,
    array $tags,
    bool $is_draft,
    bool $is_published
  ): void {
    Manager::update('articles', [
      'title' => $title,
      'description' => $description,
      'author_id' => $author_id,
      'date' => date("Y-m-d H:i:s"),
      'image' => $image,
      'category_id' => $category_id,
      'tags' => implode(",", $tags),
      'draft' => $is_draft,
      'published' => $is_published
    ], ['id' => $id]);
  }

  /**
   * Delete an article from the database
   *
   * @param integer $id Article ID
   * @return void
   */
  public static function deleteArticle(int $id): void
  {
    Manager::delete('articles', ['id' => $id]);
  }

  /**
   * Get the value of id
   *
   * @return integer
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * Get the value of title
   *
   * @return string
   */
  public function getTitle(): string
  {
    return $this->title;
  }

  /**
   * Get the value of description
   *
   * @return string
   */
  public function getDescription(): string
  {
    return $this->description;
  }

  /**
   * Get the value of author_id
   *
   * @return integer
   */
  public function getAuthorId(): int
  {
    return $this->author_id;
  }

  /**
   * Get the value of date
   *
   * @return DateTime
   */
  public function getDate(): DateTime
  {
    return $this->date;
  }

  /**
   * Get the value of image
   *
   * @return string
   */
  public function getImage(): string
  {
    return $this->image;
  }

  /**
   * Get the value of category_id
   *
   * @return integer
   */
  public function getCategoryId(): int
  {
    return $this->category_id;
  }

  /**
   * Get the category directly
   */
  public function getCategory(): Categories
  {
    return Categories::getCategoryById($this->category_id);
  }

  /**
   * Get the value of tags
   *
   * @return array
   */
  public function getTags(): array
  {
    return $this->tags;
  }

  /**
   * Get the value of is_draft
   *
   * @return boolean
   */
  public function isDraft(): bool
  {
    return $this->is_draft;
  }

  /**
   * Get the value of is_published
   *
   * @return boolean
   */
  public function isPublished(): bool
  {
    return $this->is_published;
  }
}