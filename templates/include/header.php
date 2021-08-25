<?php

    $main = Main::getInformations();
    $logo = !strpos( $main->logo, "http" ) ? UPLOAD_PATH . $main->logo : $main->logo;
    $title = $main->title;

    include "function/main.php";

    // On récupère l'objet User si l'id existe
    $user = isset( $_SESSION['id'] ) ? User::getById( $_SESSION['id'] ) : "";

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if( !empty( $title ) ) echo $title; ?></title>
    <link rel="icon" href="<?php echo $logo ?>" />

    <meta name='keywords' content='<?php

// AFFICHAGE DES TAGS
$tags = $main->tags; // "blog, sport, batman"

if( !empty( $tags ) ) {

    $exploded = explode( ', ', $tags ); // On sépare chaque tags pour les mettre dans un Array (["blog", "sport", "batman"])
    $countTags = count( $exploded ); // On fait le compte des tags (3)

    $i = 0;

    foreach ( $exploded as $tag ) {

        echo $tag;
        if( ++$i !== $countTags ) echo ", ";

    }

}

?>' />

<!-- Appel de Boostrap via CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
 
<!-- Appel de la feuille du style du site -->
<link rel="stylesheet" href="assets/css/style.css">

<!-- On appelle la bibliothèque de l'éditeur tinymce -->
<script src='assets/js/tinymce/tinymce.min.js'></script>

</head>
<body>

<?php if( $cover = $main->cover ) { ?>
<figure id="background__cover">
    <img src="<?php echo UPLOAD_PATH . $cover; ?>" alt="<?php echo $title; ?> background">
</figure>
<?php } ?>

<header>

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <div>
                    <?php // AFFICHAGE DU LOGO

                        if( !empty( $logo ) ): ?>

                        <img src="<?php echo $logo; ?>" alt="<?php echo !empty( $title ) ? $title : null ?>">
                         <?php endif; ?>
                    <a class="navbar-brand" href="index.php"><?php echo !empty( $title ) ? $title : "NSR"; ?></a>
                </div>

                <div id="burger__menu">
                    <img src="<?php echo UPLOAD_PATH . "burger.png"; ?>" alt="bouton menu" onclick="menu()">
                </div>

                <div id="menu" class="hidden">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="about.php">À propos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=archive">Archives</a>
                        </li>
                    </ul>
                    <form class="d-flex" method="get" action="search.php">
                        <input type="text" class="form-control me-2" placeholder="..." name="search" aria-label="Search" required>
                        <input type="submit" class="btn btn-outline-success" value="Recherche" name="submit"></input>
                    </form>
                </div>

                </div>
            </nav>

            <script>
                const menu = function() {
                    // On déclare l'élément avec l'id #menu
                    const menu = document.querySelector( "#menu" );
                    // On intervertit sa classe .hidden
                    menu.classList.toggle( 'hidden' );
                }
            </script>

        </header>
    