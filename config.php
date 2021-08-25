<?php
    ini_set( "display_errors", true );
    date_default_timezone_set( "Europe/Paris" );  // http://www.php.net/manual/en/timezones.php
    // On règle l'affichage de l'heure en Français
    setlocale( LC_ALL, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8' );
    define( "DB_DSN", "mysql:host=localhost:3307;dbname=nsr;" );
    define( "DB_USERNAME", "root" );
    define( "DB_PASSWORD", "root" );
    // On définit le chemin vers les classes
    define( "CLASS_PATH", "classes" );
    // On définit le chemin vers les templates
    define( "TEMPLATE_PATH", "templates" );
    // On définit le chemin vers les images
    define( "UPLOAD_PATH", "upload/" );
    // On définit le nombre d'articles pour une liste réduite
    define( "ARTICLES_LIMIT", 3 );
    // On appelle d'avance les classes à utiliser

    require( CLASS_PATH . "/Main.php" );
    require( CLASS_PATH . "/Article.php" );
    require( CLASS_PATH . "/User.php" );

    function handleException( $exception ) {
        echo "Désolé, un problème est survenu. Veuillez essayer plus tard.";
        error_log( $exception->getMessage() );
    }

    set_exception_handler( 'handleException' );
?>