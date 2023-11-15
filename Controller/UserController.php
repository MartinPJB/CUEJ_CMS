<?php

namespace Controller;

use Controller\ControllerBase;
use Controller\ControllerInterface;
use Model\User;
use Include\TypeEscaper;

class UserController extends ControllerBase implements ControllerInterface
{
  const ACTION_LOGIN = 'login';

  const ACTION_LOGOUT = 'logout';

  const ACTION_REGISTER = 'register';

  private User $model;

  /**
   * @inheritDoc
   */
  public function __construct(array $database_credentials, $twig)
  {
    parent::__construct('user', $twig);
    $this->model = new User($database_credentials);
    $this->initializeSubRoutes();
  }

  /**
   * @inheritDoc
   */
  public function initializeSubRoutes(): void
  {
    // Add GET routes
    $this->addSubRoute(self::ACTION_LOGIN, 'login.html.twig', [$this, 'GET_login'], 'GET', 2);
    $this->addSubRoute(self::ACTION_REGISTER, 'register.html.twig', [$this, 'GET_register'], 'GET', 2);
    $this->addSubRoute(self::ACTION_LOGOUT, null, [$this, 'GET_logout'], 'GET', 1);

    // Add POST routes
    $this->addSubRoute(self::ACTION_LOGIN, 'login.html.twig', [$this, 'POST_login'], 'POST', 2);
    $this->addSubRoute(self::ACTION_REGISTER, 'register.html.twig', [$this, 'POST_register'], 'POST', 2);
  }

  /**
   * @return array Retourne un array contenant rien
   */
  public function GET_login(): array
  {
    return [];
  }

  /**
   * @return array Retourne un array contenant rien
   */
  public function GET_register(): array
  {
    return [];
  }

  /**
   * @return array Retourne un array contenant les balises HTML
   */
  public function GET_logout(): array
  {
    unset($_SESSION['user_id']);
    unset($_SESSION['user']);
    session_destroy();
    $this->redirectToRoute('articles', '');
    return [];
  }

  /**
   * @return array Retourne un array contenant rien
   */
  public function POST_login(): array
  {
    $erreurs = [];

    if (isset($_POST['user_login'])) {
      // On vérifie que les champs sont remplis
      if (!isset($_POST['username']) || empty($_POST['username'])) {
        $erreurs[] = "Le nom d'utilisateur est obligatoire.";
      }

      if (!isset($_POST['password']) || empty($_POST['password'])) {
        $erreurs[] = "Le mot de passe est obligatoire.";
      }

      // On vérifie que l'utilisateur existe
      $user = $this->model->readUser([], [
        'username' => TypeEscaper::escapeString($_POST['username'])
      ])[0];

      if (!empty($user)) {

        // On vérifie que le mot de passe est correct
        if (password_verify($_POST['password'], $user['password'])) {
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['user'] = $user;

          $this->redirectToRoute('articles', ArticlesController::ACTION_LIST);
        } else {
          $erreurs[] = "Le mot de passe entré est incorrect.";
        }

      } else {
        $erreurs[] = "Aucun utilisateur inscrit ne porte ce nom.";
      }
    }

    return array_merge($this->GET_login(), [
      'erreurs' => $erreurs
    ]);
  }

  /**
   * @return array Retourne un array contenant rien
   */
  public function POST_register(): array
  {
    $erreurs = [];

    if(isset($_POST['user_register'])) {
      // On vérifie que les champs sont remplis
      if (!isset($_POST['username']) || empty($_POST['username'])) {
        $erreurs[] = "Le nom d'utilisateur est obligatoire.";
      }

      if (!isset($_POST['email']) || empty($_POST['email'])) {
        $erreurs[] = "L'adresse mail est obligatoire.";
      }

      if (!isset($_POST['password']) || empty($_POST['password'])) {
        $erreurs[] = "Le mot de passe est obligatoire.";
      }

      if (!isset($_POST['validate_password']) || empty($_POST['validate_password'])) {
        $erreurs[] = "La validation du mot de passe est obligatoire.";
      }

      // On vérifie si un utilisateur existe déjà avec ce nom
      $user = $this->model->readUser([], [
        'username' => TypeEscaper::escapeString($_POST['username'])
      ]);

      if (!empty($user)) {
        $erreurs[] = "Un utilisateur existe déjà avec ce nom.";
      }

      // On vérifie si un utilisateur existe déjà avec cette adresse mail (en redéfinissant user)
      $user = $this->model->readUser([], [
        'email' => TypeEscaper::escapeString($_POST['email'])
      ]);

      if (!empty($user)) {
        $erreurs[] = "Un utilisateur existe déjà avec cette adresse mail.";
      }


      // Si aucune erreure, on peut continuer sur le step 2
      if (empty($erreurs)) {
        // On vérifie si les deux mots de passes sont valides
        if ($_POST['password'] !== $_POST['validate_password']) {
          $erreurs[] = "Les mots de passes ne correspondent pas.";
        }

        // On vérifie si l'email passé est un email valide
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
          $erreurs[] = "L'adresse mail entrée n'est pas valide.";
        }

        // Toujours pas d'erreurs, on peut continuer sur le step 3
        if (empty($erreurs)) {
          // On inscrit l'utilisateur dans la base de données et on le connecte
          $user = $this->model->createUser([
            'username' => TypeEscaper::escapeString($_POST['username']),
            'email' => TypeEscaper::escapeString($_POST['email']),
            'password' => password_hash(TypeEscaper::escapeString($_POST['password']), PASSWORD_DEFAULT),
            'role' => 'user'
          ]);

          $_SESSION['user_id'] = $user['id'];
          $_SESSION['user'] = $user;

          $this->redirectToSubroute('login');
        }
      }
    }

    return array_merge($this->GET_register(), [
      'erreurs' => $erreurs
    ]);
  }
}
