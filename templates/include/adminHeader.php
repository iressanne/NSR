<div class="card" id="adminHeader">
    <img src="<?php echo $user->user_cover ? UPLOAD_PATH . "users/" . $user->user_cover : UPLOAD_PATH . "users/user.png"; ?>" class="card-img-top" alt="<?php echo $user->user_username; ?>">
    <div class="card-body">
        <h5 class="card-title"><?php echo $user->user_username; ?></h5>
        <h6 class="card-subtitle mb-2 text-muted small"><?php echo $user->user_role == "" ? "Utilisateur" : ucfirst( $user->user_role ); ?></h6>
        <p class="card-text">Bonjour <?php echo $user->user_first_name; ?> <?php echo $user->user_last_name; ?>, vous êtes actuellement connecté.</p>
        <a href="admin.php?action=logout" class="btn btn-secondary btn-sm">Déconnexion</a>
    </div>
</div>