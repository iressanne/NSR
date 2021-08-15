<?php include "templates/include/header.php" ?>

  <main>

<?php

 
  foreach ( $results['articles'] as $article ) {

    $id = $article->id;
    $title = $article->post_title;
    $cover = $article->post_cover;
    $cover = !strpos( $cover, "http" ) ? "http://localhost:8888/Blog/upload/$cover" : $cover;
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
        <h1><?php echo $title; ?></h1>
      </a>
        <p><?php echo $heading; ?></p>
        <time datetime="<?php echo $date; ?>"><?php echo date('j F', $date)?></time>
    </article>

<?php } ?>

    <p><a href="./?action=archive">Archives</a></p>

  </main>

<?php include "templates/include/footer.php" ?>