<?php
require('includes/check_session.php');
require('includes/check_timeout.php');
if (!isset($_SESSION['role']) || empty($_SESSION['role']) || ($_SESSION['role'] != 2 && $_SESSION['role'] != 3)) {
    header('location:erreur.php?message=Vous n\'avez pas de droit pour accéder à cette page !');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php
$title = 'Add quiz';
require 'includes/head.php';
if (isset($_SESSION['email']) || !empty($_SESSION['email'])) {
    echo '<script src="includes/check_timeout.js"></script>';
}
?>

<body>
    <?php
    require 'includes/header.php';
    ?>
    <main class="container pb-5 mt-3">
        <div class="container-fluid d-flex justify-content-center align-items-center min-vh-70 mt-5">
            <div class="card shadow-lg p-5" style="width: 100%; max-width: 600px; border-radius: 15px;">
                <?php
                if (!empty($_GET['message'])): ?>
                    <div class="alert alert-danger my-3" role="alert">
                        <?= htmlspecialchars($_GET['message']) ?>
                    </div>
                <?php elseif (!empty($_GET['message_succes'])): ?>
                    <div class="alert alert-success my-3" role="alert">
                        <?= htmlspecialchars($_GET['message_succes']) ?>
                    </div>
                <?php endif; ?>
                <h1 class="text-center mb-5 display-4 fw-bold">Création d'un Quiz</h1>

                <form action="traitements\processing.php" method="POST">
                    <div class="mb-4">

                        <input type="hidden" name="create_quiz" value="quiz_add" />

                        <label for="title" class="form-label fs-4 fw-bold">Titre du Quiz :</label>
                        <input type="text" name="title" class="form-control form-control-lg fs-3" required style="border-radius: 10px; background-color: #f0f0f0; color: #000;" placeholder="Entrer le titre ici ">
                        <div id="titre_quiz_help" class="form-text">Ce titre doit comporter entre 10 et 100 caractères.</div>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="create_quiz" class="btn btn-primary btn-lg px-5 py-3 fs-4 fw-bold" style="border-radius: 10px;">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <?php require('includes/footer.php') ?>
</body>

</html>