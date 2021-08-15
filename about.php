<?php 

    require( "config.php" );
    require( TEMPLATE_PATH . "/include/header.php" ); ?>

<main>

    <?php $description_site = $infos_row['description_site']; // AFFICHAGE DU ABOUT

    if( !empty( $description_site ) ) : ?>

    <p><?php echo $description_site; ?></p>

    <?php endif; ?>
    <?php unset( $conn ); ?>

</main>

<?php require( TEMPLATE_PATH . "/include/footer.php" ); ?>
