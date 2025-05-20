<?php
session_start();
if (isset($_SESSION['email']) || !empty($_SESSION['email'])) {
    header('location:index.php?message=Vous vous êtes déjà connecté !');
    exit();
}
?>
<html>
<?php
$title = "Inscription";
require 'includes/head.php';
?>

<body>
    <?php
    require 'includes/header.php'; ?>
    <main class="container">

        <?php
        if (isset($_GET['error']) || !empty($_GET['error'])) {
            echo '<div class="alert alert-danger mt-4" role="alert">';
            echo htmlspecialchars($_GET['error']);
            echo '</div>';
        }
        ?>
        <div class="container-fluid d-flex justify-content-center align-items-center min-vh-70 mt-5">
            <div class="card shadow-lg p-4" style="width: 100%; max-width: 600px; border-radius: 15px;">
                <h1 class="my-2 text-center">Inscription</h1>

                <form method="POST" action="traitements/processing.php">
                    <input type="hidden" name="form_type" value="inscription">
                    <div class="my-3 form-floating">
                        <input required="text" class="form-control" id="pseudo" name="pseudo" placeholder="Username" value="<?= isset($_GET['username']) ? htmlspecialchars($_GET['username']) : '' ?>">
                        <label for="pseudo">Username</label>
                    </div>
                    <div class="my-3 form-floating">
                        <input required type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '' ?>">
                        <label for="email">Email</label>
                    </div>
                    <div class="my-3 form-floating">
                        <input required type="password" class="form-control" id="mdp" name="password" placeholder="Password">
                        <label for="mdp">Password</label>
                        <div id="PasswordHelp" class="form-text">Votre mot de passe doit comporter entre 8 et 16 caractères, au moins une lettre majuscule, un chiffre et un caractère spécial.</div>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Je m'inscris" style="width: 100%;">
                </form>
            </div>
        </div>
        <?php require('includes/footer.php') ?>
    </main>
</body>

</html>