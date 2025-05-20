<?php
session_start();
if (isset($_SESSION['email']) || !empty($_SESSION['email'])) {
    header('location:index.php?message=Vous vous êtes déjà connecté !');
    exit();
}
?>
<html>
<?php
$title = "Connexion";
require 'includes/head.php';
?>

<body>
    <?php
    require 'includes/header.php'; ?>
    <main class="container mt-3">
        <?php

        if (isset($_GET['error']) || !empty($_GET['error'])) {
            echo '<div class="alert alert-danger" role="alert">';
            echo htmlspecialchars($_GET['error']);
            echo '</div>';
        }
        ?>
        <div class="container-fluid d-flex justify-content-center align-items-center min-vh-70 mt-5">
            <div class="card shadow-lg p-4" style="width: 100%; max-width: 600px; border-radius: 15px;">
                <h1 class="my-2 text-center">Connexion</h1>
                <form method="POST" action="traitements/processing.php">
                    <input type="hidden" name="form_type" value="connexion">
                    <div class="my-3 form-floating">
                        <input required type="email" name="email" class="form-control" id="Email" aria-describedby="emailHelp" placeholder="Email" value="<?= isset($_GET['emailLogin']) ? htmlspecialchars($_GET['emailLogin']) : '' ?>">
                        <label for="Email">Email address</label>

                    </div>

                    <div class="mb-3 form-floating">
                        <input required type="password" name="password" class="form-control" id="mdp" placeholder="Password">
                        <label for="mdp">Password</label>
                    </div>

                    <input class="btn btn-primary" type="submit" value="Se connecter" style="width: 100%;">
                </form>
            </div>
        </div>
        <?php require('includes/footer.php') ?>
    </main>

</body>

</html>