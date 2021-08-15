<?php include "templates/include/header.php" ?>

    <main class="archive">

        <h1>Archives</h1>

        <ul>

<?php

    foreach ( $results['articles'] as $article ) {

        $id = $article->id;
        $title = $article->post_title;
        $heading = $article->post_heading;
        $date = $article->post_date;

?>

        <li>
            <h2>
                <time datetime="<?php echo $date; ?>"><?php echo date('j F', $date)?></time>
                <a href=".?action=viewArticle&amp;articleId=<?php echo $id?>"><h1><?php echo $title; ?></h1></a>
            </h2>
            <p><?php echo $heading; ?></p>
        </li>

<?php } ?>

        </ul>

        <p><?php echo $results['totalRows']?> article<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> au total.</p>

        <p><a href="./">Retourner à la page d'accueil</a></p>

    </main>

<?php include "templates/include/footer.php" ?>