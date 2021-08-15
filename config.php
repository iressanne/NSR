<?php
    ini_set( "display_errors", true );
    date_default_timezone_set( "Europe/Paris" );  // http://www.php.net/manual/en/timezones.php
    define( "DB_DSN", "mysql:host=localhost:3307;dbname=nsr;" );
    define( "DB_USERNAME", "root" );
    define( "DB_PASSWORD", "root" );
    // On définit le chemin vers les classes
    define( "CLASS_PATH", "classes" );
    // On définit le chemin vers les templates
    define( "TEMPLATE_PATH", "templates" );
    // On définit le nombre d'articles pour une liste réduite
    define( "HOMEPAGE_NUM_ARTICLES", 5 );
    define( "ADMIN_USERNAME", "admin" );
    define( "ADMIN_PASSWORD", "mypass" );
    require( CLASS_PATH . "/article.php" );

    function handleException( $exception ) {
        echo "Désolé, un problème est survenu. Veuillez essayer plus tard.";
        error_log( $exception->getMessage() );
    }

    set_exception_handler( 'handleException' );
?>