<?php require( TEMPLATE_PATH . "/include/header.php" ); ?>


        <main>
            <form action="admin.php?action=register" method="post">
                <input type="hidden" name="register" value="true" />
                <div>
                    <label for="user_first">Prénom:</label>
                    <input type="text" name="user_first_name" id="user_first">
                </div>
                <div>
                    <label for="user_last">Nom de famille:</label>
                    <input type="text" name="user_last_name" id="user_last">
                </div>
                <div>
                    <label for="user_username">Nom d'utilisateur:</label>
                    <input type="text" name="user_username" id="user_username">
                </div>
                <div>
                    <label for="user_password">Mot de passe:</label>
                    <input type="password" name="user_password" id="user_password">
                </div>
                <div>
                    <label for="user_password_secure">Validez le mot de passe:</label>
                    <input type="password" name="user_password" id="user_password">
                </div>
                <div>
                    <label for="user_email">Adresse mail:</label>
                    <input type="email" name="user_email" id="user_email">
                </div>
                <input type="submit" value="Submit">
            </form>
        </main>

<?php require( TEMPLATE_PATH . "/include/footer.php" ); ?>