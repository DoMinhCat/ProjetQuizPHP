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
$title = 'Update question';
require 'includes/head.php';
require 'traitements/processing.php';
if (isset($_SESSION['email']) || !empty($_SESSION['email'])) {
    echo '<script src="includes/check_timeout.js"></script>';
}
if (isset($_GET['id_question_supr'])) {
    delete_question($_GET['id_question_supr']);
}

if (!isset($_GET['id_question']) || empty($_GET['id_question'])) {
    header('Location: add_quiz.php?message=Erreur lors du chargement de la page !');
    exit();
}

$data_question = info_question($_GET['id_question']);
$responses = info_answer($_GET['id_question']);
$data_quiz = quiz_infos($data_question[0]['id_quiz']);
?>

<body>
    <?php
    require 'includes/header.php';
    ?>
    <main class="container pb-5 mt-3">
        <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
            <div class="card shadow-lg p-5" style="width: 100%; max-width: 600px; border-radius: 15px;">

                <?php if (!empty($_GET['message'])): ?>
                    <div class="alert alert-danger my-3" role="alert">
                        <?= htmlspecialchars($_GET['message']) ?>
                    </div>
                <?php elseif (!empty($_GET['message_succes'])): ?>
                    <div class="alert alert-success my-3" role="alert">
                        <?= htmlspecialchars($_GET['message_succes']) ?>
                    </div>
                <?php endif; ?>

                <h1 class="text-center my-4"><?= htmlspecialchars($data_quiz['title']) ?></h1>

                <div class="mb-3 text-start">
                    <a href="update_quiz.php?id=<?= htmlspecialchars($data_question[0]['id_quiz']) ?>"
                        class="btn btn-secondary" style="border-radius: 10px;">
                        Retourner au quiz
                    </a>
                </div>

                <div class="text-center mb-4">
                    <form method="POST" action="traitements/processing.php">
                        <input type="hidden" name="id_question_delete" value="<?= htmlspecialchars($_GET['id_question']) ?>">
                        <button type="submit" name="delete_question" class="btn btn-danger btn-block my-3" style="border-radius: 10px;">Supprimer cette question</button>
                    </form>
                </div>

                <h1 class="text-center mb-5 display-4 fw-bold text-primary">Modifier la Question</h1>

                <form id="updateQuestionForm" action="traitements/processing.php" method="POST">
                    <div class="mb-4">
                        <label class="form-label fs-4 fw-bold">Contenu de la Question :</label>
                        <textarea name="content_update" class="form-control form-control-lg fs-3" required style="border-radius: 10px; background-color: #f0f0f0; color: #000;" rows="4"><?= htmlspecialchars($data_question[0]['content']) ?></textarea>
                        <input type="hidden" name="id_question_update" value="<?= htmlspecialchars($_GET['id_question']) ?>">
                    </div>

                    <div class="text-center">
                        <button type="button" class="btn btn-primary btn-lg px-5 py-3 fs-4 fw-bold my-3" style="border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#confirmSaveModal">
                            Sauvegarder
                        </button>
                    </div>
                </form>

                <h2 class="mt-5 text-secondary">Réponses associées à cette question :</h2>

                <?php if (empty($responses)): ?>
                    <p class="text-center mt-3">Aucune réponse n'a été ajoutée pour cette question.</p>
                <?php else: ?>
                    <table class="table table-striped mt-3">
                        <thead>
                            <tr>
                                <th scope="col">Contenu</th>
                                <th scope="col">Correcte ?</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($responses as $response): ?>
                                <tr>
                                    <td><?= htmlspecialchars($response['content']) ?></td>
                                    <td><?= $response['correct'] == 1 ? 'Oui' : 'Non' ?></td>
                                    <td>
                                        <a href="update_answer.php?id=<?= htmlspecialchars($response['id']) ?>" class="btn btn-sm btn-primary">Modifier</a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteResponseModal<?= htmlspecialchars($response['id']) ?>">Supprimer</button>
                                        <div class="modal fade" id="confirmDeleteResponseModal<?= htmlspecialchars($response['id']) ?>" tabindex="-1" aria-labelledby="confirmDeleteResponseModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="confirmDeleteResponseModalLabel">Confirmer la suppression</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Êtes-vous sûr de vouloir supprimer cette réponse ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <a href="update_answer.php?id_answer_supr=<?= htmlspecialchars($response['id']) ?>" class="btn btn-danger">Supprimer</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <p class="mt-4 text-center">
                    <a href="add_answer.php?id_question=<?= htmlspecialchars($_GET['id_question']) ?>" class="btn btn-success btn-lg">Ajouter une nouvelle réponse</a>
                </p>

                <div class="modal fade" id="confirmSaveModal" tabindex="-1" aria-labelledby="confirmSaveModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="confirmSaveModalLabel">Confirmer la sauvegarde</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Êtes-vous sûr de vouloir sauvegarder les modifications ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="button" class="btn btn-primary" onclick="document.getElementById('updateQuestionForm').submit()">Sauvegarder</button>
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