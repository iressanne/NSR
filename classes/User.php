<?php

/** 
 * On crée une classe pour les utilisateurs
 * https://www.w3schools.com/php/keyword_class.asp
 */

class User {
  
  // On définie les propriétés

  /**
  * @var int L'id de l'utilisateur
  */
  public $id = null;

  /**
  * @var int Le prénom de l'utilisateur
  */
  public $user_first_name = null;

  /**
  * @var int Le nom de famille de l'utilisateur
  */
  public $user_last_name = null;

  /**
  * @var string Le pseudo de l'utilisateur
  */
  public $user_username = null;

  /**
  * @var string La photo de couverture de l'utilisateur
  */
  public $user_cover = null;

  /**
  * @var string L'email de l'utilisateur
  */
  public $user_email = null;

  /**
  * @var string Le rôle de l'utilisateur
  */
  public $user_role = null;


  /**
  * On définit les propriétés de l'objet en utilisant les valeurs du tableau fourni.
  *
  * @param assoc Les valeurs de la propriété
  */

  public function __construct( $data = array() ) {

    // On liste chacune des valeurs en fonction de si elle existe
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['user_first_name'] ) ) $this->user_first_name = $data['user_first_name'];
    if ( isset( $data['user_last_name'] ) ) $this->user_last_name = $data['user_last_name'];
    if ( isset( $data['user_username'] ) ) $this->user_username = $data['user_username'];
    if ( isset( $data['user_cover'] ) ) $this->user_cover = $data['user_cover'];
    if ( isset( $data['user_email'] ) ) $this->user_email = $data['user_email'];
    if ( isset( $data['user_role'] ) ) $this->user_role = $data['user_role'];

  }

  /**
  * On définit les propriétés de l'objet en utilisant les valeurs du formulaire dans le tableau fourni.
  *
  * @param assoc Les valeurs du formulaire
  */

  public function storeFormValues ( $params ) {

    // On stocke tous les paramètres
    $this->__construct( $params );

  }


  /**
  * Une fonction pour retourner un objet User correspondant à l'ID de l'utilisateur donné.
  *
  * @param int L'ID de l'utilisateur
  * @return User|false Retourne l'objet de l'utilisateur, ou un FALSE s'il n'a pas été trouvé ou s'il y a eu un problème.
  */
  

  public static function getById( $id ) {

    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM users WHERE id = :id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":id", $id, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new User( $row );

  }


  /**
  * Une fonction pour retourner un objet User correspondant au pseudo de l'utilisateur donné.
  *
  * @param int Le pseudo de l'utilisateur
  * @return User|false Retourne l'objet de l'utilisateur, ou un FALSE s'il n'a pas été trouvé ou s'il y a eu un problème.
  */

  public static function getByUsername( $user_username ) {

    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM users WHERE user_username = :user_username";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":user_username", $user_username, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new User( $row );

  }


  /**
  * Une fonction qui renvoie tous les objets User (ou une gamme d'objets) dans la base de données.
  *
  * @param int Facultatif - Le nombre de lignes à retourner (par défaut: default=all)
  * @return Array|false Renvoie un array à deux éléments: results => array, la liste d'objets User; totalRows => Le nombre total d'utilisateurs.
  */

  public static function getList( $numRows = 1000000, $sort = "id", $order = "ASC" ) {

    // Connexion à la base de données
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    // Sélection SQL des utilisateurs en fonction de la LIMIT
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM users ORDER BY $sort $order LIMIT :numRows";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    // Une boucle while qui crée un objet User par $row et qui l'ajoute à l'array list
    while ( $row = $st->fetch() ) {
      $user = new User( $row );
      $list[] = $user;
    }

    // Maintenant on trouve le nombre d'objets User qui correspondent aux critères
    $sql = "SELECT FOUND_ROWS() AS totalUsers";
    $totalUsers = $conn->query( $sql )->fetch();
  
    $conn = null;

    // On renvoie un Array avec la liste des User et son nombre total
    return ( array ( "users" => $list, "totalUsers" => $totalUsers[0] ) );

  }


  /**
  * Une fonction qui renvoie tous les Users qui ont publié le plus d'articles.
  *
  * @param int Facultatif - Le nombre de lignes à retourner (par défaut: default=all)
  * @return Array|false Renvoie un array à deux éléments: results => array, la liste d'objets User; totalRows => Le nombre total d'utilisateurs.
  */

  public static function getTop( $numRows = 1000000 ) {

    // Connexion à la base de données
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );

    // Sélection SQL des utilisateurs en fonction de la LIMIT
    $sql = "SELECT
        u.id, p.posts
        FROM USERS u
        LEFT JOIN
          (
              SELECT post_author, COUNT(id) AS `posts`
              FROM posts
              GROUP BY post_author
          ) p ON u.id = p.post_author
        ORDER BY p.posts DESC
        LIMIT :numRows";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    // Une boucle while qui crée un objet User par $row et qui l'ajoute à l'array list
    while ( $row = $st->fetch() ) {
        $user = User::getById( $row['id'] );
        $list[] = $user;
    }
  
    $conn = null;

    // On renvoie un Array avec la liste des User et son nombre total
    return $list;
  }

  /**
   * Une fonction pour mettre à jour l'objet User actuel dans la base de données.
   */

  public function update() {

      // L'objet a-t'il déjà un ID définit?
      if ( is_null( $this->id ) ) trigger_error ( "User::update(): Vous tentez de mettre à jour un objet Article dont la propriété ID n'a pas été définie.", E_USER_ERROR );
  
      // Connexion à la base de données
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      // On met à jour le User via une requète SQL
      $sql = "UPDATE users SET user_role = :user_role WHERE id = :id";
      // On prépare la requète SQL
      $st = $conn->prepare ( $sql );
      // On ajoute les valeurs
      $st->bindValue( ":user_role", $this->user_role, PDO::PARAM_STR );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
  }

  /**
   * Une fonction pour mettre à jour l'objet User de la personne connectée.
   */

  public function updateOwn() {

      // L'objet a-t'il déjà un ID définit?
      if ( is_null( $this->id ) ) trigger_error ( "User::update(): Vous tentez de mettre à jour un objet Article dont la propriété ID n'a pas été définie.", E_USER_ERROR );
  
      // Connexion à la base de données
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      // On met à jour le User via une requète SQL
      $sql = "UPDATE users SET user_first_name = :user_first_name, user_last_name = :user_last_name, user_cover = :user_cover, user_email = :user_email WHERE id = :id";
      // On prépare la requète SQL
      $st = $conn->prepare ( $sql );
      // On ajoute les valeurs
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->bindValue( ":user_first_name", $this->user_first_name, PDO::PARAM_STR );
      $st->bindValue( ":user_last_name", $this->user_last_name, PDO::PARAM_STR );
      $st->bindValue( ":user_email", $this->user_email, PDO::PARAM_STR );

      if(isset($_FILES["user_cover"]) && $_FILES["user_cover"]["error"] == 0){
  
          $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
          $filename = $_FILES["user_cover"]["name"];
          $filetype = $_FILES["user_cover"]["type"];
          $filesize = $_FILES["user_cover"]["size"];
      
          $ext = pathinfo($filename, PATHINFO_EXTENSION);
          if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
      
          $maxsize = 5 * 1024 * 1024;
          if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
      
          if(in_array($filetype, $allowed)){
            
              if( file_exists( "upload/users/$filename" ) ) {

                  echo "$filename is already exists.";

              } else{

                  move_uploaded_file($_FILES["user_cover"]["tmp_name"], "upload/users/$filename" );
                  echo "Votre image à bien été envoyée.";

              } 
          } else{
              echo "Error: There was a problem uploading your file. Please try again."; 
          }
  
        $cover_url = trim( $filename );
        $st->bindParam( ':user_cover', $cover_url, PDO::PARAM_STR );
  
      } else{
  
        echo "Error: " . $_FILES["user_cover"]["error"];
  
      }
      
      $st->execute();
      $conn = null;

  }

}

?>
