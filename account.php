<?php
require('includes/check_session.php');
require('includes/check_timeout.php');
require('includes/db.php');
$id = $_SESSION['id'];

$req = $bdd->prepare('SELECT id, username, email FROM users WHERE id = ?');
$req->execute([$id]);
$user = $req->fetch();
if (!$user) {
    header("Location:index.php?message=" . urlencode('Une erreur s\'est produite, veuillez réessayer plus tard.'));
}
$username = $user['username'];
$email = $user['email'];
?>
<!DOCTYPE html>
<html lang="fr">

<?php
$title = 'Mon Compte';
include('includes/head.php');
include('includes/header.php');
?>
<script src="includes/check_timeout.js"></script>

<body>
    <main class="container py-4">
        <?php
        if (isset($_GET['error']) && !empty($_GET['error'])) { ?>
            <div class="alert alert-danger" role="alert">
            <?php
            echo  htmlspecialchars($_GET['error']);
        }

            ?>
            </div>
            <?php
            if (isset($_GET['succes']) && !empty($_GET['succes'])) { ?>
                <div class="alert alert-success" role="alert">
                <?php
                echo htmlspecialchars($_GET['succes']);
                echo '</div>';
            }
                ?>
                <h1 class="mb-5 text-center">Mon profil</h1>
                <form method="post" action="traitements/processing.php">
                    <input type="hidden" name="form_type" value="update_account" />
                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($username) ?>" required>

                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control" name="password" placeholder="Laissez vide si vous ne voulez pas le changer">
                        <div id="PasswordHelp" class="form-text">Votre mot de passe doit comporter entre 8 et 12 caractères, au moins une lettre majuscule, un chiffre et un caractère spécial.</div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-1">Mettre à jour</button>


                </form>

                <button type="button" class="btn btn-danger mt-5" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Supprimer mon compte
                </button>


                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation de suppression de compte</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="m-0">Êtes-vous sûr de vouloir supprimer définitivement votre compte ?</p>
                                <br>
                                <p class="m-0">
                                    Cette action va également supprimer tous les éléments liés à ce compte (quiz, questions, résultats,...).
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                <a href="delete_account.php" type="button" class="btn btn-danger">Supprimer mon compte</a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include('includes/footer.php') ?>
    </main>

</body>

</html>