<?php require( TEMPLATE_PATH . "/include/header.php" ); ?>

    <main>

        <form action="admin.php?action=login" method="post" style="width: 50%;">
            <input type="hidden" name="login" value="true" />

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>

            <div class="mb-3">
                <label for="user_username" class="form-label">Username</label>
                <input type="text" name="user_username" class="form-control" id="user_username" placeholder="Votre nom d'utilisateur" required autofocus maxlength="20" />
            </div>

            <div class="mb-3">
                <label for="user_password" class="form-label">Password</label>
                <input type="password" name="user_password" class="form-control" id="user_password" placeholder="Votre mot de passe" required maxlength="20" />
            </div>

            <!-- <div class="mb-3">
                <input type="submit" name="login" value="Login" />
            </div> -->
            <button type="submit" class="btn btn-primary">Connexion</button>

            <p>Pas encore de compte&#8239;? <a href="./admin.php?action=register">Enregistrez-vous maintenant&#8239;!</a></p>

        </form>

    </main>

<?php require( TEMPLATE_PATH . "/include/footer.php" ); ?>

