<?php require( TEMPLATE_PATH . "/include/header.php" );

    // On récupère les variables pour conditionner le tri des articles
    $sort = isset( $_GET['sort'] ) ? $_GET['sort'] : "id";
    // On règle la flèche de tri en fonction de la variable $order
    $orderArrow = $order == "ASC" ? "&nbsp;&#9650;" : "&nbsp;&#9660;";

?>

    <main class="admin">

    <?php if( $user->user_role == "admin" || $user->user_role == "author" ) { ?>

        <aside class="articles">

            <h2><?php echo $results['pageTitle'] ?></h2>

            <p><small class="text-muted">
            <?php if( $results['totalRows'] > 0 ) { ?>
                <?php echo $results['totalRows'] ?> article<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> au total
            <?php } else { ?>
                Vous n'avez pas encore écrit d'articles
            <?php } ?>
            </small></p>

            <?php if ( isset( $results['errorMessage'] ) ) { ?>
                    <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
            <?php } ?>

            <?php if ( isset( $results['statusMessage'] ) ) { ?>
                    <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
            <?php } ?>

        <?php if( $results['totalRows'] > 0 ) { ?>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">
                            <a href="./admin.php?sort=id&order=<?php echo $sort == "id" && $order == "ASC" ? "DESC" : "ASC" ?>">
                                Date<?php if( $sort == "id" ) echo $orderArrow ?>
                            </a>
                        </th>
                        <th scope="col">
                            <a href="./admin.php?sort=title&order=<?php echo $sort == "title" && $order == "ASC" ? "DESC" : "ASC" ?>">
                                Article<?php if( $sort == "title" ) echo $orderArrow ?>
                            </a>
                        </th>
                        <?php if ( $user->user_role == "admin" ) { ?>
                        <th scope="col">
                            <a href="./admin.php?sort=author&order=<?php echo $sort == "author" && $order == "ASC" ? "DESC" : "ASC" ?>">
                                Auteur<?php if( $sort == "author" ) echo $orderArrow ?>
                            </a>
                        </th>
                        <?php } ?>
                    </tr>
                </thead>

            <?php foreach ( $results['articles'] as $article ) { ?>

                <tr onclick="location='admin.php?action=editArticle&amp;articleId=<?php echo $article->id?>'">
                    <td><?php echo date('j M Y', $article->post_date)?></td>
                    <td><?php echo $article->post_title?></td>
                    <?php if ( $user->user_role == "admin" ) { ?>
                        <td><?php echo $article->post_author?></td>
                    <?php } ?>
                </tr>

            <?php } ?>

            </table>

        <?php } ?>

            <a href="admin.php?action=newArticle" class="btn btn-primary btn-sm">Ajouter un article</a>
            <?php if ( $user->user_role == "admin" ) echo "<a href='admin.php?action=pinArticle' class='btn btn-secondary btn-sm'>Mettre en avant un article</a>"; ?>

        </aside>

    <?php } ?>

<?php if ( $user->user_role == "admin" ) { // Si la personne est Admin, on liste les Users ?>

    <aside>

        <h2>Tous les utilisateurs</h2>

        <p><small class="text-muted"><?php echo $results['totalUsers']?> utilisateur<?php echo ( $results['totalUsers'] != 1 ) ? 's' : '' ?> au total</small></p>

        <?php if ( isset( $results['errorMessage'] ) ) { ?>
                <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
        <?php } ?>


        <?php if ( isset( $results['userStatusMessage'] ) ) { ?>
                <div class="userStatusMessage"><?php echo $results['userStatusMessage'] ?></div>
        <?php } ?>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Role</th>
                </tr>
            </thead>

        <?php foreach ( $results['users'] as $tr ) { ?>

            <tr onclick="location='admin.php?action=editUser&amp;userId=<?php echo $tr->id ?>'"<?php if( $user->user_username == $tr->user_username ) echo ' class="table-primary"' ?>>
                <td><?php echo $tr->user_username ?></td>
                <td><?php echo $tr->user_role == "" ? "user" : $tr->user_role ?></td>
            </tr>

        <?php } ?>

        </table>

    </aside>

<?php } ?>        

<?php require( TEMPLATE_PATH . "/include/adminHeader.php" ); // On insert le adminHeader ?>

    </main>

<?php require( TEMPLATE_PATH . "/include/footer.php" ); ?>

