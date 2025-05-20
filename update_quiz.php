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
$title = 'Update quiz';
require 'includes/head.php';
require 'traitements/processing.php';
if (isset($_SESSION['email']) || !empty($_SESSION['email'])) {
    echo '<script src="includes/check_timeout.js"></script>';
}
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: add_quiz.php?message=Erreur lors du chargement de la page !');
    exit();
}

$data_quiz = quiz_infos($_GET['id']);
$questions = info_question_quiz($_GET['id']);
?>

<body>
    <?php
    require 'includes/header.php';
    ?>
    <main class="container pb-5 mt-3">
        <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
            <div class="card shadow-lg p-5 mt-5" style="width: 100%; max-width: 600px; border-radius: 15px;">
                <?php
                if (!empty($_GET['message'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($_GET['message']) ?>
                    </div>
                <?php elseif (!empty($_GET['message_succes'])): ?>
                    <div class="alert alert-success" role="alert">
                        <?= htmlspecialchars($_GET['message_succes']) ?>
                    </div>
                <?php endif; ?>
                <div class="text-center mb-4">
                    <button type="button" class="btn btn-danger btn-block my-3" style="border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#confirmDeleteQuizModal">
                        Supprimer ce quiz
                    </button>
                </div>

                <h1 class="text-center mb-5 display-4 fw-bold text-primary">Modifier le quiz</h1>

                <form id="updateQuizForm" action="traitements/processing.php" method="POST">
                    <div class="mb-4">
                        <label for="title" class="form-label fs-4 fw-bold">Titre du Quiz :</label>
                        <input type="text" id="title" name="title_update" class="form-control form-control-lg fs-3" required style="border-radius: 10px; background-color: #f0f0f0; color: #000;" value="<?php echo isset($data_quiz['title']) ? $data_quiz['title'] : ''; ?>">
                        <input type="hidden" name="id" class="form-control form-control-lg fs-3" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
                    </div>

                    <div class="text-center">
                        <button type="button" class="btn btn-primary btn-lg px-5 py-3 fs-4 fw-bold my-3" style="border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#confirmSaveQuizModal">
                            Sauvegarder
                        </button>
                    </div>
                </form>

                <h2 class="mt-5 text-secondary">Questions liées à ce quiz :</h2>

                <ul class="list-group mt-3">
                    <?php if (empty($questions)): ?>
                        <li class="list-group-item text-center my-3">Aucune question n'a été ajoutée à ce quiz.</li>
                    <?php else: ?>
                        <?php foreach ($questions as $question): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><?php echo htmlspecialchars($question['content']); ?></span>
                                <div>
                                    <a href="update_question.php?id_question=<?php echo htmlspecialchars($question['id']); ?>" class="btn btn-sm btn-primary mr-2">Modifier</a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteQuestionModal<?php echo $question['id']; ?>">Supprimer</button>

                                    <div class="modal fade" id="confirmDeleteQuestionModal<?php echo $question['id']; ?>" tabindex="-1" aria-labelledby="confirmDeleteQuestionModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="confirmDeleteQuestionModalLabel">Confirmer la suppression</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Êtes-vous sûr de vouloir supprimer cette question ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <a href="update_question.php?id_question_supr=<?php echo htmlspecialchars($question['id']); ?>" class="btn btn-danger">Supprimer</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>

                <p class="mt-4 text-center">
                    <a href="add_question.php?id_quiz=<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>" class="btn btn-success btn-lg mt-4">Ajouter une nouvelle question</a>
                </p>

                <div class="modal fade" id="confirmDeleteQuizModal" tabindex="-1" aria-labelledby="confirmDeleteQuizModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="confirmDeleteQuizModalLabel">Confirmer la suppression</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Êtes-vous sûr de vouloir supprimer ce quiz, cette action va également supprimer toutes ses questions et toutes ses réponses ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <form method="POST" action="traitements/processing.php" style="display:inline;">
                                    <input type="hidden" name="id_delete" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
                                    <button type="submit" name="delete_quiz" class="btn btn-danger">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="confirmSaveQuizModal" tabindex="-1" aria-labelledby="confirmSaveQuizModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="confirmSaveQuizModalLabel">Confirmer la sauvegarde</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Êtes-vous sûr de vouloir sauvegarder les modifications ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="button" class="btn btn-primary" onclick="document.getElementById('updateQuizForm').submit()">Sauvegarder</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php require 'includes/footer.php'; ?>
    </main>
</body>

</html>