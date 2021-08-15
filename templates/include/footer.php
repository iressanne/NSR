<footer>
            <ul>
            <li><?php echo logged_in() ? '<a href="admin.php">Mon compte</a>' : '<a href="admin.php?action=login">Se connecter</a>'; ?></li>
            <?php if( logged_in() ) echo '<li><a href="admin.php?action=newArticle">Poster un article</a></li>'; ?>
                <li><a href="contact.php">Nous contacter</a></li>
            </ul>
        </footer>

    <?php unset( $conn ); ?></body>
</html>