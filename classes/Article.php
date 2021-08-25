<?php

/** 
 * On crée une classe pour les articles
 * https://www.w3schools.com/php/keyword_class.asp
 */

class Article {
  
  // On définie les propriétés

  /**
  * @var int L'id de l'article
  */
  public $id = null;

  /**
  * @var int La date de publication de l'article
  */
  public $post_date = null;

  /**
  * @var int L'auteur de publication de l'article
  */
  public $post_author = null;

  /**
  * @var string Le titre complet de l'article
  */
  public $post_title = null;

  /**
  * @var string L'url de la couverture de l'article
  */
  public $post_cover = null;

  /**
  * @var string Le résumé de l'article
  */
  public $post_heading = null;

  /**
  * @var string Le contenu HTML de l'article
  */
  public $post_content = null;


  /**
  * On définit les propriétés de l'objet en utilisant les valeurs du tableau fourni.
  *
  * @param assoc Les valeurs de la propriété
  */

  public function __construct( $data = array() ) {

    // On liste chacune des valeurs en fonction de si elle existe
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['post_date'] ) ) $this->post_date = (int) $data['post_date'];
    if ( isset( $data['post_author'] ) ) $this->post_author = $data['post_author'];
    if ( isset( $data['post_title'] ) ) $this->post_title = $data['post_title'];
    if ( isset( $data['post_cover'] ) ) $this->post_cover = $data['post_cover'];
    if ( isset( $data['post_heading'] ) ) $this->post_heading = $data['post_heading'];
    if ( isset( $data['post_content'] ) ) $this->post_content = $data['post_content'];

  }

  /**
  * On définit les propriétés de l'objet en utilisant les valeurs du formulaire dans le tableau fourni.
  *
  * @param assoc Les valeurs du formulaire
  */

  public function storeFormValues ( $params ) {

    // On stocke tous les paramètres
    $this->__construct( $params );

    // On analyse et stocker la date de publication
    if ( isset( $params['post_date'] ) ) {
      $post_date = explode ( '-', $params['post_date'] );

      if ( count($post_date) == 3 ) {
        list ( $y, $m, $d ) = $post_date;
        $this->post_date = mktime ( 0, 0, 0, $m, $d, $y );
      }
    }
  }


  /**
  * Une fonction pour retourner un objet Article correspondant à l'ID de l'article donné.
  *
  * @param int L'ID de l'article
  * @return Article|false Retourne l'objet de l'article, ou un FALSE s'il n'a pas été trouvé ou s'il y a eu un problème.
  */

  public static function getById( $id ) {

    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT *, UNIX_TIMESTAMP(post_date) AS post_date FROM posts WHERE id = :id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":id", $id, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Article( $row );

  }


  /**
  * Une fonction qui renvoie tous les objets Article (ou une gamme d'objets) dans la base de données.
  *
  * @param int Facultatif - Le nombre de lignes à retourner (par défaut: default=all)
  * @return Array|false Renvoie un array à deux éléments: results => array, la liste d'objets Article; totalRows => Le nombre total d'articles.
  */

  public static function getList( $numRows = 1000000, $sort = "id", $order = "DESC", $author = "" ) {

    // Connexion à la base de données
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    // Sélection SQL des articles en fonction de la LIMIT
    $sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(post_date) AS post_date FROM posts";
    $sql .= $author == "" ? null : " WHERE post_author LIKE '$author'";
    $sql .= " ORDER BY $sort $order LIMIT :numRows";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    // Une boucle while qui crée un objet Article par $row et qui l'ajoute à l'array list
    while ( $row = $st->fetch() ) {
      $article = new Article( $row );
      $list[] = $article;
    }

    // Maintenant on trouve le nombre d'objets Articles qui correspondent aux critères
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
  
    $conn = null;

    // On renvoie un Array avec la liste des Articles et son nombre total
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
  }

  /**
   * Une fonction pour inserer un article à la base de données et on définit son ID
  */

  public function insert( $user_name ) {

    // L'objet a-t'il déjà un ID définit?
    if ( !is_null( $this->id ) ) trigger_error ( "Article::insert(): Vous tentez d'inserer un objet Article dont la propriété ID est déjà définie (à $this->id).", E_USER_ERROR );

    // Connexion à la base de données
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    // On insert l'article en commençant par une requète SQL
    $sql = "INSERT INTO posts ( post_date, post_author, post_title, post_cover, post_heading, post_content ) VALUES ( FROM_UNIXTIME(:post_date), :post_author, :post_title, :post_cover, :post_heading, :post_content )";
    // On prépare la requète SQL
    $st = $conn->prepare ( $sql );
    // On ajoute les valeurs
    $st->bindValue( ":post_date", $this->post_date, PDO::PARAM_INT );
    $st->bindValue( ":post_author", $user_name, PDO::PARAM_STR );
    $st->bindValue( ":post_title", $this->post_title, PDO::PARAM_STR );

    // On vérifie si l'image à été envoyée ou non
    if( isset( $_FILES["post_cover"] ) && $_FILES["post_cover"]["error"] == 0 ) {

      // On définit les formats autorisés
      $allowed = array( "jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png" );

      // On récupère les infos du fichier
      $filename = $_FILES["post_cover"]["name"];
      $filetype = $_FILES["post_cover"]["type"];
      $filesize = $_FILES["post_cover"]["size"];
  
      // On vérifie l'extension
      $ext = pathinfo( $filename, PATHINFO_EXTENSION );
      if( !array_key_exists( $ext, $allowed ) ) die( "Error: Please select a valid file format." );
  
      // On vérifie la taille de l'image (5MB pour 1024 px par 1024px)
      $maxsize = 5 * 1024 * 1024;
      if( $filesize > $maxsize ) die( "Error: File size is larger than the allowed limit." );
  
      // On vérifie le MYME type
      if( in_array( $filetype, $allowed ) ) {

          // Check whether file exists before uploading it
          if( file_exists( "upload/$filename" ) ) {

            echo "$filename is already exists.";

          } else{

            move_uploaded_file( $_FILES["post_cover"]["tmp_name"], "upload/$filename" );
            echo "Votre image à bien été envoyée.";

          } 

      } else{

        echo "Error: There was a problem uploading your file. Please try again."; 
        
      }

      $cover_url = trim( $filename );
      $st->bindParam( ':post_cover', $cover_url, PDO::PARAM_STR );

    } else {

      $cover_url = trim( "default.jpg" );
      $st->bindParam( ':post_cover', $cover_url, PDO::PARAM_STR );

    }

    $st->bindValue( ":post_heading", $this->post_heading, PDO::PARAM_STR );
    $st->bindValue( ":post_content", $this->post_content, PDO::PARAM_STR );
    $st->execute();
    $this->id = $conn->lastInsertId();
    
    $conn = null;
  }

  /**
   * Une fonction pour mettre à jour l'objet Article actuel dans la base de données.
  */

  public function update() {

    // L'objet a-t'il déjà un ID définit?
    if ( is_null( $this->id ) ) trigger_error ( "Article::update(): Vous tentez de mettre à jour un objet Article dont la propriété ID n'a pas été définie.", E_USER_ERROR );
   
    // Connexion à la base de données
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    // On met à jour l'Article via une requète SQL avec une condition si jamais l'image de couverture est inchangée

    $sql = "UPDATE posts SET post_date=FROM_UNIXTIME(:post_date), post_title=:post_title, post_heading=:post_heading, post_content=:post_content WHERE id = :id";

    if ( isset( $_FILES[ "post_cover" ] ) ) {

      $sql = "UPDATE posts SET post_date=FROM_UNIXTIME(:post_date), post_title=:post_title, post_cover=:post_cover, post_heading=:post_heading, post_content=:post_content WHERE id = :id";

    }
    
    // On prépare la requète SQL
    $st = $conn->prepare ( $sql );
    // On ajoute les valeurs
    $st->bindValue( ":post_date", $this->post_date, PDO::PARAM_INT );
    $st->bindValue( ":post_title", $this->post_title, PDO::PARAM_STR );

    // Check if file was uploaded without errors
    if( isset( $_FILES[ "post_cover" ] ) && $_FILES["post_cover"]["error"] == 0 && !isset( $_POST[ "post_cover" ] ) ) {

      $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
      $filename = $_FILES["post_cover"]["name"];
      $filetype = $_FILES["post_cover"]["type"];
      $filesize = $_FILES["post_cover"]["size"];
  
      // Verify file extension
      $ext = pathinfo($filename, PATHINFO_EXTENSION);
      if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
  
      // Verify file size - 5MB maximum
      $maxsize = 5 * 1024 * 1024;
      if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
  
      // Verify MYME type of the file
      if(in_array($filetype, $allowed)){
          // Check whether file exists before uploading it
          if(file_exists("upload/" . $filename)){
              echo $filename . " is already exists.";
          } else{
              move_uploaded_file($_FILES["post_cover"]["tmp_name"], "upload/" . $filename);
              echo "Votre image à bien été envoyée.";
          } 
      } else{
          echo "Error: There was a problem uploading your file. Please try again."; 
      }

      $cover_url = trim( $filename );
      $st->bindParam( ':post_cover', $cover_url, PDO::PARAM_STR );

    }

    $st->bindValue( ":post_heading", $this->post_heading, PDO::PARAM_STR );
    $st->bindValue( ":post_content", $this->post_content, PDO::PARAM_STR );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }

  /**
  * Une fonction pour supprimer un objet Article de la base de données
  */

  public function delete() {

    // L'objet a-t'il déjà un ID définit?
    if ( is_null( $this->id ) ) trigger_error ( "Article::delete(): Vous tentez de supprimer un objet Article dont la propriété ID n'a pas été définie.", E_USER_ERROR );

    // Connexion à la base de données
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    // On supprime l'objet Article via une requète SQL
    $st = $conn->prepare ( "DELETE FROM posts WHERE id = :id LIMIT 1" );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }

}

?>
