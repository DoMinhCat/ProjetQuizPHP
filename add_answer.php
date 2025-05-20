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
$title = 'Add answer';
require 'includes/head.php';
if (isset($_SESSION['email']) || !empty($_SESSION['email'])) {
    echo '<script src="includes/check_timeout.js"></script>';
}

require 'traitements/processing.php';

if (!isset($_GET['id_question']) || empty($_GET['id_question'])) {
    header('Location: update_question.php?message=Erreur lors du chargement de la page !');
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
                <h1 class="text-center mt-3 mb-5 display-4 fw-bold">Ajouter une Réponse à la Question</h1>
                <div class="mb-4 text-start">
                    <a href="update_question.php?id=<?= $_GET['id_question'] ?>"
                        class="btn btn-secondary" style="border-radius: 10px;">
                        Retourner à la question
                    </a>
                </div>
                <form action="traitements/processing.php" method="POST">
                    <input type="hidden" name="id_question_add" value="<?php echo isset($_GET['id_question']) ? $_GET['id_question'] : ''; ?>" />

                    <div class="mb-4">
                        <label class="form-label fs-4 fw-bold mt-4">Contenu de la Réponse :</label>
                        <textarea name="content_response_add" class="form-control form-control-lg fs-3" rows="3" required style="border-radius: 10px; background-color: #f0f0f0; color: #000;" placeholder="Entrez la réponse ici"></textarea>
                    </div>

                    <div class="my-4">
                        <label class="form-label fs-4 fw-bold mt-4">Réponse correcte ?</label>
                        <select name="is_correct_add" class="form-control form-control-lg fs-3" required style="border-radius: 10px; background-color: #f0f0f0; color: #000;">
                            <option value="0">Non</option>
                            <option value="1">Oui</option>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="add_response" class="btn btn-primary btn-lg px-5 py-3 fs-4 fw-bold mt-3" style="border-radius: 10px; color: #000;">Ajouter la Réponse</button>
                    </div>
                </form>
            </div>
        </div>
        <?php require('includes/footer.php') ?>
    </main>

</body>

</html>