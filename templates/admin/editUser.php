<?php require( TEMPLATE_PATH . "/include/header.php" ); ?>

<main class="admin">

    <?php 
        // On ajoute une fonction pour savoir si la personne à modifier est nous-même
        function isYou( $user, $results ) {
            return $user->id == $results[ 'user' ]->id;
        }
    ?>

    <aside>

        <h2><?php echo $results['pageTitle'] . " " . $results[ 'user' ]->user_username ?></h2>

        <form action="admin.php?action=<?php echo $results['formAction']?>" enctype="multipart/form-data" method="post">
            <input type="hidden" name="userId" value="<?php echo $results[ 'user' ]->id ?>"/>

        <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
        <?php } ?>

        <?php if ( isYou( $user, $results ) ) { ?>

            <div>
                <label for="user_first_name" class="form-label">Prénom</label>
                <input type="text" name="user_first_name" class="form-control" id="user_first_name" placeholder="Prénom" value="<?php echo htmlspecialchars( $results[ 'user' ]->user_first_name )?>" />
            </div>

            <div>
                <label for="user_last_name" class="form-label">Nom de famille</label>
                <input type="text" name="user_last_name" class="form-control" id="user_last_name" placeholder="Nom de famille" value="<?php echo htmlspecialchars( $results[ 'user' ]->user_last_name )?>" />
            </div>

            <div>
                <label for="user_cover" class="form-label">Photo de couverture</label>
                <input type="file" name="user_cover" class="form-control" id="user_cover" value="<?php !$results['user']->user_cover ? "user.png" : $results['user']->user_cover ?>" onchange="imgPreview(this)">
                <img id="cover_preview" class="hidden" src="#" alt="...">
                <p><small><strong>Note:</strong> Uniquement des .jpg, .jpeg, .gif ou .png d'une taille max de 5 MB.</small></p>
            </div>

        <?php } else { ?>

            <div>
                <label for="user_name" class="form-label">Nom complet</label>
                <input type="text" name="user_name" class="form-control" id="user_name" placeholder="Pseudo de l'utilisateur" disabled value="<?php echo htmlspecialchars( $results[ 'user' ]->user_first_name." ".$results[ 'user' ]->user_last_name )?>" />
            </div>

        <?php }; ?>

            <div>
                <label for="user_role" class="form-label">Rôle</label>
                <select class="form-select" name="user_role" aria-label="Rôle l'utilisater" onchange="selectChange(this)"<?php if ( isYou( $user, $results ) ) echo " disabled" ?>>
                    <option value="admin"<?php if( $results[ 'user' ]->user_role == "admin" ) echo " selected" ?>>Admin</option>
                    <option value="author"<?php if( $results[ 'user' ]->user_role == "author" ) echo " selected" ?>>Auteur</option>
                    <option value="user"<?php if( $results[ 'user' ]->user_role == "user" || $results[ 'user' ]->user_role == "" ) echo " selected" ?>>Utilisateur</option>
                </select>
            </div>

            <div>
                <label for="user_email" class="form-label">Mail</label>
                <input type="text" name="user_email" class="form-control" id="user_email" placeholder="Pseudo de l'utilisateur"<?php if ( !isYou( $user, $results ) ) echo " disabled" ?> value="<?php echo htmlspecialchars( $results[ 'user' ]->user_email )?>" />
            </div>

            <div>

                <button type="submit" name="<?php echo !isYou( $user, $results ) ? "saveChanges" : "saveOwn"; ?>" class="btn btn-primary btn-sm">Sauvegarder</button>
                
                <button type="submit" formnovalidate name="cancel" class="btn btn-secondary btn-sm">Annuler</button>

                <?php if ( $results[ 'user' ]->id && !isYou( $user, $results ) ) { ?>
                    <button type="button" class="btn btn-danger btn-sm">
                    <a href="admin.php?action=deleteUser&amp;userId=<?php echo $results[ 'user' ]->id ?>" onclick="return confirm('Souhaitez-vous réellement supprimer cet utilisateur ?')">Supprimer cet utilisateur</a>
                    </button>
                <?php } ?>

            </div>

        </form>

<?php if ( isYou( $user, $results ) ) { ?>

        <template>
            <label for="user_cover" class="form-label">Photo de couverture</label>
            <input type="text" name="user_cover" class="form-control" id="user_cover" value="<?php echo htmlspecialchars( $results['user']->user_cover ); ?>" required disabled>
            <img src="<?php echo !strpos( $results['user']->user_cover, "http" ) ? UPLOAD_PATH . "users/" . $results['user']->user_cover : $results['user']->user_cover; ?>" alt="<?php echo $results['user']->user_cover; ?>">
            <p><button class="btn btn-secondary btn-sm" onclick="newFile()">Télécharger une nouvelle image</button></p>
        </template>

        <template>
            <label for="user_cover" class="form-label">Photo de couverture</label>
            <input type="file" name="user_cover" class="form-control" id="user_cover" required onchange="imgPreview(this)">
            <img id="cover_preview" class="hidden" src="#" alt="...">
            <p><small><strong>Note:</strong> Uniquement des .jpg, .jpeg, .gif ou .png d'une taille max de 5 MB.</small></p>
        </template>

        <script>

            // On va chercher l'input #user_cover
            let fileSelect = document.querySelector( '#user_cover' );
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
            const newFile = function() {

                // On va chercher le tag template n°2
                let template = document.getElementsByTagName( "template" )[ 1 ];
                // On vient cloner le contenu du template
                let clone = template.content.cloneNode( true );
                // On vide le contenu du parent de l'input
                parentDiv.innerHTML = "";
                // On vient insérer dans le parent le contenu du template
                parentDiv.appendChild( clone );

            }

            const imgPreview = function( element ) {

                // On déclare l'image de preview
                const coverPreview = document.querySelector( "#cover_preview" )
                // On vient checher la nouvelle image insérée dans l'input
                const img = element.files[0];

                // Si l'image existe alors...
                if ( img ) {

                    // On vient déclarer un nouveau FileReader() (Classe javascript)
                    const fileReader = new FileReader();

                    // Avec le FileReader on vient ouvrir l'image
                    fileReader.readAsDataURL( img );

                    // Si l'ouverture de l'image à fonctionné alors on lance une nouvelle fonction
                    fileReader.addEventListener( "load", function () {

                        // On remplace la source de l'image par le résultat
                        coverPreview.src = this.result;
                        // On vient retirer la class .hidden à l'image
                        coverPreview.classList.remove( "hidden" );

                    });

                }

            }
        
        </script>

<?php } ?>

    </aside>

  <?php require( TEMPLATE_PATH . "/include/adminHeader.php"); // On insert le adminHeader ?>

</main>

<?php require( TEMPLATE_PATH . "/include/footer.php" ); ?>