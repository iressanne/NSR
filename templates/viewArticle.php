<?php 
    include "templates/include/header.php";

    // On déclare la variable de l'article
    $article = $results['article'];
    $title = $article->post_title;
?>

    <main class="article">

        <?php if ( $cover = $article->post_cover ) { ?>
            
        <figure>
            <?php
                $cover = !strpos( $cover, "http" ) ? UPLOAD_PATH . $cover : $cover;
                echo "<img src='$cover' alt='$title'>";
            ?>
            <h1><?php echo htmlspecialchars( $title )?></h1>
            <div><?php echo htmlspecialchars( $article->post_heading )?></div>
        </figure>

        <?php } else { ?>

        <h1><?php echo htmlspecialchars( $title )?></h1>
        <div style="font-style: italic;"><?php echo htmlspecialchars( $article->post_heading )?></div>

        <?php } ?>


        <div class="paragraph"><?php echo $article->post_content?></div>

        <footer>

            <p>Article publié le <?php echo date('j F Y', $article->post_date)?> par <a href="search.php?search=<?php echo $article->post_author ?>"><?php echo $article->post_author ?></a></p>

            <p><a href="./">Retourner à la page d'accueil</a></p>

        </footer>

    </main>

<?php include "templates/include/footer.php" ?>