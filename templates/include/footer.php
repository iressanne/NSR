<footer>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid"> 
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item"><?php echo logged_in() ? '<a href="admin.php" class="nav-link">Mon compte</a>' : '<a href="admin.php?action=login" class="nav-link">Se connecter</a>'; ?></li>
                <?php if( logged_in() && $user->user_role == "admin" || logged_in() && $user->user_role == "author"  ) echo '<li class="nav-item"><a href="admin.php?action=newArticle" class="nav-link">Poster un article</a></li>'; ?>
                <li class="nav-item"><a href="contact.php" class="nav-link">Nous contacter</a></li>
            </ul>
        </div>
    </nav>
           
</footer>

    <script src='assets/js/script.js'></script>

    <?php unset( $conn ); ?></body>
</html>