<?php

/** 
 * On crée une classe pour le site
 * https://www.w3schools.com/php/keyword_class.asp
 */

class Main {
  
    // On définie les propriétés

    /**
     * @var int L'id du site
    */
    public $id = null;

    /**
     * @var string Le titre complet du site
    */
    public $title = null;

    /**
     * @var string La description du site
    */
    public $desc = null;

    /**
     * @var string Le contenu du about du site
    */
    public $content = null;

    /**
     * @var string Le logo du site
    */
    public $logo = null;

    /**
     * @var string La photo de couverture du site
    */
    public $cover = null;

    /**
     * @var string Les tags du site
    */
    public $tags = null;

    /**
     * @var string L'article pin sur le site
    */
    public $pin = null;

    /**
     * @var string Le thème du site
    */
    public $theme = null;


    /**
     * On définit les propriétés de l'objet en utilisant les valeurs du tableau fourni.
    *
    * @param assoc Les valeurs de la propriété
    */

    public function __construct( $data = array() ) {

        // On liste chacune des valeurs en fonction de si elle existe
        if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
        if ( isset( $data['main_title'] ) ) $this->title = $data['main_title'];
        if ( isset( $data['main_desc'] ) ) $this->desc = $data['main_desc'];
        if ( isset( $data['main_content'] ) ) $this->content = $data['main_content'];
        if ( isset( $data['main_logo'] ) ) $this->logo = $data['main_logo'];
        if ( isset( $data['main_cover'] ) ) $this->cover = $data['main_cover'];
        if ( isset( $data['main_tags'] ) ) $this->tags = $data['main_tags'];
        if ( isset( $data['main_pin'] ) ) $this->pin = $data['main_pin'];
        if ( isset( $data['main_theme'] ) ) $this->theme = $data['main_theme'];

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
     * Une fonction pour retourner un objet Article correspondant à l'ID du site donné.
    *
    * @param int L'ID du site
    * @return Main|false Retourne l'objet du site, ou un FALSE s'il n'a pas été trouvé ou s'il y a eu un problème.
    */

    public static function getInformations() {

        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );

        $sql = "SELECT * FROM informations WHERE id = 1";
        $st = $conn->prepare( $sql );
        $st->execute();

        $row = $st->fetch();
        if ( $row ) return new Main( $row );

        $conn = null;

    }

    /**
     * Une fonction pour mettre à jour l'objet Main actuel dans la base de données.
     */

    public function update() {
    
        // Connexion à la base de données
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );

        // On met à jour le Main via une requète SQL avec une condition si jamais l'image de couverture est inchangée
        $sql = "UPDATE informations SET main_title=:main_title, main_desc=:main_desc, main_content=:main_content, main_logo=:main_logo, main_cover=:main_cover, main_tags=:main_tags, main_theme=:main_theme WHERE id = 1";
        
        // On prépare la requète SQL
        $st = $conn->prepare ( $sql );

        // On ajoute les valeurs
        $st->bindValue( ":main_title", $this->title, PDO::PARAM_STR );
        $st->bindValue( ":main_desc", $this->desc, PDO::PARAM_STR );
        $st->bindValue( ":main_content", $this->content, PDO::PARAM_STR );

        // On vérifie si le logo a été mis en ligne
        if( isset( $_FILES[ "main_logo" ] ) && $_FILES[ "main_logo" ][ "error" ] == 0 && !isset( $_POST[ "main_logo" ] ) ) {

            $allowed = array( "jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png" );
            $filename = $_FILES[ "main_logo" ][ "name" ];
            $filetype = $_FILES[ "main_logo" ][ "type" ];
            $filesize = $_FILES[ "main_logo" ][ "size" ];
        
            // On vérifie l'extention du fichier
            $ext = pathinfo( $filename, PATHINFO_EXTENSION );
            if( !array_key_exists( $ext, $allowed ) ) die( "Error: Please select a valid file format." );
        
            // On vérifie la taille de l'image
            $maxsize = 5 * 1024 * 1024;
            if( $filesize > $maxsize ) die( "Error: le fichier dépasse la taille limite." );
        
            // On vérifie le MIME type
            if( in_array( $filetype, $allowed ) ){
                
                if( file_exists( "upload/" . $filename ) ) {
                    echo $filename . " existe déjà.";
                } else {
                    move_uploaded_file($_FILES["main_logo"]["tmp_name"], "upload/" . $filename);
                    echo "Votre image à bien été envoyée.";
                }

            } else {

                echo "Error: L'envoie de l'image rencontre un problème.";

            }

            $img_url = trim( $filename );
            $st->bindParam( ':main_logo', $img_url, PDO::PARAM_STR );

        } else {

            $st->bindValue( ":main_logo", $this->logo, PDO::PARAM_STR );

        }

        // On vérifie si la cover a été mis en ligne
        if( isset( $_FILES[ "main_cover" ] ) && $_FILES[ "main_cover" ][ "error" ] == 0 && !isset( $_POST[ "main_cover" ] ) ) {

            $allowed = array( "jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png" );
            $filename = $_FILES[ "main_cover" ][ "name" ];
            $filetype = $_FILES[ "main_cover" ][ "type" ];
            $filesize = $_FILES[ "main_cover" ][ "size" ];
        
            // On vérifie l'extention du fichier
            $ext = pathinfo( $filename, PATHINFO_EXTENSION );
            if( !array_key_exists( $ext, $allowed ) ) die( "Error: Please select a valid file format." );
        
            // On vérifie la taille de l'image
            $maxsize = 5 * 1024 * 1024;
            if( $filesize > $maxsize ) die( "Error: le fichier dépasse la taille limite." );
        
            // On vérifie le MIME type
            if( in_array( $filetype, $allowed ) ){
                
                if( file_exists( "upload/" . $filename ) ) {
                    echo $filename . " existe déjà.";
                } else {
                    move_uploaded_file($_FILES["main_cover"]["tmp_name"], "upload/" . $filename);
                    echo "Votre image à bien été envoyée.";
                }

            } else {

                echo "Error: L'envoie de l'image rencontre un problème.";

            }

            $img_url = trim( $filename );
            $st->bindParam( ':main_cover', $img_url, PDO::PARAM_STR );

        } else {

            $st->bindValue( ":main_cover", $this->cover, PDO::PARAM_STR );

        }

        $st->bindValue( ":main_tags", $this->tags, PDO::PARAM_STR );
        $st->bindValue( ":main_theme", $this->theme, PDO::PARAM_STR );

        $st->execute();
        $conn = null;

    }

    /**
     * Une fonction pour mettre en avant un article sur le site.
     */

    public function pin( $articleId ) {

        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );

        // On met à jour le Main via une requète SQL avec une condition si jamais l'image de couverture est inchangée
        $sql = "UPDATE informations SET main_pin=:main_pin WHERE id = 1";

        $st = $conn->prepare( $sql );

        $st->bindValue( ":main_pin", $articleId, PDO::PARAM_STR );

        $st->execute();
        $conn = null;

    }

}
