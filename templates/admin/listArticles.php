<?php require( TEMPLATE_PATH . "/include/header.php" ); ?>
<?php //session_start(); ?>

    <?php require( TEMPLATE_PATH . "/include/adminHeader.php" ); // On insert le adminHeader ?>

    <h1>Tous les articles</h1>

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>


    <?php if ( isset( $results['statusMessage'] ) ) { ?>
            <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
    <?php } ?>

    <table>
        <tr>
            <th>Date de publication</th>
            <th>Article</th>
        </tr>

<?php foreach ( $results['articles'] as $article ) { ?>

        <tr onclick="location='admin.php?action=editArticle&amp;articleId=<?php echo $article->id?>'">
            <td><?php echo date('j M Y', $article->post_date)?></td>
            <td>
                <?php echo $article->post_title?>
            </td>
        </tr>

<?php } ?>

    </table>

    <p><?php echo $results['totalRows']?> article<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> au total.</p>

    <p><a href="admin.php?action=newArticle">Ajouter un nouvel article</a></p>

<?php require( TEMPLATE_PATH . "/include/footer.php" ); ?>

