<?php

use \Core\Routing\Router;
use \Controller\ArticlesController;
use \Controller\UsersController;
use \Controller\PublicController;
use \Controller\AdminController;
use \Controller\AdminSubControllers\AdminArticlesController;
use \Controller\AdminSubControllers\AdminMediasController;

/*
  Usage:
  Router::addRoute('route', 'action', Controller::class, accessLevel, method);
*/

/* -- Articles routes -- */
// GET
Router::addRoute('articles', '', '\Controller\ArticlesController', 0, 'GET');
Router::addRoute('articles', 'all', '\Controller\ArticlesController', 0, 'GET');
Router::addRoute('articles', 'see', '\Controller\ArticlesController', 0, 'GET');

/* -- Users routes -- */
// GET
Router::addRoute('users', 'see', '\Controller\UsersController', 0, 'GET');
Router::addRoute('users', 'login', '\Controller\UsersController', 0, 'GET');
Router::addRoute('users', 'logout', '\Controller\UsersController', 1, 'GET');

// POST
Router::addRoute('users', 'process_login', '\Controller\UsersController', 0, 'POST');

/* -- Public routes -- */
// GET
Router::addRoute('public', 'front', '\Controller\PublicController', 0, 'GET');
Router::addRoute('public', 'back', '\Controller\PublicController', 0, 'GET');

/* -- Admin routes -- */
Router::addRoute('admin', '', '\Controller\AdminController', 2, 'GET');

/* -- Admin Articles -- */
// GET
Router::addRoute('admin', 'articles', '\Controller\AdminSubControllers\AdminArticlesController', 2, 'GET');

// POST
Router::addRoute('admin', 'create_article', '\Controller\AdminSubControllers\AdminArticlesController', 2, 'POST');
Router::addRoute('admin', 'edit_article', '\Controller\AdminSubControllers\AdminArticlesController', 2, 'POST');
Router::addRoute('admin', 'delete_article', '\Controller\AdminSubControllers\AdminArticlesController', 2, 'POST');

/* -- Admin Medias -- */
// GET
// Router::addRoute('admin', 'medias', '\Controller\AdminSubControllers\AdminArticlesController', 2, 'GET');

// POST
Router::addRoute('admin', 'delete_media', '\Controller\AdminSubControllers\AdminMediasController', 2, 'GET');
