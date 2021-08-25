<?php

require( "config.php" );

// initialisation de la session
session_start();

$action = isset( $_GET['action'] ) ? $_GET['action'] : "";
$sort = isset( $_GET['sort'] ) ? ( $_GET['sort'] == "id" ? $_GET['sort'] : "post_".$_GET['sort'] ) : "id";
$order = isset( $_GET['order'] ) ? $_GET['order'] : "";
$order = $order != "" ? $order : ( $sort == "id" ? "DESC" : "ASC" );

switch ( $action ) {
  case 'archive':
    archive( $sort, $order );
    break;
  case 'viewArticle':
    viewArticle();
    break;
  default:
    homepage();
}

function archive( $sort, $order ) {
  $results = array();
  $data = Article::getList( 100000, $sort, $order );
  $results['articles'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "Archives";
  require( TEMPLATE_PATH . "/archive.php" );
}

function viewArticle() {
  if ( !isset($_GET["articleId"]) || !$_GET["articleId"] ) {
    homepage();
    return;
  }

  $results = array();
  $results['article'] = Article::getById( (int)$_GET["articleId"] );
  $results['pageTitle'] = $results['article']->post_title;
  require( TEMPLATE_PATH . "/viewArticle.php" );
}

function homepage() {
  $results = array();

  // On liste les articles
  $data = Article::getList( ARTICLES_LIMIT );
  $results['articles'] = $data['results'];

  // on liste les auteurs
  $data = User::getTop( ARTICLES_LIMIT );
  $results['users'] = $data;

  require( TEMPLATE_PATH . "/homepage.php" );
}

?>