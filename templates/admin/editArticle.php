<?php require( TEMPLATE_PATH . "/include/header.php" ); ?>

    <?php require( TEMPLATE_PATH . "/include/adminHeader.php"); // On insert le adminHeader ?>

      <h1><?php echo $results['pageTitle']?></h1>

      <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
        <input type="hidden" name="articleId" value="<?php echo $results['article']->id ?>"/>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>

        <ul>

          <li>
            <label for="post_title">Article Title</label>
            <input type="text" name="post_title" id="post_title" placeholder="Name of the article" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['article']->post_title )?>" />
          </li>

          <li>
            <label for="post_heading">Article Summary</label>
            <textarea name="post_heading" id="post_heading" placeholder="Brief description of the article" required maxlength="1000" style="height: 5em;"><?php echo htmlspecialchars( $results['article']->post_heading )?></textarea>
          </li>

          <li>
            <label for="post_content">Article Content</label>
            <textarea name="post_content" id="post_content" placeholder="The HTML content of the article" required maxlength="100000" style="height: 30em;"><?php echo htmlspecialchars( $results['article']->post_content )?></textarea>
          </li>

          <li>
            <label for="post_date">Publication Date</label>
            <input type="date" name="post_date" id="post_date" placeholder="YYYY-MM-DD" required maxlength="10" value="<?php echo $results['article']->post_date ? date( "Y-m-d", $results['article']->post_date ) : "" ?>" />
          </li>


        </ul>

        <div class="buttons">
          <input type="submit" name="saveChanges" value="Sauvegarder" />
          <input type="submit" formnovalidate name="cancel" value="Annuler" />
        </div>

      </form>

<?php if ( $results['article']->id ) { ?>
      <p><a href="admin.php?action=deleteArticle&amp;articleId=<?php echo $results['article']->id ?>" onclick="return confirm('Souhaitez-vous réellement supprimer cet article ?')">Supprimer cet article</a></p>
<?php } ?>

<?php require( TEMPLATE_PATH . "/include/footer.php" ); ?>