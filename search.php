<?php 

    // On initialise la session
    session_start();

    require( "config.php" );
    require( TEMPLATE_PATH . "/include/header.php" );

    $button = $_GET[ 'submit' ];
    $search = $_GET[ 'search' ];

    // Connexion à la base de données
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    
    // On fait une requète SQL qui comprend un résulté de recherche pour plusieurs éléments d'articles
    $sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(post_date) AS post_date FROM posts
        WHERE post_title LIKE :search
        OR post_author LIKE :search
        OR post_cover LIKE :search
        OR post_heading LIKE :search
        OR post_content LIKE :search
        ORDER BY id DESC";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":search", "%$search%" );
    $st->execute();

    // Maintenant on trouve le nombre d'objets Articles qui correspondent aux critères
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    
?>
<main>

    <aside>

        <h2>Résultat de recherche pour <b><?php echo $search ?></b></h2>

<?php

    $results = $st->fetchAll();

    if ( $results ) {

?>

    <p><small class="text-muted"><?php echo $totalRows[ 0 ] ?> article<?php echo ( $totalRows[ 0 ] > 0 ) ? 's' : '' ?> au total</small></p>

    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Article</th>
                <th scope="col">Description</th>
                <th scope="col">Auteur</th>
            </tr>
        </thead>

<?php

        foreach( $results as $row ) {

?>


            <tr onclick="location='index.php?action=viewArticle&articleId=<?php echo $row['id']; ?>'">
                <td><?php echo date( 'j M Y', $row[ 'post_date' ] ); ?></td>
                <td><?php echo $row[ 'post_title' ]; ?></td>
                <td><?php echo $row[ 'post_heading' ]; ?></td>
                <td><?php echo $row[ 'post_author' ]; ?></td>
            </tr>     

<?php } ?>

    </table>
    
<?php

    } else {

        echo "Désolé, nous sommes dans l'incapacité de trouver des articles correspondant à '<b>$search</b>'.";

    }

?>

</aside>

    <?php $conn = null; ?>

</main>

<?php require( TEMPLATE_PATH . "/include/footer.php" ); ?>
