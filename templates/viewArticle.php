<?php include "templates/include/header.php" ?>

    <main>

        <h1><?php echo htmlspecialchars( $results['article']->post_title )?></h1>

        <div style="font-style: italic;"><?php echo htmlspecialchars( $results['article']->post_heading )?></div>

        <div><?php echo $results['article']->post_content?></div>

        <p>Publié le <?php echo date('j F Y', $results['article']->post_date)?></p>

        <p><a href="./">Retourner à la page d'accueil</a></p>

    </main>

<?php include "templates/include/footer.php" ?>