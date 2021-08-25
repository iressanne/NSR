<?php include "templates/include/header.php" ?>

  <main class="homepage">

  <?php if ( $main->pin != "" ) { $pinned = Article::getById( $main->pin ) ?>
    
    <article>
      <a href=".?action=viewArticle&amp;articleId=<?php echo $pinned->id?>">
        <figure>
          <?php
            $title = $pinned->post_title;
            $cover = $pinned->post_cover;
            $cover = !strpos( $cover, "http" ) ? UPLOAD_PATH . $cover : $cover;
            if( !empty( $cover ) ) echo "<img src='$cover' alt='$title'>";
          ?>
          <h3><?php echo $title; ?></h3>
          <p><?php echo $pinned->post_heading; ?></p>
        </figure>
      </a>
    </article>

  <?php } ?>

    <div class="homepage__overview">

<?php

 
  foreach ( $results['articles'] as $article ) {

    $id = $article->id;
    $title = $article->post_title;
    $cover = $article->post_cover;
    $cover = !strpos( $cover, "http" ) ? UPLOAD_PATH . $cover : $cover;
    $heading = $article->post_heading;
    $date = $article->post_date;

?>

      <article>
        <a href=".?action=viewArticle&amp;articleId=<?php echo $id?>">
          <figure>
              <?php if( !empty( $cover ) ) echo "<img src='$cover' alt='$title'>"; ?>
          </figure>
        </a>
        <a href=".?action=viewArticle&amp;articleId=<?php echo $id?>">
          <h3><?php echo $title; ?></h3>
        </a>
        <p><?php echo $heading; ?></p>
        <p><small><time datetime="<?php echo date( "m/d/y", $date ); ?>"><?php echo strftime( "%e %B", $date ); ?></time></small></p>
      </article>

<?php } ?>
      
    </div>

    <p class="h6"><a href="./?action=archive">Accéder à d'autres articles</a></p>

    <hr>

    <div class="homepage__overview" id="top">

    <?php foreach ( $results['users'] as $top_user ) { ?>
        
      <div class="card">
        <img src="<?php echo $top_user->user_cover ? UPLOAD_PATH . "users/" . $top_user->user_cover : UPLOAD_PATH . "users/user.png"; ?>" class="card-img-top" alt="<?php echo $top_user->user_username; ?>">
        <div class="card-body">
          <h5 class="card-title"><?php echo $top_user->user_username; ?></h5>
        </div>
      </div>

    <?php } ?>

    </div>

  </main>

<?php include "templates/include/footer.php" ?>