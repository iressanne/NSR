<?php require( TEMPLATE_PATH . "/include/header.php" );

    // On récupère les variables pour conditionner le tri des articles
    $sort = isset( $_GET['sort'] ) ? $_GET['sort'] : "id";
    // On règle la flèche de tri en fonction de la variable $order
    // &nbsp; équivaut à un espace fine insécable (pour éviter les retours à la ligne de petits caractères)
    // &#9650; et &#9660; correspondent aux flèches de classement
    // Liste des entitées de caractères https://unilist.raphaelbastide.com/
    $orderArrow = $order == "ASC" ? "&nbsp;&#9650;" : "&nbsp;&#9660;";

?>

    <main class="archive">

        <h2><?php echo $results['pageTitle'] ?></h2>

        <p><small class="text-muted"><?php echo $results['totalRows']?> article<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> au total</small></p>

        <table class="table table-hover">
            <thead>
                <tr>
                <th scope="col">&nbsp;</th>
                <th scope="col">
                    <a href="./index.php?action=archive&sort=id&order=<?php echo $sort == "id" && $order == "ASC" ? "DESC" : "ASC" ?>">
                        Date<?php if( $sort == "id" ) echo $orderArrow ?>
                    </a>
                </th>
                <th scope="col">
                    <a href="./index.php?action=archive&sort=title&order=<?php echo $sort == "title" && $order == "ASC" ? "DESC" : "ASC" ?>">
                        Article<?php if( $sort == "title" ) echo $orderArrow ?>
                    </a>
                </th>
                <th scope="col">&nbsp;</th>
                <th scope="col">
                    <a href="./index.php?action=archive&sort=author&order=<?php echo $sort == "author" && $order == "ASC" ? "DESC" : "ASC" ?>">
                        Auteur<?php if( $sort == "author" ) echo $orderArrow ?>
                    </a>
                </th>
                </tr>
            </thead>
            <tbody>

<?php

    $i = 0;

    foreach ( $results['articles'] as $article ) {
        
        $i++; // À chaque tour de boucle on ajoute 1 à $i

        $id = $article->id;
        $title = $article->post_title;
        $heading = $article->post_heading;
        $date = $article->post_date;
        $author = $article->post_author;

?>


                <tr>
                    <th scope="row"><?php echo $i; ?></th>
                    <td><time datetime="<?php echo $date; ?>"><?php echo date('j F', $date)?></time></td>
                    <td><a href=".?action=viewArticle&amp;articleId=<?php echo $id?>"><?php echo $title; ?></a></td>
                    <td><?php echo $heading; ?></td>
                    <td><?php echo $author; ?></td>
                </tr>

<?php } ?>
            </tbody>
        </table>

        <p><a href="./">Retourner à la page d'accueil</a></p>

    </main>

<?php require( TEMPLATE_PATH . "/include/footer.php" ); ?>