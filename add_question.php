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
$title = 'Add question';
require 'includes/head.php';
if (isset($_SESSION['email']) || !empty($_SESSION['email'])) {
    echo '<script src="includes/check_timeout.js"></script>';
}

require 'traitements/processing.php';

if (!isset($_GET['id_quiz']) || empty($_GET['id_quiz'])) {
    header('Location: update_quiz.php?message=Erreur lors du chargement de la page !');
    exit();
}
?>

<body>
    <?php
    require 'includes/header.php';
    ?>

    <main class="container pb-5 mt-3">
        <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
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

                <h1 class="text-center mb-5 display-4 fw-bold">Ajouter une Question au Quiz</h1>

                <form action="traitements/processing.php" method="POST">
                    <div class="mb-4">
                        <input type="hidden" name="id_quiz" value="<?php echo isset($_GET['id_quiz']) ? $_GET['id_quiz'] : ''; ?>" />
                        <div class="mb-4 text-start">
                            <a href="update_quiz.php?id=<?= $_GET['id_quiz'] ?>"
                                class="btn btn-secondary" style="border-radius: 10px;">
                                Retourner au quiz
                            </a>
                        </div>
                        <label class="form-label fs-4 fw-bold">Contenu de la Question :</label>
                        <textarea name="content" class="form-control form-control-lg fs-3" rows="4" required style="border-radius: 10px; background-color: #f0f0f0; color: #000;" placeholder="Entrez la question ici"></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="add_question" class="btn btn-primary btn-lg px-5 py-3 fs-4 fw-bold mt-3" style="border-radius: 10px;">Ajouter la Question</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <?php require('includes/footer.php') ?>
</body>

</html>