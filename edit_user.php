<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || empty($_SESSION['role']) || ($_SESSION['role'] != 2 && $_SESSION['role'] != 3)) {
    header('location:erreur.php?message=Vous n\'avez pas de droit pour accéder à cette page !');
    exit();
}
require('includes/check_timeout.php');
require('includes/db.php');

$stmt = $bdd->prepare("SELECT role FROM users WHERE username = ?");
$stmt->execute([$_SESSION['pseudo']]);
$authUser = $stmt->fetch();

if (!$authUser || $authUser['role'] < 2) {
    header('Location: index.php?error=Accès refusé');
    exit();
}

// Vérifie si un ID est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_account.php?error=ID manquant pour l'édition");
    exit();
}

$id = $_GET['id'];
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = (int) $_POST['role'];

    if (!empty($username) && !empty($email) && !empty($role)) {
        try {
            $stmt = $bdd->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
            $stmt->execute([$username, $email, $role, $id]);
            header("Location: manage_account.php?message=modification_ok");
            exit();
        } catch (Exception $e) {
            $msg = "Erreur lors de la modification : " . $e->getMessage();
        }
    } else {
        $msg = "Tous les champs sont obligatoires.";
    }
}

$stmt = $bdd->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: manage_account.php?error=Utilisateur introuvable");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php $title = "Édition d'utilisateur";
require('includes/head.php');
if (isset($_SESSION['email']) || !empty($_SESSION['email'])) {
    echo '<script src="includes/check_timeout.js"></script>';
}
?>

<body>
    <?php include('includes/header.php'); ?>

    <div class="container py-4">
        <h1>Modifier l'utilisateur</h1>

        <?php if (!empty($msg)) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($msg) . '</div>';
        } ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Nom d'utilisateur</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Rôle</label>
                <select name="role" class="form-select">
                    <option value="3" <?= $user['role'] == '3' ? 'selected' : '' ?>>Super Admin</option>
                    <option value="2" <?= $user['role'] == '2' ? 'selected' : '' ?>>Admin</option>
                    <option value="1" <?= $user['role'] == '1' ? 'selected' : '' ?>>Utilisateur</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="manage_account.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
    <?php require('includes/footer.php') ?>
</body>

</html>