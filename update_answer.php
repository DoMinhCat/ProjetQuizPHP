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
$title = 'Update answer';
require 'includes/head.php';
if (isset($_SESSION['email']) || !empty($_SESSION['email'])) {
  echo '<script src="includes/check_timeout.js"></script>';
}

require 'traitements/processing.php';

if (isset($_GET['id_answer_supr'])) {
  delete_answer($_GET['id_answer_supr']);
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
  header('Location: add_quiz.php?message=Erreur lors du chargement de la page !');
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
        $data_answer = info_answer_update($_GET['id']);
        $data_question = info_question($data_answer[0]['id_question']);

        if (!empty($_GET['message'])): ?>
          <div class="alert alert-danger my-3" role="alert">
            <?= htmlspecialchars($_GET['message']) ?>
          </div>
        <?php elseif (!empty($_GET['message_succes'])): ?>
          <div class="alert alert-success my-3" role="alert">
            <?= htmlspecialchars($_GET['message_succes']) ?>
          </div>
        <?php endif; ?>

        <h1 class="text-center mb-3"><?php echo $data_question[0]['content'] ?></h1>
        <div class="my-3 text-start">
          <a href="update_question.php?id_question=<?php echo $data_answer[0]['id_question']; ?>"
            class="btn btn-secondary"
            style="border-radius: 10px;">
            Retourner au quiz
          </a>
        </div>
        <h1 class="text-center mb-5 display-4 fw-bold text-primary">Modifier la Réponse</h1>

        <form id="updateForm" action="traitements/processing.php" method="POST">
          <div class="mb-4">
            <label class="form-label fs-4 fw-bold">Contenu de la Réponse :</label>
            <textarea name="content_answer_update_answer" class="form-control form-control-lg fs-3" rows="3" required style="border-radius: 10px; background-color: #f0f0f0; color: #000;"><?php echo htmlspecialchars($data_answer[0]['content']); ?></textarea>
          </div>

          <div class="mb-4">
            <label class="form-label fs-4 fw-bold mt-3">Réponse correcte ?</label>
            <select name="is_correct_update_answer" class="form-control form-control-lg fs-3" required style="border-radius: 10px; background-color: #f0f0f0;">
              <option value="0" <?php if ($data_answer[0]['correct'] == 0) echo 'selected'; ?>>Non</option>
              <option value="1" <?php if ($data_answer[0]['correct'] == 1) echo 'selected'; ?>>Oui</option>
            </select>
          </div>

          <input type="hidden" name="id_response_update" value="<?php echo htmlspecialchars($data_answer[0]['id']); ?>">
          <input type="hidden" name="id_question" value="<?php echo htmlspecialchars($data_answer[0]['id_question']); ?>">

          <div class="text-center">
            <button type="button" class="btn btn-primary btn-lg px-5 py-3 fs-4 fw-bold my-3" style="border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#confirmSaveModal">
              Sauvegarder
            </button>
          </div>
        </form>

        <button type="button" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
          Supprimer la Réponse
        </button>

        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="confirmDeleteModalLabel">Confirmer la suppression</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer cette réponse ?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="?id_answer_supr=<?php echo htmlspecialchars($data_answer[0]['id']); ?>" class="btn btn-danger">Supprimer</a>
              </div>
            </div>
          </div>
        </div>

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
                <button type="button" class="btn btn-primary" onclick="document.getElementById('updateForm').submit()">Sauvegarder</button>
              </div>
            </div>
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