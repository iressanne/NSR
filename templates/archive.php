<?php include "templates/include/header.php";

    // On récupère les variables pour conditionner le tri des articles
    $sort = isset( $_GET['sort'] ) ? $_GET['sort'] : "date";
    // On règle la flèche de tri en fonction de la variable $order
    // &nbsp; équivaut à un espace fine insécable (pour éviter les retours à la ligne de petits caractères)
    // &#9650; et &#9660; correspondent aux flèches de classement
    // Liste des entitées de caractères https://unilist.raphaelbastide.com/
    $orderArrow = $order == "ASC" ? "&nbsp;&#9650;" : "&nbsp;&#9660;";

?>

    <main class="archive">

        <h1>Archives</h1>

        <p><small><?php echo $results['totalRows']?> article<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> au total.</small></p>

        <table class="table table-hover">
            <thead>
                <tr>
                <th scope="col">&nbsp;</th>
                <th scope="col">
                    <a href="./?action=archive&sort=date<?php if( $sort == "date" && $order == "DESC" ) echo "&order=ASC" ?>">
                        Date<?php if( $sort == "date" ) echo $orderArrow ?>
                    </a>
                </th>
                <th scope="col">
                    <a href="./?action=archive&sort=title<?php if( $sort == "title" && $order == "ASC" ) echo "&order=DESC" ?>">
                        Article<?php if( $sort == "title" ) echo $orderArrow ?>
                    </a>
                </th>
                <th scope="col">&nbsp;</th>
                <th scope="col">
                    <a href="./?action=archive&sort=author<?php if( $sort == "author" && $order == "ASC" ) echo "&order=DESC" ?>">
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

<?php include "templates/include/footer.php" ?>