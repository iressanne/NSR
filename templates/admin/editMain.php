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
        <label for="main_title" class="form-label">Titre</label>
        <input type="text" name="main_title" class="form-control" id="main_title" placeholder="Titre du site" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $main->title )?>" />

        <label for="main_desc" class="form-label">Description</label>
        <textarea name="main_desc" class="form-control" id="main_desc" placeholder="Description brève du site" required maxlength="1000" style="height: 10em;"><?php echo htmlspecialchars( $main->desc )?></textarea>

        <label for="main_theme" class="form-label">Thème du site</label>
        <select name="main_theme" class="form-select" id="main_theme" aria-label="Thème du site">
            <option value="1"<?php if( $main->theme == 1 ) echo " selected" ?>>Thème bleu</option>
            <option value="2"<?php if( $main->theme == 2 ) echo " selected" ?>>Thème rouge</option>
        </select>

        <label for="main_tags" class="form-label">Tags</label>
        <textarea name="main_tags" class="form-control" id="main_tags" placeholder="Veuillez séparer les tags du site par des virgules" required maxlength="1000" style="height: 10em;"><?php echo htmlspecialchars( $main->tags )?></textarea>
      </div>

      <div>
        <label for="main_content" class="form-label">Contenu</label>
        <textarea name="main_content" class="form-control" id="main_content"><?php if( $content = $main->content ) echo htmlspecialchars( $content ) ?></textarea>
      </div>

      <div>
        <label for="main_logo" class="form-label">Logo du site</label>
        <div>
            <input type="file" name="main_logo" class="form-control form-file" id="main_logo" value="<?php if( $logo = $main->logo ) echo htmlspecialchars( $logo ) ?>" required onchange="imgPreview(this)">
            <img id="logo_preview" class="hidden" src="#" alt="...">
            <p><small><strong>Note:</strong> Uniquement des .jpg, .jpeg, .gif ou .png d'une taille max de 5 MB.</small></p>
        </div>
        <label for="main_cover" class="form-label">Photo de couverture</label>
        <div>
            <input type="file" name="main_cover" class="form-control form-file" id="main_cover" value="<?php if( $cover = $main->cover ) echo htmlspecialchars( $main->cover ) ?>" required onchange="imgPreview(this)">
            <img id="cover_preview" class="hidden" src="#" alt="...">
            <p><small><strong>Note:</strong> Uniquement des .jpg, .jpeg, .gif ou .png d'une taille max de 5 MB.</small></p>
        </div>
      </div>

      <div>

        <button type="submit" name="saveChanges" class="btn btn-primary btn-sm">Sauvegarder</button>
        <button type="submit" formnovalidate name="cancel" class="btn btn-secondary btn-sm">Annuler</button>

      </div>

    </form>

    <template>
      <input type="text" name="" class="form-control" id="" value="" disabled>
      <img src="#" alt="...">
      <p><button class="btn btn-secondary btn-sm" onclick="newFile(this)">Télécharger une nouvelle image</button></p>
    </template>

    <template>
      <input type="file" name="" class="form-control" id="" required onchange="imgPreview(this)">
      <img id="cover_preview" class="hidden" src="#" alt="...">
      <p><small><strong>Note:</strong> Uniquement des .jpg, .jpeg, .gif ou .png d'une taille max de 5 MB.</small></p>
    </template>

    <script>

        // On va chercher l'input .form-file
        const fileSelects = document.querySelectorAll( '.form-file' );

        // On vient faire un for pour faire une boucle avec tous les input file
        for ( let i = 0; i < fileSelects.length; i++ ) {

            const fileSelect = fileSelects[i];

            // On va chercher la valeur par défaut de l'input
            const fileValue = fileSelect.defaultValue;
            // On sélection l'élément parent
            const parentDiv = fileSelect.parentElement;
            // On sélectionne l'élément précédent pour définir les .classes et #id
            const label = parentDiv.previousElementSibling;
            const labelFor = label.htmlFor;

            // On vérifie si fileValue n'est pas null ou vide
            if ( fileValue !== null && fileValue !== "" ) {

                // On va chercher le tag template n°1
                const template = document.getElementsByTagName( "template" )[ 0 ];
                // On vient cloner le contenu du template
                const clone = template.content.cloneNode( true );
                // On vide le contenu du parent de l'input
                parentDiv.innerHTML = "";
                // On vient insérer dans le parent le contenu du template
                parentDiv.appendChild( clone );

                const newInput = parentDiv.querySelector( "input" );
                newInput.defaulValue = fileValue;
                newInput.value = fileValue;
                newInput.id = labelFor;
                newInput.name = labelFor;

                const img = parentDiv.querySelector('img');
                img.src = fileValue.indexOf( "http" ) == -1 ? "<?php echo UPLOAD_PATH ?>" + fileValue : fileValue;
                img.alt = fileValue;

            }
            
        }

        // On créer une fonction pour aller chercher une nouvelle image via un input file
        const newFile = function( element ) {

            // On sélection l'élément parent x2 pour accéder à la div
            const parentDiv = element.parentElement.parentElement;
            // On sélectionne l'élément précédent pour définir les .classes et #id
            const label = parentDiv.previousElementSibling;
            const labelFor = label.htmlFor;
            // On va chercher le tag template n°2
            const template = document.getElementsByTagName( "template" )[ 1 ];
            // On vient cloner le contenu du template
            const clone = template.content.cloneNode( true );
            // On vide le contenu du parent de l'input
            parentDiv.innerHTML = "";
            // On vient insérer dans le parent le contenu du template
            parentDiv.appendChild( clone );

            const newInput = parentDiv.querySelector( "input" );
            newInput.id = labelFor;
            newInput.name = labelFor;

        }

        const imgPreview = function( element ) {

            // On déclare l'image de preview
            const imgPreview = element.nextElementSibling;
            // On vient checher la nouvelle image insérée dans l'input
            const img = element.files[ 0 ];

            // Si l'image existe alors...
            if ( img ) {

                // On vient déclarer un nouveau FileReader() (Classe javascript)
                const fileReader = new FileReader();

                // Avec le FileReader on vient ouvrir l'image
                fileReader.readAsDataURL( img );

                // Si l'ouverture de l'image à fonctionné alors on lance une nouvelle fonction
                fileReader.addEventListener( "load", function () {

                // On remplace la source de l'image par le résultat
                imgPreview.src = this.result;
                // On vient retirer la class .hidden à l'image
                imgPreview.classList.remove( "hidden" );

                });

            }

        }
      </script>

  </aside>

  <?php require( TEMPLATE_PATH . "/include/adminHeader.php"); // On insert le adminHeader ?>

</main>

<?php require( TEMPLATE_PATH . "/include/footer.php" ); ?>