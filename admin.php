<main>

<?php

// Connexion à la base de données
require( "config.php" );
session_start();

// Définit les ISSET
$action = isset( $_GET['action'] ) ? $_GET['action'] : "";
$username = isset( $_SESSION['user_username'] ) ? $_SESSION['user_username'] : "";

// Renvoie vers la page login dans le cas ou l'utilisateur est déconnecté
if ( $action != "login" && $action != "logout" && $action != "register" && !$username ) {

    login();
    exit;

}


switch ( $action ) {
    case 'login':
        login();
        break;
    case 'logout':
        logout();
        break;
    case 'register':
        register();
        break;
    case 'newArticle':
        newArticle();
        break;
    case 'editArticle':
        editArticle();
        break;
    case 'deleteArticle':
        deleteArticle();
        break;
    default:
        listArticles();
}

// Fonction d'encodage de mot de passe
function stringEncryption( $action, $string ) {

    $output = false;
    
    $encrypt_method = 'AES-256-CBC';
    $secret_key = 'Rendu#Cours_Salim!';
    $secret_iv = '!Init@_Vector$2';
    
    // hash
    $key = hash( 'sha256', $secret_key );
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
    
    if( $action == 'encrypt' ) {
        $output = openssl_encrypt( $string, $encrypt_method, $key, 0, $iv );
        $output = base64_encode( $output );
    }
    else if( $action == 'decrypt' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }
    
    return $output;

}


function login() {

    $results = array();
    $results['pageTitle'] = "Page de connexion";

    if ( isset( $_POST['login'] ) ) {

        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        
        // Check si username est vide
        $post_user = trim( $_POST[ "user_username" ] );
        if( empty( $post_user ) ) {
            $username_err = "Veuillez entrer votre nom.";
        } else{
            $username = $post_user;
        }
        
        // Check si password est vide
        $post_password = trim( $_POST[ "user_password" ] );
        if( empty( $post_password ) ) {
            $password_err = "Veuillez entrer votre mot de passe.";
        } else{
            $password = $post_password;
        }
            
        // On valide le login
        if( empty( $username_err ) && empty( $password_err ) ) {
            // On prépare une selection SQL
            $sql = "SELECT id, user_username, user_password FROM users WHERE user_username = :user_username";
                
            if( $stmt = $conn->prepare( $sql ) ) {

                // Lier des variables à l'instruction préparée en tant que paramètres
                $stmt->bindParam( ":user_username", $param_username, PDO::PARAM_STR );
                
                // On règle les paramètres
                $param_username = $username;
                
                // On tente d'exécuter l'instruction qui a été préparée
                if( $stmt->execute() ) {

                    // On check si le username existe, si oui on testera le mot de passe
                    if( $stmt->rowCount() == 1 ) {

                        if( $row = $stmt->fetch() ) {

                            $id = $row[ "id" ];
                            $username = $row[ "user_username" ];
                            var_dump($username);

                            $decrypted = stringEncryption( 'decrypt', $row[ "user_password" ] );
                            $decrypted = password_hash( trim( $decrypted ), PASSWORD_DEFAULT );

                            if( password_verify( $password, $decrypted ) ) {

                                // Password est bon, maintenant on démarre une nouvelle SESSION 
                                // https://www.php.net/manual/fr/reserved.variables.session.php
                                // session_start();
                                
                                // On stock les données dans les variables de session
                                $_SESSION[ "loggedin" ] = true;
                                $_SESSION[ "id" ] = $id;
                                $_SESSION[ "user_username" ] = $username;
                                
                                // Et on redirige l'utilisateur vers sa welcome page
                                // (Créer une page welcome.php)
                                header( "location: admin.php" );

                            } else {

                                // Ici on renvoie un message d'erreur dans le cas ou le mot de passe n'est pas valide
                                $login_err = "Invalid password.";

                            }
                        }
                    } else {

                        // Ici on renvoie un message d'erreur dans le cas ou le username n'est pas valide
                        $login_err = "Invalid username.";
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // On ferme l'exécution
                unset( $stmt );
            }
        }

        unset( $conn );

    } else {

        // L'utilisateur n'a pas encore rempli le formulaire de connexion, alors on affiche le formulaire
        require( TEMPLATE_PATH . "/admin/loginForm.php" );

    }

}

// fonction de déconnexion
function logout() {

    unset( $_SESSION['loggedin'] );
    unset( $_SESSION['id'] );
    unset( $_SESSION['user_username'] );
    header( "Location: admin.php" );

}

// Fonction d'enregistrement
function register() {

    $results = array();
    $results['pageTitle'] = "Page de connexion";

    if ( isset( $_POST['register'] ) ) {

        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        
        // Execution de la requete SQL du formulaire register.php
        try {
            // On va chercher les infos du formulaire
            $sql = "INSERT INTO users (user_first_name, user_last_name, user_username, user_password, user_email, user_role) VALUES (:user_first_name, :user_last_name, :user_username, :user_password, :user_email, :user_role)";
            $stmt = $conn->prepare( $sql );
            
            // On joint les paramêtres aux infos
            $stmt->bindParam( ':user_first_name', $_REQUEST[ 'user_first_name' ] );
            $stmt->bindParam( ':user_last_name', $_REQUEST[ 'user_last_name' ] );
            $stmt->bindParam( ':user_username', $_REQUEST[ 'user_username' ] );
    
            // On appelle le script de cryptage pour le mot de passe
            $encrypted = stringEncryption( 'encrypt', $_REQUEST[ 'user_password' ] );
            $stmt->bindParam( ':user_password', $encrypted );
    
            $stmt->bindParam( ':user_email', $_REQUEST[ 'user_email' ] );
    
            // On définit par défaut le rôle des nouveaux inscrits en tant qu'utilisateur
            $stmt->bindParam( ':user_role', 'user' );
            
            // On execute l'enregistrement
            $stmt->execute();
    
            // Redirection vers admin.php
            header( "location: admin.php" );
            exit;
    
        } catch( PDOException $e ) {
            die( "ERROR: Could not able to execute $sql. " . $e->getMessage() );
        }
        
        // Fermeture de la connexion
        unset( $conn );

    } else {

        // L'utilisateur n'a pas encore rempli le formulaire d'enregistrement, alors on affiche le formulaire
        include( TEMPLATE_PATH . "/admin/registerForm.php" );

    }
}


function newArticle() {

    $results = array();
    $results['pageTitle'] = "New Article";
    $results['formAction'] = "newArticle";

    if ( isset( $_POST['saveChanges'] ) ) {

        // Création d'un nouvel article
        $article = new Article;
        $article->storeFormValues( $_POST );
        $article->insert();
        header( "Location: admin.php?status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // Si l'utilisateur annule, alors redirection vers admin.php
        header( "Location: admin.php" );
        
    } else {

        $results['article'] = new Article;
        require( TEMPLATE_PATH . "/admin/editArticle.php" );

    }

}


function editArticle() {

    $results = array();
    $results['pageTitle'] = "Edit Article";
    $results['formAction'] = "editArticle";

    if ( isset( $_POST['saveChanges'] ) ) {

        // Redirection vers article non trouvé si article inexistant
        if ( !$article = Article::getById( (int)$_POST['articleId'] ) ) {
            header( "Location: admin.php?error=articleNotFound" );
            return;
        }

        // Mise à jour de l'article
        $article->storeFormValues( $_POST );
        $article->update();
        header( "Location: admin.php?status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // Si l'utilisateur annule, alors redirection vers admin.php
        header( "Location: admin.php" );
    } else {

        // Contrairement à newArticle, on vient afficher le formulaire contenant les éléments de l'article sélectionné
        $results['article'] = Article::getById( (int)$_GET['articleId'] );
        require( TEMPLATE_PATH . "/admin/editArticle.php" );
    }

}


function deleteArticle() {

    if ( !$article = Article::getById( (int)$_GET['articleId'] ) ) {
        header( "Location: admin.php?error=articleNotFound" );
        return;
    }

    $article->delete();
    header( "Location: admin.php?status=articleDeleted" );

}


function listArticles() {
    $results = array();
    $data = Article::getList();
    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $results['pageTitle'] = "All Articles";

    if ( isset( $_GET['error'] ) ) {
        if ( $_GET['error'] == "articleNotFound" ) $results['errorMessage'] = "Error: Article non trouvé.";
    }

    if ( isset( $_GET['status'] ) ) {
        if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Vos modifications ont bien été enregistrées.";
        if ( $_GET['status'] == "articleDeleted" ) $results['statusMessage'] = "Article supprimé.";
    }

    require( TEMPLATE_PATH . "/admin/listArticles.php" );
}

?>

</main>