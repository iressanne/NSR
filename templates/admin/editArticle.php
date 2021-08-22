<?php require( TEMPLATE_PATH . "/include/header.php" ); ?>

<main class="admin">

  <aside class="post">

    <h2><?php echo $results['pageTitle']?></h2>

    <form action="admin.php?action=<?php echo $results['formAction']?>" enctype="multipart/form-data" method="post">
      <input type="hidden" name="articleId" value="<?php echo $results['article']->id ?>"/>

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
      <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

      <div>
        <label for="post_title" class="form-label">Titre</label>
        <input type="text" name="post_title" class="form-control" id="post_title" placeholder="Titre de l'article" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['article']->post_title )?>" />

        <label for="post_author" class="form-label">Auteur</label>
        <input type="text" name="post_author" class="form-control" id="post_author" placeholder="Auteur de l'article" required disabled maxlength="255" value="<?php echo $user->user_username?>" />

        <label for="post_heading" class="form-label">Description</label>
        <textarea name="post_heading" class="form-control" id="post_heading" placeholder="Description brève de l'articls" required maxlength="1000" style="height: 5em;"><?php echo htmlspecialchars( $results['article']->post_heading )?></textarea>

        <label for="post_date" class="form-label"<?php if ( $_GET['action'] == "editArticle" && $user->user_role == "author" ) echo " disabled"; ?>>Date de publication</label>
        <input type="date" name="post_date" class="form-control" id="post_date" placeholder="YYYY-MM-DD" required maxlength="10" value="<?php echo $results['article']->post_date ? date( "Y-m-d", $results['article']->post_date ) : date("Y-m-d") ?>" />
      </div>

      <div>
        <label for="post_content" class="form-label">Contenu</label>
        <textarea name="post_content" class="form-control" id="post_content"><?php if( $content = $results['article']->post_content ) echo htmlspecialchars( $content )?></textarea>
      </div>

      <div>
        <label for="post_cover" class="form-label">Photo de couverture</label>
        <input type="file" name="post_cover" class="form-control" id="post_cover" value="<?php echo htmlspecialchars( $results['article']->post_cover )?>">
        <p><small><strong>Note:</strong> Uniquement des .jpg, .jpeg, .gif ou .png d'une taille max de 5 MB.</small></p>
      </div>

      <div>

        <button type="submit" name="saveChanges" class="btn btn-primary btn-sm">Sauvegarder</button>
        <button type="submit" formnovalidate name="cancel" class="btn btn-secondary btn-sm">Annuler</button>

        <?php if ( $results['article']->id && $user->user_role == "admin" ) { ?>
          <button type="button" class="btn btn-danger btn-sm">
            <a href="admin.php?action=deleteArticle&amp;articleId=<?php echo $results['article']->id ?>" onclick="return confirm('Souhaitez-vous réellement supprimer cet article ?')">Supprimer cet article</a>
          </button>
        <?php } ?>

      </div>

    </form>
<?php if ( $_GET['action'] == "editArticle" ) { ?>

    <template>
      <label for="post_cover" class="form-label">Photo de couverture</label>
      <input type="text" name="post_cover" class="form-control" id="post_cover" value="<?php echo htmlspecialchars( $results['article']->post_cover ); ?>" required disabled>
      <img src="<?php echo !strpos( $results['article']->post_cover, "http" ) ? UPLOAD_PATH . $results['article']->post_cover : $results['article']->post_cover; ?>" alt="<?php echo $results['article']->post_cover; ?>">
      <p><button class="btn btn-secondary btn-sm" onclick="newFile()">Télécharger une nouvelle image</button></p>
    </template>

    <template>
      <label for="post_cover" class="form-label">Photo de couverture</label>
      <input type="file" name="post_cover" class="form-control" id="post_cover" required>
      <p><small><strong>Note:</strong> Uniquement des .jpg, .jpeg, .gif ou .png d'une taille max de 5 MB.</small></p>
    </template>

    <script>

    // On va chercher l'input #post_cover
    let fileSelect = document.querySelector( '#post_cover' );
      // On va chercher la valeur par défaut de l'input
      let fileValue = fileSelect.defaultValue;
      // On sélection l'élément parent
      let parentDiv = fileSelect.parentElement;


      // On vérifie si fileValue n'est pas null ou vide
      if ( fileValue !== null || fileValue !== "" ) {

        // On va chercher le tag template n°1
        let template = document.getElementsByTagName( "template" )[ 0 ];
        // On vient cloner le contenu du template
        let clone = template.content.cloneNode( true );
        // On vide le contenu du parent de l'input
        parentDiv.innerHTML = "";
        // On vient insérer dans le parent le contenu du template
        parentDiv.appendChild( clone );

      }

      // On créer une fonction pour aller chercher une nouvelle image via un input file
      let newFile = function() {

        // On va chercher le tag template n°2
        let template = document.getElementsByTagName( "template" )[ 1 ];
        // On vient cloner le contenu du template
        let clone = template.content.cloneNode( true );
        // On vide le contenu du parent de l'input
        parentDiv.innerHTML = "";
        // On vient insérer dans le parent le contenu du template
        parentDiv.appendChild( clone );

      }
    </script>

<?php } ?>

  </aside>

  <?php require( TEMPLATE_PATH . "/include/adminHeader.php"); // On insert le adminHeader ?>

</main>

<?php require( TEMPLATE_PATH . "/include/footer.php" ); ?>