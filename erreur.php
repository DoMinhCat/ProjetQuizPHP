<?php
if (!empty($_GET['message'] && ($_GET['message']) == 'Session expirée. Veuillez vous reconnecter !')) {
    session_start();
    session_unset();
    session_destroy();
}
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<?php
$title = 'Oops, erreur!';
include('includes/head.php');
?>

<body>
    <?php
    include('includes/header.php');
    ?>
    <main class="container">

        <h1 class="text-center mt-5 my-4">Une erreur s'est produite !!</h1>

        <?php if (isset($_GET['message']) || !empty($_GET['message']))
            echo '<div class="alert alert-danger text-center my-3" role="alert">' . htmlspecialchars($_GET['message']) . '</div>' ?>
        <div style="margin:auto;width:50%;">
            <a style="width: 100%;" class="btn btn-primary mt-2" href="index.php">Revenir à la page Acceuil</a>
        </div>
        <?php require('includes/footer.php') ?>
    </main>
</body>

</html>