<?php require( TEMPLATE_PATH . "/include/header.php" ); ?>


        <main>
            <form action="admin.php?action=register" method="post">
                <input type="hidden" name="register" value="true" />
                <div class="mb-3">
                    <label for="user_first" class="form-label">Prénom:</label>
                    <input type="text" name="user_first_name" class="form-control" id="user_first">
                </div>
                <div class="mb-3">
                    <label for="user_last" class="form-label">Nom de famille:</label>
                    <input type="text" name="user_last_name" class="form-control" id="user_last">
                </div>
                <div class="mb-3">
                    <label for="user_username" class="form-label">Nom d'utilisateur:</label>
                    <input type="text" name="user_username" class="form-control" id="user_username">
                </div>
                <div class="mb-3">
                    <label for="user_password" class="form-label">Mot de passe:</label>
                    <input type="password" name="user_password" class="form-control" id="user_password">
                </div>
                <div class="mb-3">
                    <label for="user_password_secure" class="form-label">Validez le mot de passe:</label>
                    <input type="password" name="user_password" class="form-control" id="user_password">
                </div>
                <div class="mb-3">
                    <label for="user_email" class="form-label">Adresse mail:</label>
                    <input type="email" name="user_email" class="form-control" id="user_email">
                </div>
                <button type="submit" class="btn btn-primary">S'enregister</button>
            </form>
        </main>

<?php require( TEMPLATE_PATH . "/include/footer.php" ); ?>
